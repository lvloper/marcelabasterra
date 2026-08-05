# BiographySummary — Props del bloque

## Schema

| Prop | Tipo | Requerido | Default | Descripción |
|------|------|-----------|---------|-------------|
| `tag` | `text` | no | | Etiqueta editorial sobre el título (ej: "Trayectoria") |
| `title` | `text` | no | | Título (ej: "Sobre mí") |
| `heading_level` | `select` | sí | `h2` | `h1` para apertura principal de página; `h2` para sección |
| `summary` | `rich` (basic) | sí | | Resumen biográfico |
| `photo` | `image` | no | | Foto de perfil (400x400) |
| `cta_label` | `text` | no | | Texto del CTA |
| `cta_route` | `route` | no | | Ruta del CTA |
| `highlights` | `repeater` | no | `[]` | Datos destacados de trayectoria |

### Campos del repeater `highlights`

| Campo | Tipo | Descripción |
|-------|------|-------------|
| `number` | `text` | Cifra destacada (ej: "25+", "12", "40") |
| `label` | `text` | Descripción del dato (ej: "años de docencia", "libros publicados") |

## Contrato de datos

```json
{
  "type": "BiographySummary",
  "data": {
    "tag": "Trayectoria",
    "title": "Sobre mí",
    "summary": "<p>Soy abogada...</p>",
    "photo": "images/biography/foto.jpg",
    "cta_label": "Ver trayectoria completa",
    "cta_route": { "route_id": "5", "anchor": "" },
    "highlights": [
      { "number": "25+", "label": "años de docencia" },
      { "number": "12", "label": "libros publicados" },
      { "number": "40", "label": "conferencias internacionales" },
      { "number": "15", "label": "cargos institucionales" }
    ]
  }
}
```

## Reglas de renderizado

- Desktop: foto a la izquierda (5 cols), texto a la derecha (7 cols), highlights abajo a ancho completo.
- Mobile: foto → contenido principal → highlights, apilados en orden de lectura.
- Fondo azul institucional con patrón de grilla decorativo sutil.
- Etiqueta editorial opcional sobre el título, en Source Serif 4.
- Highlights se muestran en grilla de 2 columnas (mobile) y 4 columnas (desktop) con separadores verticales.
- Se usa en Home (resumen breve) y en Sobre mí (versión completa).
