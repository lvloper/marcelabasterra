# Draft de bloque: TeachingListing

## Meta
- **Nombre:** TeachingListing
- **Categoría:** Listados
- **Label:** Actividad docente

## Schema

| Campo | Tipo | Requerido | Default | Notas |
|-------|------|-----------|---------|-------|
| title | text | no | — | Título de sección. |
| description | textarea | no | — | Introducción. |
| levels | checkbox list | no | — | `grado`, `posgrado`, `doctorado`; vacío incluye todos. |
| institutions | select multiple | no | — | Filtro por instituciones existentes. |
| selected_items | select multiple relation | no | — | Selección manual opcional de `Docencia`. |
| current_only | toggle | sí | true | Sólo actividades vigentes. |
| max_items | number | sí | 12 | Entero entre 1 y 30. |
| show_description | toggle | sí | true | Controla presentación/descripción. |

## Comportamiento
- Lista actividades docentes filtradas por nivel, institución y vigencia.
- La selección manual prevalece sobre los filtros automáticos.
- Cada registro muestra universidad, facultad, carrera/programa, materia, cargo/rol, cátedra, modalidad, período y enlace cuando existan.
- El orden prioriza vigentes y luego período más reciente.
- El bloque nunca genera ni muestra un apartado `Equipo docente`.

## Notas de implementación
- Requiere ampliar `Docencia`; el modelo actual sólo tiene institución, materia, cátedra, nivel y descripción.
- Los datos de UCA y UCES no deben cargarse como vigentes. La exclusión editorial se resuelve en datos, no hardcodeada en la vista.
- Puede reutilizarse con filtros distintos en Docencia, Grado UBA, Posgrado y Doctorado.
- **Estado:** pendiente de revisión y aprobación; no implementar todavía.

