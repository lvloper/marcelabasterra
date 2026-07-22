# EventsHighlight — Props del bloque

## Schema

| Prop | Tipo | Requerido | Default | Descripción |
|------|------|-----------|---------|-------------|
| `title` | `text` | no | | Título de la sección |
| `description` | `textarea` | no | | Texto introductorio |
| `source_mode` | `select` | sí | `automatic` | Automático o selección editorial |
| `eventos` | `multi-select` | no | | Eventos seleccionados |
| `conferencias` | `multi-select` | no | | Conferencias seleccionadas |
| `include_conferences` | `toggle` | no | `true` | Unifica conferencias y eventos |
| `max_items` | `number` | no | `1` | Máximo de items a mostrar (hasta 3) |
| `show_past` | `toggle` | no | `false` | Mostrar eventos pasados |

## Contrato de datos

```json
{
  "type": "EventsHighlight",
  "data": {
    "title": "Próximos eventos",
    "source_mode": "automatic",
    "eventos": [],
    "conferencias": [],
    "include_conferences": true,
    "max_items": 1,
    "show_past": false
  }
}
```

## Reglas de renderizado

- En modo automático selecciona el próximo registro con fecha; si no existe, muestra el realizado más reciente.
- Consulta el catálogo backend unificado de `Evento` y `Conferencia`.
- La vista muestra imagen, fecha, nombre, institución, ciudad/país, tema, descripción y acceso al detalle.
