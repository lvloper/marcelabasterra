# Draft de bloque: EventsListing

## Meta
- **Nombre:** EventsListing
- **Categoría:** Listados
- **Label:** Listado de actividades

## Schema

| Campo | Tipo | Requerido | Default | Notas |
|-------|------|-----------|---------|-------|
| title | text | no | — | Título de la sección. |
| description | textarea | no | — | Introducción. |
| status | select | sí | upcoming | `upcoming`, `past` o `all`. |
| event_types | checkbox list | no | — | Jornada, congreso, clase, conferencia, exposición, panel, presentación. |
| selected_events | select multiple relation | no | — | Curaduría manual opcional. |
| max_items | number | sí | 12 | Entero entre 1 y 50. |
| show_image | toggle | sí | true | Imagen opcional por actividad. |
| show_description | toggle | sí | true | Resumen opcional. |
| show_empty_fallback | toggle | sí | false | Permite enlazar a realizados cuando no hay próximos. |
| fallback_route | route | no | — | Sólo visible si el fallback está habilitado. |
| fallback_label | text | no | Ver actividades realizadas | Texto del fallback. |

## Comportamiento
- `upcoming`: fecha ascendente desde hoy.
- `past`: fecha descendente anterior a hoy.
- `all`: fecha descendente.
- La selección manual prevalece sobre la consulta automática.
- Cada entrada muestra actividad, institución, lugar/modalidad, fecha, rol, estado de confirmación y enlace/video cuando existan.
- Si está vacío, se oculta salvo que `show_empty_fallback` esté activo.

## Notas de implementación
- No reemplazar `EventsHighlight`; este bloque cubre índices y separación estricta próximos/realizados.
- Requiere ampliar `Evento` con institución, rol, modalidad, confirmación, imagen y video.
- Comparar fechas usando la zona horaria de la aplicación (`America/Argentina/Buenos_Aires`).
- **Estado:** pendiente de revisión y aprobación; no implementar todavía.

