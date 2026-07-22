# TeachingListing — Props del bloque

## Schema

| Prop | Tipo | Requerido | Default | Descripción |
|------|------|-----------|---------|-------------|
| `title` | `text` | no | — | Título de sección. |
| `description` | `textarea` | no | — | Introducción editorial. |
| `levels` | `select[]` | no | todos | Filtra `posgrado`, `maestria` y `doctorado`. |
| `scopes` | `select[]` | no | ambos | Filtra instituciones nacionales e internacionales. |
| `institutions` | `relation[]` | no | todas | Filtra por `InstitucionAcademica`. |
| `selected_items` | `relation[]` | no | — | Selección manual de `Docencia`; prevalece sobre filtros. |
| `current_only` | `boolean` | sí | `true` | Limita a actividades vigentes. |
| `max_items` | `integer` | sí | `30` | Entre 1 y 60. |
| `show_description` | `boolean` | sí | `true` | Muestra la descripción de la actividad. |
| `show_institutions` | `boolean` | sí | `true` | Muestra la grilla de identidades institucionales. |
| `student_resources` | `repeater` | no | — | Accesos externos del lateral sticky (`label`, `url`). |

## Reglas de renderizado

- Consulta `Docencia` y su relación `InstitucionAcademica`; no duplica el contenido en el bloque.
- Presenta un resumen cuantitativo y agrupa por alcance en dos columnas.
- Cada institución utiliza un `details/summary` nativo: el detalle académico permanece plegado hasta que la persona decide consultarlo.
- Dentro de cada institución agrupa las actividades por nivel.
- El lateral de recursos es sticky sólo en desktop y mantiene el orden lógico del DOM.
- Los logotipos usan el archivo CMS y muestran una sigla tipográfica accesible como fallback.
- No muestra un apartado de equipo docente.
