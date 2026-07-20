# Hero — Props del bloque

## Schema

| Prop | Tipo | Requerido | Default | Descripción |
|------|------|-----------|---------|-------------|
| `title` | `text` | sí | | Título principal |
| `subtitle` | `textarea` | no | | Subtítulo |
| `image` | `image` | no | | Imagen de fondo (1920x1080) |
| `cta_label` | `text` | no | | Texto del botón CTA |
| `cta_route` | `route` | no | | Ruta del botón CTA |

## Contrato de datos

```json
{
  "type": "Hero",
  "data": {
    "title": "Marcela Basterra",
    "subtitle": "Abogada especialista en...",
    "image": "images/hero/fondo.jpg",
    "cta_label": "Conocer más",
    "cta_route": { "route_id": "1", "btn_label": "Ver más" }
  }
}
```

## Reglas de renderizado

- Si no hay imagen, el hero se renderiza solo con texto sobre fondo de color.
- El CTA es opcional; si no se define cta_label ni cta_route, no se muestra botón.
