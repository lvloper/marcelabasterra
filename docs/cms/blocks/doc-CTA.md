# CTA — Props del bloque

## Schema

| Prop | Tipo | Requerido | Default | Descripción |
|------|------|-----------|---------|-------------|
| `title` | `text` | no | | Título del CTA |
| `text` | `textarea` | no | | Texto descriptivo |
| `button_label` | `text` | sí | | Texto del botón |
| `button_route` | `route` | sí | | Ruta del botón |

## Contrato de datos

```json
{
  "type": "CTA",
  "data": {
    "title": "¿Querés saber más?",
    "text": "Contactame para más información",
    "button_label": "Contactar",
    "button_route": { "route_id": "3", "btn_label": "Contactar" }
  }
}
```

## Reglas de renderizado

- El botón es siempre requerido.
- Bloque reutilizable, puede aparecer múltiples veces en una misma página.
