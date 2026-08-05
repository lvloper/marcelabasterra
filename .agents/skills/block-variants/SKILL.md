---
name: block-variants
description: Generar versiones alternativas de diseño de un bloque CMS o componente (footer, header, etc.), mostrarlas apiladas una debajo de la otra con la misma data, elegir una y dejarla como vista única. Usar cuando el usuario diga "genera versiones/variantes de X", "no me gusta la estética de X" o pida alternativas de diseño para una vista.
---

# Block Variants

Genera N versiones alternativas (default 4) de una vista existente sin tocar la vista original: las crea como `-v1..-v4`, las apila con barras sticky en el mismo lugar de siempre, y al elegir reemplaza la vista con la ganadora y borra el resto.

## Flujo

1. Seguir el proceso completo de `docs/cms/blocks/subagents/block-variants.md`.
2. Identificar la vista destino (preguntar si hay ambigüedad; "footer"/"header" → `components.common.*`).
3. Leer `DESIGN.md`, la vista actual y (si es bloque CMS) `doc-{Name}.md`.
4. Crear `{Name}-v1..v{n}.blade.php`: **copia completa** del original (mantener `@php` de datos, `<x-block>`, estilos y scripts) cambiando solo el markup. 4 direcciones visuales distintas.
5. Renombrar el original a `{Name}-orig.blade.php`.
6. Crear wrapper `{Name}.blade.php` que apila Original + v1..v4 con barras sticky:
   - Bloques: `@include('blocks.{Name}-orig', array_except(get_defined_vars(), ['__env', '__data', '__path']))`
   - Componentes: `@include('components.common.footer-orig')` directo (se autoabastecen de data).
7. `php artisan view:clear` + `npm run build`.
8. Indicar al usuario la URL donde comparar (la misma de siempre; ahora muestra las opciones apiladas).
9. Al elegir ("me quedo con la opción N"): copiar la ganadora a `{Name}.blade.php`, borrar `-orig` y las perdedoras, `view:clear` + `npm run build`.
10. Verificar: curl 200 en la página afectada, `/`, `sobre-mi`, `publicaciones`, `contacto`; el bloque/componente debe renderizar una sola vez.

## Modelo

- Usar el modelo de mayor capacidad disponible para diseño (ver política en `AGENTS.md`).
- Reportar `modelo_usado` y `motivo_fallback` si aplica.

## Prompt de ejemplo del usuario

- `genera 4 versiones del footer`
- `genera variantes del bloque Hero`
- `no me gusta la estética del CTA, dame alternativas`

## Criterios de done

- Comparación visible apilada con la misma data en su lugar de siempre.
- Vista única reemplazada con la ganadora, sin archivos temporales.
- Sin regresiones (verificación 200).
