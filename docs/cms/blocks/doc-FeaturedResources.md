# FeaturedResources — Props del bloque

## Schema

| Prop | Tipo | Requerido | Default | Descripción |
|------|------|-----------|---------|-------------|
| `title` | `text` | no | | Título de la sección |
| `description` | `textarea` | no | | Texto introductorio |
| `source_mode` | `select` | sí | `manual` | `manual` o `latest` |
| `max_items` | `number` | no | `4` | Entre 1 y 4 recursos |
| `items` | `repeater` | no | | Recursos destacados manuales |
| `→ resource_type` | `select` | sí | | Libro / Artículo / Entrevista |
| `→ resource_id` | `select` (dinámico) | sí | | ID del recurso según tipo |

## Contrato de datos

```json
{
  "type": "FeaturedResources",
  "data": {
    "title": "Publicaciones destacadas",
    "items": [
      { "resource_type": "libro", "resource_id": 1 },
      { "resource_type": "entrevista", "resource_id": 3 }
    ]
  }
}
```

## Reglas de renderizado

- `resource_id` se filtra dinámicamente según `resource_type` seleccionado.
- La vista consulta el modelo correspondiente y muestra thumbnail + título + link.
- `latest` consulta el catálogo académico normalizado y se mantiene actualizado automáticamente.
