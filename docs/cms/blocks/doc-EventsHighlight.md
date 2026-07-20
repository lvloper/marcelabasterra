# EventsHighlight — Props del bloque

## Schema

| Prop | Tipo | Requerido | Default | Descripción |
|------|------|-----------|---------|-------------|
| `title` | `text` | no | | Título de la sección |
| `description` | `textarea` | no | | Texto introductorio |
| `eventos` | `multi-select` | no | | Eventos seleccionados |
| `max_items` | `number` | no | `6` | Máximo de items a mostrar |
| `show_past` | `toggle` | no | `false` | Mostrar eventos pasados |

## Contrato de datos

```json
{
  "type": "EventsHighlight",
  "data": {
    "title": "Próximos eventos",
    "eventos": [1, 3],
    "max_items": 6,
    "show_past": false
  }
}
```

## Reglas de renderizado

- Por defecto filtra eventos con fecha_inicio >= now().
- La vista muestra fecha, ubicación y título.
