# RelatedResources — Props del bloque

## Schema

| Prop | Tipo | Requerido | Default | Descripción |
|------|------|-----------|---------|-------------|
| `title` | `text` | no | | Título de la sección |
| `resource_types` | `multi-select` | no | `[libro, articulo, entrevista]` | Tipos a incluir |
| `tags` | `tags` | no | | Filtrar por tags/temática |
| `max_items` | `number` | no | `4` | Máximo de items |

## Contrato de datos

```json
{
  "type": "RelatedResources",
  "data": {
    "title": "También te puede interesar",
    "resource_types": ["libro", "articulo"],
    "tags": ["derecho", "familia"],
    "max_items": 4
  }
}
```

## Reglas de renderizado

- Útil en páginas de detalle para mostrar contenido relacionado.
- Si no se especifican tags, muestra los últimos N destacados de los tipos seleccionados.
