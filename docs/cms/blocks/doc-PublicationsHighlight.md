# PublicationsHighlight — Props del bloque

## Schema

| Prop | Tipo | Requerido | Default | Descripción |
|------|------|-----------|---------|-------------|
| `libro_id` | `select` | sí | | Libro a destacar |
| `image` | `image` | no | | Portada principal (si no se sube, usa la del libro) |
| `image_2` | `image` | no | | Portada secundaria |
| `title` | `text` | no | | Título de sección |
| `date` | `text` | no | | Año (si no se completa, usa el del libro) |
| `subtitle` | `text` | no | | Subtítulo (si no se completa, usa el del libro) |
| `publisher` | `text` | no | | Editorial (si no se completa, usa la del libro) |
| `cta_label` | `text` | no | `Ver publicación` | Texto del botón de acción |
| `cta_route` | `route` | no | | Enlace del botón de acción |

## Estructura

Tres columnas en desktop:
1. **Información del libro**: año, autor, título, subtítulo, editorial y botón de acción
2. **Portada**: imagen del libro centrada y destacada
3. **Otros libros**: lista de hasta 4 publicaciones recientes con miniatura, año y título

Debajo: enlace centrado al listado completo de libros.

En mobile el orden es: información → otros libros → portada.
