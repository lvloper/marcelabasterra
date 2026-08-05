# Subagente: block-variants

## Objetivo

Generar **N versiones alternativas de diseño** (default 4) de una vista existente — bloque CMS (`blocks.{Name}`) o componente (`components.common.footer`, etc.) — y mostrarlas **apiladas, una debajo de la otra, junto al original y con la misma data**, para que el usuario elija cuál le gusta. Al elegir, solo la ganadora queda como vista única.

## Cuando usarlo

- Trigger: "genera versiones de [bloque/componente]", "genera variantes de X", "no me gusta la estética de X", "dame alternativas de diseño para X".
- Sirve para bloques CMS (`Hero`, `CTA`, `Cards`...) y vistas sueltas (`footer`, `header`, modales...).
- Funciona en cualquier etapa: con vista diseñada o con vista dump (en ese caso primero seguir el paso 2 de diseño estándar).

## Modelo recomendado

- Ejecutar con el modelo de mayor capacidad disponible para diseño/maquetación.
- Prioridad sugerida: GPT-5.5 o equivalente.
- Fallback sugerido: GPT-5.3-Codex.
- Reportar `modelo_usado` y `motivo_fallback` si se usa fallback.

## Entradas requeridas

- **Vista destino**: ruta Blade, ej. `blocks.Hero` o `components.common.footer`.
- `DESIGN.md` — design system del proyecto (obligatorio).
- Si es bloque CMS: `docs/cms/blocks/doc-{Name}.md` — props del schema.
- Vista actual: `resources/views/blocks/{Name}.blade.php` o `resources/views/components/common/footer.blade.php`.
- Referencias de componentes: `resources/views/components/*.blade.php`, `tailwind.config.js`.

## Restricciones

- **La vista original NO se modifica durante la comparación**: se preserva como `{Name}-orig.blade.php`.
- Cada variante `{Name}-v{n}.blade.php` es una **copia completa del archivo original**: el bloque `@php` de preparación de datos y los `<script>` / `@pushOnce` deben permanecer intactos. Solo se rediseña el markup.
- Los 4 diseños deben ser **direcciones visuales claramente distintas** entre sí y del original (ej: editorial mínima, contraste oscuro, centrado asimétrico, compacto denso).
- No hardcodear valores de marca: clases Tailwind alineadas a los tokens del DS.
- Mobile-first siempre.
- Todo elemento visual debe mapearse a un prop del schema (bloques) o a data real existente (componentes).
- No modificar la data del CMS ni los archivos de layout.
- Si es bloque CMS, no renombrar el tipo ni los campos.

## Proceso

### 1. Preparación

1. Identificar la vista destino exacta (preguntar si hay ambigüedad; si el usuario dice "footer"/"header", asumir `components.common.*`).
2. Leer `DESIGN.md`, la vista actual y (si es bloque CMS) `doc-{Name}.md` + `app/Filament/Blocks/{Name}Block.php`.
3. Leer 1-2 vistas existentes como referencia de convenciones Blade.

### 2. Generar las variantes

4. Crear `resources/views/blocks/{Name}-v1.blade.php` … `-v{n}.blade.php` (o `components/common/footer-v1.blade.php` …):
   - Copiar el archivo original completo.
   - Reemplazar SOLO el markup (la sección HTML/Tailwind), manteniendo `@php` de datos, clases del wrapper (`<x-block>`), estilos y scripts.
   - Cada variante con una dirección visual distinta.
5. Backup del original: renombrar `{Name}.blade.php` → `{Name}-orig.blade.php`.

### 3. Wrapper temporal de comparación

6. Crear `{Name}.blade.php` (nuevo) que apila, una debajo de la otra, con barra sticky de identificación:

```blade
{{-- TEMPORAL: comparador de variantes. Elegir y pedir "me quedo con la opción N". --}}
@php $base = 'blocks.Hero'; @endphp {{-- o 'components.common.footer' --}}
<style>
    .variants-lab {
        position: sticky; top: 0; z-index: 40;
        display: flex; align-items: center; justify-content: space-between; gap: 1rem;
        padding: 0.5rem 1rem; background: rgba(15,23,42,.92); color: #fff;
        font: 600 .8rem/1 var(--font-body, sans-serif); letter-spacing: .04em;
    }
    .variants-lab span { opacity: .7; font-weight: 400; }
</style>

<div class="variants-lab">Original · {{ $base }} <span>{{ $base }}</span></div>
@include('{{ $base }}-orig', array_except(get_defined_vars(), ['__env', '__data', '__path']))

@for ($i = 1; $i <= 4; $i++)
    @php $labView = $base . '-v' . $i; @endphp
    @if (view()->exists($labView))
        <div class="variants-lab">Opción {{ $i }} · {{ $labView }} <span>{{ $labView }}</span></div>
        @include($labView, array_except(get_defined_vars(), ['__env', '__data', '__path']))
    @endif
@endfor
```

- **Bloque CMS**: el reenvío de data se hace con `get_defined_vars()` (el wrapper recibe los props esparcidos por `@component`). Definir `$base = 'blocks.Hero'` al inicio.
- **Componente** (footer, header...): no requiere reenvío de data (se autoabastecen de config/menús). Usar `@include('components.common.footer-orig')` y `@include('components.common.footer-v1')` directos.

7. `php artisan view:clear`.
8. `npm run build` (clases Tailwind nuevas).
9. Informar al usuario dónde mirar (la misma página/URL de siempre; el componente/bloque aparece 5 veces apilado). Si es bloque CMS, el preview del block picker de Filament también mostrará el comparador.

### 4. Elección y finalización

10. El usuario elige: "me quedo con la opción 3".
11. Copiar `{Name}-v3.blade.php` → `{Name}.blade.php` (la ganadora reemplaza al wrapper; **queda como vista única**).
12. Borrar `{Name}-orig.blade.php` y las variantes perdedoras `-v1..-v4` (excepto la ganadora).
13. `php artisan view:clear` + `npm run build`.
14. Verificar: `curl -s -o /dev/null -w "%{http_code}" http://127.0.0.1:8082/<ruta>` → 200 en la página afectada, `/`, `sobre-mi`, `publicaciones`, `contacto`. El bloque/componente debe renderizar UNA sola vez.

## Salida obligatoria

- Lista de vistas creadas (`-v1..-v{n}`) con su dirección de diseño.
- URL donde se compara.
- Vista ganadora reemplazada y archivos temporales eliminados.
- `modelo_usado` (+ `motivo_fallback` si aplica).
- Resultado de verificación (HTTP 200).

## Checklist

- [ ] Original intacto durante la comparación (`-orig` preservado)
- [ ] 4 variantes con direcciones visuales distintas y data idéntica
- [ ] Wrapper temporal con barras sticky "Original / Opción N"
- [ ] `php artisan view:clear` y `npm run build` ejecutados
- [ ] Vista única reemplazada con la ganadora
- [ ] Archivos temporales eliminados
- [ ] Página afectada y páginas clave responden 200

## Criterios de done

- El usuario pudo comparar todas las opciones apiladas con la misma data en su lugar de siempre.
- Solo la variante elegida queda como vista única, sin restos temporales.
- No hay regresiones en el sitio.
