# Tarea individual — ProgramsListing

## Prompt para una tarea nueva

```text
Trabajá únicamente en la base funcional del bloque ProgramsListing del CMS.

Leé AGENTS.md, la skill create-block, docs/cms/blocks/subagents/block-backend.md y docs/cms/blocks/draft-programs-listing.md. Inspeccioná sólo ProgramaAcademico, ProgramaAcademicoResource, sus migraciones, PageBlock, DefaultTemplate y un bloque de listado como referencia.

El draft está aprobado. Primero ampliá ProgramaAcademico con materia, año académico, cuatrimestre, archivo PDF y estado vigente/histórico, manteniendo compatibilidad con descripción, institución, fechas y enlace existentes. Creá una migración reversible y actualizá modelo, formulario y tabla Filament.

Después generá ProgramsListingBlock.php, registralo en DefaultTemplate.php y creá resources/views/blocks/ProgramsListing.blade.php únicamente con <x-block> y @dump(get_defined_vars()). Generá docs/cms/blocks/doc-ProgramsListing.md con props, filtros y orden.

No diseñes el frontend ni modifiques páginas, menú o contenido. Validá el archivo como PDF y reutilizá los componentes de archivo existentes.

Verificá php -l, migraciones, php artisan view:clear, tests relevantes y cms:blocks-list. Informá archivos modificados y datos necesarios para validar programas vigentes e históricos.
```

## Prompt posterior de diseño

```text
Diseñá únicamente la vista del bloque ProgramsListing ya implementado. Leé AGENTS.md, DESIGN.md, docs/cms/blocks/subagents/design-prompt.md y docs/cms/blocks/doc-ProgramsListing.md. No cambies backend ni props. Reemplazá sólo resources/views/blocks/ProgramsListing.blade.php usando datos reales y contemplando vigentes, históricos, PDF y enlaces. Validá responsive, accesibilidad y php artisan view:clear. Si corresponde, informá modelo_usado y motivo_fallback.
```

