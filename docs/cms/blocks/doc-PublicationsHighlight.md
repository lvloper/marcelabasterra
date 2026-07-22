# PublicationsHighlight — Props del bloque

## Schema

| Prop | Tipo | Requerido | Default | Descripción |
|------|------|-----------|---------|-------------|
| `title` | `text` | no | | Título de la sección |
| `description` | `textarea` | no | | Texto introductorio |
| `layout` | `select` | sí | `featured` | `featured` o `library_grid` |
| `source_mode` | `toggleButtons` | sí | `manual` | `latest` ordena libros publicados por fecha; `manual` conserva la selección editorial |
| `libros` | `multi-select` | no | | Libros destacados a mostrar |
| `articulos` | `multi-select` | no | | Artículos destacados a mostrar |
| `max_items` | `number` | no | `6` | Máximo de items a mostrar |
| `show_type_label` | `toggle` | no | `true` | Mostrar etiqueta "Libro" / "Artículo" |
| `cta_label` | `text` | no | | Texto del enlace al índice general |
| `cta_route` | `route` | no | | Página de publicaciones |

## Contrato de datos

```json
{
  "type": "PublicationsHighlight",
  "data": {
    "title": "Publicaciones",
    "libros": [1, 3],
    "articulos": [2],
    "max_items": 6,
    "show_type_label": true
  }
}
```

## Reglas de renderizado

- Los multi-select consultan los modelos Libro y ArticuloAcademico.
- El primer registro recibe tratamiento protagonista; `latest` selecciona automáticamente el libro más reciente.
- `library_grid` muestra portadas, título, año, rol y acceso a la ficha en una grilla responsive.
- El CTA general sólo se muestra cuando tiene texto y una ruta válida.
