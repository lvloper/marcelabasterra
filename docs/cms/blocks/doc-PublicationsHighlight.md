# PublicationsHighlight — Props del bloque

## Schema

| Prop | Tipo | Requerido | Default | Descripción |
|------|------|-----------|---------|-------------|
| `title` | `text` | no | | Título de la sección |
| `description` | `textarea` | no | | Texto introductorio |
| `libros` | `multi-select` | no | | Libros destacados a mostrar |
| `articulos` | `multi-select` | no | | Artículos destacados a mostrar |
| `max_items` | `number` | no | `6` | Máximo de items a mostrar |
| `show_type_label` | `toggle` | no | `true` | Mostrar etiqueta "Libro" / "Artículo" |

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
- La vista renderiza cards con portada/título/fecha para cada recurso.
