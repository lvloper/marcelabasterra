# Draft de bloque: ProgramsListing

## Meta
- **Nombre:** ProgramsListing
- **Categoría:** Listados
- **Label:** Programas académicos

## Schema

| Campo | Tipo | Requerido | Default | Notas |
|-------|------|-----------|---------|-------|
| title | text | no | — | Título de sección. |
| description | textarea | no | — | Introducción. |
| status | select | sí | current | `current`, `historical` o `all`. |
| subjects | select multiple | no | — | Filtro por materia. |
| selected_programs | select multiple relation | no | — | Selección manual opcional. |
| max_items | number | sí | 20 | Entero entre 1 y 50. |
| show_description | toggle | sí | true | Muestra resumen breve. |
| show_period | toggle | sí | true | Muestra año/cuatrimestre. |
| empty_message | text | no | — | Mensaje para índices sin resultados. |

## Comportamiento
- `current` muestra programas vigentes; `historical`, archivo; `all`, ambos.
- Ordena por año descendente y luego cuatrimestre.
- La selección manual prevalece sobre filtros.
- Cada registro muestra título, materia, año, cuatrimestre, descripción y enlace/archivo PDF.
- La descarga sólo aparece si hay archivo; el enlace externo sólo si existe.

## Notas de implementación
- Requiere ampliar `ProgramaAcademico` con materia, año, cuatrimestre, archivo y estado.
- Puede usarse tanto en la página general de Programas como en Grado UBA con filtro por materia.
- Validar PDF y conservar un label accesible con tipo/tamaño de archivo cuando estén disponibles.
- **Estado:** pendiente de revisión y aprobación; no implementar todavía.

