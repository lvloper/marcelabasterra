# Prompts de bloques para la Home

La Home conserva el diseño actual de `Hero`. Los demás bloques se trabajan uno por uno en tareas independientes.

## Orden recomendado

| Orden | Sección | Tipo de tarea | Prompt |
|---|---|---|---|
| 1 | Cargos actuales | Listado textual reutilizable | [ContentList](content-list.md) |
| 2 | Publicación más reciente | Rediseñar bloque existente | [FeaturedResources para Home](home-featured-resources.md) |
| 3 | Reconocimiento destacado | Rediseñar bloque existente | [MediaText para Home](home-media-text.md) |
| 4 | Actualidad y prensa | Crear backend base; diseñar después | [PressFeed](press-feed.md) |
| 5 | Próximas actividades | Crear backend base; diseñar después | [EventsListing](events-listing.md) |
| 6 | Accesos al CV | Crear backend base; diseñar después | [CVAccess](cv-access.md) |
| 7 | Contacto | Rediseñar bloque existente | [CTA para Home](home-cta.md) |

## Archivos de schema y props

### Bloques nuevos

- `docs/cms/blocks/draft-content-list.md`
- `docs/cms/blocks/draft-press-feed.md`
- `docs/cms/blocks/draft-events-listing.md`
- `docs/cms/blocks/draft-cv-access.md`

Primero se ejecuta el prompt de backend de su tarea. Esa ejecución crea el correspondiente `doc-{Name}.md`. Después se carga y valida contenido real; recién entonces se usa el prompt de diseño incluido en el mismo archivo.

### Bloques existentes

- `docs/cms/blocks/doc-FeaturedResources.md`
- `docs/cms/blocks/doc-MediaText.md`
- `docs/cms/blocks/doc-CTA.md`

Estos ya tienen backend y contrato de props, por lo que sus prompts trabajan únicamente sobre la vista Blade.

## Hero

No hay tarea de rediseño para `Hero`: se conserva su diseño actual. Al componer la Home sólo se deberán actualizar sus datos y enlaces conforme al mapa objetivo.
