# Draft de bloque: PressFeed

## Meta
- **Nombre:** PressFeed
- **Categoría:** Listados
- **Label:** Prensa y actualidad

## Schema

| Campo | Tipo | Requerido | Default | Notas |
|-------|------|-----------|---------|-------|
| title | text | no | — | Título de sección. |
| description | textarea | no | — | Introducción. |
| content_types | checkbox list | sí | articulo, entrevista, noticia | Uno o más tipos. |
| media | tags/select multiple | no | — | Filtro opcional por medio. |
| selected_items | select multiple relation | no | — | Curaduría manual; vacío usa consulta automática. |
| max_items | number | sí | 6 | Entero entre 1 y 24. |
| show_filters | toggle | sí | false | Habilitar en índices, deshabilitar en Home. |
| show_image | toggle | sí | true | Renderiza imagen si el registro la posee. |
| empty_message | text | no | — | Sólo se usa en páginas índice; Home oculta el bloque vacío. |

## Comportamiento
- Combina contenidos de prensa y los ordena por fecha descendente.
- La selección manual, si existe, prevalece y conserva el orden editorial.
- Cada entrada presenta título, tipo, medio, fecha, resumen opcional, imagen opcional y enlace.
- `show_filters` expone filtros por tipo y medio mediante query string accesible.
- En Home, si no hay resultados, el bloque se oculta.

## Notas de implementación
- Depende de definir el recurso unificado `PublicacionMedio` o una estrategia polimórfica que incluya `Entrevista`.
- No usar `Blog` como sustituto automático: sus entradas actuales son demo y no expresan la taxonomía editorial requerida.
- El enlace puede ser externo o una ficha interna, según el registro.
- **Estado:** pendiente de revisión y aprobación; no implementar todavía.

