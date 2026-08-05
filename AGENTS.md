# AGENTS.md

Guia operativa para que cualquier agente del proyecto interprete comandos cortos sin pedir rutas.

## Design system

- `DESIGN.md` contiene el design system oficial vigente del proyecto y debe ser consultado por todo agente de IA antes de diseñar, maquetar o modificar una interfaz.
- El design system es el único punto de verdad para la dirección visual, colores, tipografía, espaciado, grillas, componentes, interacción, accesibilidad y reglas de transformación de bloques.
- Todas las decisiones de diseño nuevas deben partir de `DESIGN.md`; no se deben introducir criterios visuales propios ni reutilizar la identidad original de una plantilla sin adaptarla al manual.
- Ningún subagente debe hardcodear valores de marca (colores, fuentes, etc.) ni contradecir las reglas obligatorias del manual.
- Si una solicitud, plantilla o referencia visual entra en conflicto con `DESIGN.md`, prevalece `DESIGN.md` y el agente debe adaptar o reemplazar esa decisión.
- Para reutilizar este CMS en otro proyecto: reemplazar `DESIGN.md` y `tailwind.config.js`.

## Politica de modelo para diseno/UI

- Para los pasos de diseno (`design-prompt`, `design-from-html`), usar siempre el modelo de mayor capacidad disponible para tareas de diseno/UX/UI en el entorno.
- Prioridad sugerida: GPT-5.5 o equivalente de maxima capacidad.
- Fallback sugerido: GPT-5.3-Codex.
- Si no esta disponible, usar el mejor fallback disponible y dejar trazabilidad en la respuesta (`modelo_usado` + `motivo_fallback`).

## Defaults de enrutamiento

- Si el usuario dice `bloque`, `hero`, `cta`, `cards`, `quote`, `rich text`, `embed`, asumir dominio CMS Blocks.
- Ruta por defecto para bloques: `docs/cms/blocks/`.
- Ruta por defecto para subagentes de bloques: `docs/cms/blocks/subagents/`.
- Ruta por defecto para paginas CMS: `docs/cms/pages/`.

## Flujo estandar de bloques (4 pasos)

### 1. `draft` — Schema generico del bloque
- Ante pedido tipo: `necesito crear un bloque ...`
- Crear draft editable en `docs/cms/blocks/draft-<kebab>.md` usando la plantilla `docs/cms/blocks/draft.md`.
- Describe el schema del bloque: nombre, categoria, label, campos con tipo y reglas de validacion.
- No ejecutar implementacion en este paso.

### 2. `backend` — Clase PHP + vista con dump
- El usuario confirma el draft.
- El subagente `block-backend` genera:
  - `app/Filament/Blocks/{Name}Block.php` (clase con fields)
  - `resources/views/blocks/{Name}.blade.php` (vista con `@dump(get_defined_vars())`)
  - Registro en template correspondiente
- El draft se reemplaza por `docs/cms/blocks/doc-{Name}.md` (documentacion pura con props del schema bien documentados).
- El bloque es funcional desde el CMS, pero la vista solo muestra la data cruda.

### 3. `carga+valida` — Data real + validacion
- El usuario carga datos reales en el CMS (crea pagina, completa campos del bloque).
- El usuario informa al agente: "cargue tal y tal data".
- El subagente `data-validator` analiza los datos cargados vs el schema documentado en `doc-{Name}.md` y devuelve diagnostico.

### 4. `diseno` — Vista final (3 caminos)
- Una vez validada la data, se disena la vista final.
- **Camino A** `design-prompt`: descripcion textual del diseno -> genera Blade final.
- **Camino B** `design-from-html`: maqueta HTML de referencia -> ignora contenido, extrae estructura, mapea props del schema, genera Blade.
- **Camino C** (Pencil): para disenos grandes. Requiere tener el design system construido en Pencil primero.
- Opcional: pedir "genera la preview" para ejecutar `block-preview-capturer` y tener miniatura en el block picker.

## Comportamiento por defecto

- Si el usuario no da ruta y el contexto habla de bloques, NO preguntar ruta: usar `docs/cms/blocks/`.
- Si faltan datos, completar con placeholders `{{...}}` y marcar `pendiente`.
- Mantener un solo bloque por draft.
- No duplicar pagina existente cuando se indique reemplazo en pagina.

## Referencias obligatorias

- `DESIGN.md`
- `docs/cms/blocks/draft.md`
- `docs/cms/blocks/subagents/block-backend.md`
- `docs/cms/blocks/subagents/data-validator.md`
- `docs/cms/blocks/subagents/design-prompt.md`
- `docs/cms/blocks/subagents/design-from-html.md`
- `docs/cms/blocks/subagents/block-preview-capturer.md`
- `docs/cms/blocks/subagents/block-variants.md`

## Triggers recomendados (lenguaje natural)

- `redisenalo [bloque]` / `rediseña [bloque]` -> redesign: lee props actuales, genera nueva vista (sin tocar backend)
- `necesito crear un bloque ...` -> paso 1 (draft)
- `ya revise el draft` -> esperar confirmacion
- `esta ok, ejecuta` / `aprobado` -> paso 2 (backend)
- `ya cargue los datos` / `cargue tal y tal` -> paso 3 (carga+valida)
- `ahora disenalo` / `dame el diseno` / `maquetemoslo` -> paso 4 (diseno)
- `genera la preview` / `genera la miniatura` -> ejecuta `block-preview-capturer` sobre el bloque
- `te paso una maqueta` / `usa este HTML` -> infiere schema, crea draft para revisar (paso 1)
- `mejora la interfaz`, `mejorar UI`, `mejorar UX`, `mejorar diseno` (o similar) -> usar `docs/cms/blocks/subagents/design-prompt.md`
- `genera versiones de [bloque/componente]` / `genera variantes de X` / `no me gusta la estetica de X` -> subagente `block-variants`: crea 4 opciones alternativas (`-v1..-v4`) apiladas con la misma data, elige y queda una sola vista

## Inventario de bloques

- Usar `php artisan cms:blocks-list` para listar todos los bloques del proyecto (PHP + Blade).
- Opciones: `--orphans` (solo vistas sin clase PHP), `--unregistered` (solo clases no registradas en templates), `--json` (salida JSON para consumo agente).
- Los bloques sin clase PHP son vistas frontend reutilizables pero no tienen backend en el CMS.

## Post-diseno: build obligatorio

- Despues de cualquier cambio de diseno (bloques, vistas, CSS, Tailwind, etc.), ejecutar `npm run build` para compilar assets.
- No esperar a que el usuario lo pida; hacerlo automaticamente al finalizar el cambio de diseno.

## Verificacion post-entrega obligatoria

- Despues de entregar cualquier cambio (data, backend, diseno, fixes), navegar el sitio y verificar que no haya errores antes de dar por cerrada la tarea.
- Comando basico: `curl -s -o /dev/null -w "%{http_code}" http://127.0.0.1:8082/<ruta>` (o el puerto de desarrollo vigente).
- Revisar al menos: `/` (home) y las paginas afectadas por el cambio; si una devuelve 5xx, leer el error (log en `storage/logs/laravel.log` o el body de la respuesta) y corregirlo.
- Verificar tambien 200 en paginas secundarias clave (`sobre-mi`, `publicaciones`, `contacto`) para detectar regresiones.
- No entregar hasta que las rutas afectadas respondan sin errores.

## Acceso al panel

- En desarrollo, las credenciales por defecto del panel `/admin` se definen via variables de entorno o seeders. Consultar `README.md` o `.env.example` para credenciales de desarrollo.
