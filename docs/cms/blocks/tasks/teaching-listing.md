# Tarea individual — TeachingListing

## Prompt para una tarea nueva

```text
Trabajá únicamente en la base funcional del bloque TeachingListing del CMS.

Leé AGENTS.md, la skill create-block, docs/cms/blocks/subagents/block-backend.md y docs/cms/blocks/draft-teaching-listing.md. Inspeccioná sólo Docencia, DocenciaResource, migraciones relacionadas, PageBlock, DefaultTemplate y un bloque de listado registrado.

El draft está aprobado. Primero ampliá Docencia para representar universidad/institución, facultad, carrera o programa, materia, cargo o rol, cátedra, nivel, modalidad, período, enlace y vigencia. Conservá datos actuales y creá una migración reversible. Actualizá modelo, formulario y listado Filament.

Después generá TeachingListingBlock.php, registralo en DefaultTemplate.php y creá resources/views/blocks/TeachingListing.blade.php únicamente con <x-block> y @dump(get_defined_vars()). Generá docs/cms/blocks/doc-TeachingListing.md con props y reglas de filtrado.

No diseñes el frontend. No hardcodees la exclusión de UCA o UCES: la vigencia se controla desde los datos. No generes campos ni UI de Equipo docente. No modifiques páginas o menú.

Verificá php -l, migraciones, php artisan view:clear, tests relevantes y cms:blocks-list. Informá archivos modificados y datos pendientes para grado, posgrado y doctorado.
```

## Prompt posterior de diseño

```text
Diseñá únicamente la vista del bloque TeachingListing ya implementado. Leé AGENTS.md, DESIGN.md, docs/cms/blocks/subagents/design-prompt.md y docs/cms/blocks/doc-TeachingListing.md. No cambies backend ni props. Reemplazá sólo resources/views/blocks/TeachingListing.blade.php usando datos reales y contemplando grado, posgrado y doctorado. No muestres Equipo docente. Validá responsive, accesibilidad y php artisan view:clear. Si corresponde, informá modelo_usado y motivo_fallback.
```

