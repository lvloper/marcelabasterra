# PressFeed — Props del bloque

## Schema

| Prop | Tipo | Requerido | Default | Descripción |
|------|------|-----------|---------|-------------|
| `title` | text | no | — | Título de la sección. |
| `description` | textarea | no | — | Texto introductorio. |
| `source_mode` | select | sí | `media_publications` | `media_publications` o `unified_news`. |
| `content_types` | checkbox list | sí | artículo, entrevista, noticia | Tipos visibles en la consulta automática. |
| `media` | text | no | — | Medios separados por coma; vacío incluye todos. |
| `selected_items` | select multiple | no | — | IDs de `PublicacionMedio`; conserva orden editorial cuando se implemente la vista. |
| `max_items` | number | sí | 6 | Límite entre 1 y 24. |
| `show_filters` | toggle | sí | false | Activa filtros accesibles en las páginas índice. |
| `show_image` | toggle | sí | true | Muestra imagen de ruta cuando exista. |
| `empty_message` | text | no | — | Mensaje para índices sin resultados. |

## Contrato de datos

```json
{
  "type": "PressFeed",
  "data": {
    "title": "Actualidad y prensa",
    "content_types": ["articulo", "entrevista", "noticia"],
    "media": "",
    "selected_items": [],
    "max_items": 6,
    "show_filters": false,
    "show_image": true
  }
}
```

## Reglas de renderizado

- La selección manual tiene prioridad sobre la consulta automática.
- La consulta automática usa `PublicacionMedio`, filtra por tipo y medio, y ordena por `fecha` descendente.
- `unified_news` reúne `Blog`, `PublicacionMedio` y `Entrevista` mediante el catálogo normalizado y elimina coincidencias duplicadas por título y fecha.
- La vista final debe mostrar título, tipo, medio, fecha, resumen, imagen opcional y enlace externo o ficha interna.
- En Home, la ausencia de resultados oculta el bloque. En índices, puede mostrar `empty_message`.

## Estrategia de migración

- `Entrevista` se mantiene sin cambios para no perder datos ni URLs existentes.
- Las nuevas piezas se cargan en `PublicacionMedio`.
- La migración editorial de entrevistas previas a `PublicacionMedio` queda pendiente de revisión de contenido; no se ejecutó una migración automática.
