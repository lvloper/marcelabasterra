# ContentList — Props del bloque

## Meta

- **Nombre:** `ContentList`
- **Categoría:** `Listados`
- **Label:** `Listado de contenido`
- **Fuente de verdad:** datos del bloque en la página.
- **Ubicación actual:** Home, inmediatamente después de `Hero`.

## Schema

| Prop | Tipo | Requerido | Default | Descripción |
|---|---|---|---|---|
| `title` | `string|null` | no | `null` | Título de la sección. |
| `description` | `string|null` | no | `null` | Introducción breve. |
| `items` | `array` | sí | `[]` | Ítems editables y reordenables. |
| `items.*.meta` | `string|null` | no | `null` | Etiqueta o metadato breve. |
| `items.*.title` | `string|null` | no | `null` | Título del ítem. |
| `items.*.text` | `string|null` | no | `null` | Texto descriptivo del ítem. |
| `items.*.url` | `string|null` | no | `null` | Enlace externo opcional. |
| `items.*.link_label` | `string|null` | no | `Ver más` | Etiqueta visible del enlace. |

Los metadatos generales heredados de `PageBlock` (`blockTitle`, `blockAnchor`, espaciado, clases, estilos y visibilidad) no forman parte del contrato editorial específico.

## Contrato de datos

```json
{
  "type": "ContentList",
  "data": {
    "title": "Cargos institucionales",
    "description": null,
    "items": [
      {
        "meta": "Desde 2025",
        "title": "Presidenta",
        "text": "Asociación Argentina de Derecho Constitucional.",
        "url": "https://example.org",
        "link_label": "Visitar institución"
      }
    ]
  }
}
```

## Reglas de renderizado

1. Cada ítem es independiente y no consulta modelos del CMS.
2. El bloque se oculta cuando no hay ítems con contenido.
3. `meta`, `title`, `text` y enlace son todos opcionales; cada ítem puede usar sólo los campos que necesite.
4. El enlace externo se abre en una pestaña nueva con la protección `noopener noreferrer`.
