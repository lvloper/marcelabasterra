# Block: Cards

## Meta
- **Nombre:** Cards
- **Categoría:** Otros
- **Label:** Cards

## Schema

| Campo | Tipo | Requerido | Default | Notas |
|-------|------|-----------|---------|-------|
| `title` | text | no | — | Título opcional del bloque |
| `description` | rich (basic) | no | — | Descripción / introducción del bloque |
| `items` | repeater | sí | 3 ítems | Lista de cards. Mínimo 1 ítem. |

### Campos de `items` (repeater)

| Campo | Tipo | Requerido | Default | Notas |
|-------|------|-----------|---------|-------|
| `label` | select | no | — | Etiqueta opcional: `entrevista`, `articulo` o `libro` |
| `title` | text | sí | — | Título de la card |
| `description` | textarea | no | — | Descripción breve (3 rows) |
| `image` | image | no | — | Imagen de la card (800x600) |
| `route` | route | no | — | Enlace opcional de la card. Soporta ancla. |

## Comportamiento
- El primer item del repeater se renderiza como **card destacada** en la columna izquierda (9/12 del ancho en desktop) con `position: sticky`.
- Los items restantes se muestran en una **sidebar derecha** (3/12 del ancho en desktop) en formato compacto, apilados verticalmente.
- En mobile, todo se apila en una sola columna (sin sidebar).
- Si hay un solo item, ocupa el ancho completo sin sidebar.
- El bloque puede tener un título y descripción general arriba del layout.
- Cada card en `items` puede enlazar a una ruta interna o externa.

## Notas de implementación
- En desktop: layout flex con `lg:w-9/12` (destacada + sticky) y `lg:w-3/12` (sidebar con borde izquierdo).
- La card destacada tiene imagen 16:9, título grande (`text-2xl md:text-3xl`), descripción con `line-clamp-3` y enlace "Ver más".
- Las sidebar cards usan `rounded-xl bg-gray-3 overflow-hidden` (mismo contenedor que el resto del sitio): imagen 4:3 opcional, título, descripción breve (`line-clamp-2`) y enlace "Ver más".
- Si una card tiene `route`, todo el contenedor debe ser clickeable (wrap en `<a>`).
- `image` debe mostrar un placeholder si no está definida.
- El botón "Agregar card" permite añadir más ítems desde el CMS.
