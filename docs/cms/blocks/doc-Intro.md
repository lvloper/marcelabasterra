# Intro — Props del bloque

> Bloque genérico de introducción de sección: título + resumen, con foto, enlace y datos destacados opcionales. No está atado a un uso específico (puede ser presentación, bio breve, intro de sección, etc.).

## Schema

| Prop | Tipo | Requerido | Default | Descripción |
|------|------|-----------|---------|-------------|
| `tag` | `text` | no | | Etiqueta editorial sobre el título (ej: "Trayectoria") |
| `title` | `text` | no | | Título de la sección |
| `heading_level` | `select` | sí | `h2` | `h1` para apertura principal de página; `h2` para sección |
| `summary` | `rich` (basic) | sí | | Resumen / texto introductorio |
| `photo` | `image` | no | | Foto opcional (400x400) |
| `cta_label` | `text` | no | | Texto del enlace |
| `cta_route` | `route` | no | | Ruta del enlace |
| `highlights` | `repeater` | no | `[]` | Datos destacados opcionales |

### Campos del repeater `highlights`

| Campo | Tipo | Descripción |
|-------|------|-------------|
| `number` | `text` | Cifra destacada (ej: "25+", "12", "40") |
| `label` | `text` | Descripción del dato (ej: "años de docencia", "libros publicados") |

## Contrato de datos

```json
{
  "type": "Intro",
  "data": {
    "tag": "Trayectoria",
    "title": "Sobre mí",
    "summary": "<p>Texto introductorio...</p>",
    "photo": "images/biography/foto.jpg",
    "cta_label": "Ver trayectoria completa",
    "cta_route": { "route_id": "5", "anchor": "" },
    "highlights": [
      { "number": "25+", "label": "años de docencia" },
      { "number": "12", "label": "libros publicados" }
    ]
  }
}
```

## Reglas de renderizado

- Composición minimalista: el Hero u otro bloque de apertura lleva el protagonismo visual de la página.
- Desktop: título en columna izquierda (4 cols) y resumen a la derecha (7 cols); CTA como enlace editorial subrayado debajo del título (oculto en mobile, donde va tras el resumen).
- Mobile: título → resumen → enlace, apilados en orden de lectura.
- Bordes superior e inferior de 1 px (`gray-2`), superficie blanca, sin fondo azul.
- Sin etiqueta `tag` renderizada en la vista actual (el tag se mantiene en el schema como prop opcional).
- Foto opcional: no se renderiza en este diseño (el Hero ya aporta la imagen).
- Highlights: prop disponible en schema, no renderizada en el diseño actual.
- Se usa en Home (resumen breve) y en Sobre mí (versión completa).
