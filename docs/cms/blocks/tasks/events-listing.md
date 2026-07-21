# Tarea individual — EventsListing

## Prompt para una tarea nueva

```text
Trabajá únicamente en la base funcional del bloque EventsListing del CMS.

Leé AGENTS.md, la skill create-block, docs/cms/blocks/subagents/block-backend.md y docs/cms/blocks/draft-events-listing.md. Inspeccioná sólo Evento, EventoResource, sus migraciones, EventsHighlight como referencia, PageBlock y DefaultTemplate.

El draft está aprobado. Primero ampliá Evento para representar institución, rol, modalidad, estado de confirmación, imagen y video. Conservá los campos y registros actuales, creá una migración reversible y actualizá el recurso Filament. Definí opciones estables para los tipos de actividad indicados en el draft.

Después generá EventsListingBlock.php, registralo en DefaultTemplate.php y creá resources/views/blocks/EventsListing.blade.php únicamente con <x-block> y @dump(get_defined_vars()). Generá docs/cms/blocks/doc-EventsListing.md con props, consulta, orden y comportamiento de estados vacíos.

No reemplaces EventsHighlight. No diseñes el frontend ni modifiques páginas, menú o contenidos. Usá la zona horaria configurada por la aplicación para separar próximos y realizados.

Verificá php -l, migraciones, php artisan view:clear, tests relevantes y cms:blocks-list. Informá archivos modificados y datos requeridos para la validación posterior.
```

## Prompt posterior de diseño

```text
Diseñá únicamente la vista del bloque EventsListing ya implementado. Leé AGENTS.md, DESIGN.md, docs/cms/blocks/subagents/design-prompt.md y docs/cms/blocks/doc-EventsListing.md. No cambies backend ni props. Diseñá resources/views/blocks/EventsListing.blade.php con datos reales, contemplando próximos, realizados, fallback y vacío. Validá responsive, accesibilidad y php artisan view:clear. Si corresponde, informá modelo_usado y motivo_fallback.
```

