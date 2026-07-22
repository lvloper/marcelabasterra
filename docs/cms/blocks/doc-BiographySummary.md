# BiographySummary — Props del bloque

## Schema

| Prop | Tipo | Requerido | Default | Descripción |
|------|------|-----------|---------|-------------|
| `title` | `text` | no | | Título (ej: "Sobre mí") |
| `heading_level` | `select` | sí | `h2` | `h1` para apertura principal de página; `h2` para sección |
| `summary` | `rich` (basic) | sí | | Resumen biográfico |
| `photo` | `image` | no | | Foto de perfil (400x400) |
| `cta_label` | `text` | no | | Texto del CTA |
| `cta_route` | `route` | no | | Ruta del CTA |

## Contrato de datos

```json
{
  "type": "BiographySummary",
  "data": {
    "title": "Sobre mí",
    "summary": "<p>Soy abogada...</p>",
    "photo": "images/biography/foto.jpg",
    "cta_label": "Ver trayectoria completa",
    "cta_route": { "route_id": "5", "btn_label": "Ver más" }
  }
}
```

## Reglas de renderizado

- Layout: foto a la izquierda, texto a la derecha (mobile: stacked).
- Se usa en Home (resumen breve) y en Sobre mí (versión completa).
