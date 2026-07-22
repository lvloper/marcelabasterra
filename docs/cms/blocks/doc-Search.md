# Block: Search

## Meta
- **Nombre:** Search
- **Categoría:** Contenido
- **Label:** Búsqueda

## Schema

| Campo | Tipo | Requerido | Default | Notas |
|-------|------|-----------|---------|-------|
| `mode` | select | sí | `global` | `global` o `academic_catalog` |
| `title` | text | no | `Buscar` | Título del bloque de búsqueda |
| `description` | textarea | no | `Buscá en el sitio lo que necesitás.` | Texto descriptivo (2 rows) |
| `items_per_page` | number | no | `12` | Entre 6 y 24 resultados en el catálogo académico |

## Comportamiento
- `global` conserva la búsqueda general del sitio.
- `academic_catalog` consulta los recursos ruteables existentes y permite filtrar por categoría, año y tema sin duplicar datos en la página.
- El catálogo normaliza libros, artículos, noticias, prensa, entrevistas, conferencias, eventos y videos mediante `AcademicProductionCatalog`.

## Notas de implementación
- El formulario debe ser un `<form>` con `method="GET"` apuntando a la ruta de resultados de búsqueda.
- El input debe tener `name="q"` o el nombre que use el motor de búsqueda del sitio.
- `title` y `description` son personalizables desde el CMS.
- Considerar agregar un ícono de lupa dentro del input.
