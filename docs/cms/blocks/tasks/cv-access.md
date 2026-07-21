# Tarea individual — CVAccess

## Prompt para una tarea nueva

```text
Trabajá únicamente en la base funcional del bloque CVAccess del CMS.

Leé AGENTS.md, la skill create-block, docs/cms/blocks/subagents/block-backend.md y docs/cms/blocks/draft-cv-access.md. Inspeccioná CVDownload, los componentes de archivo/ruta del CMS, PageBlock y DefaultTemplate.

El draft está aprobado. Generá CVAccessBlock.php con exactamente dos documentos, completo y reducido, validación PDF, fecha de actualización y labels para ver/descargar. Evitá combinaciones duplicadas del tipo de documento y usá componentes existentes del proyecto.

Registralo en DefaultTemplate.php y creá resources/views/blocks/CVAccess.blade.php únicamente con <x-block> y @dump(get_defined_vars()). Generá docs/cms/blocks/doc-CVAccess.md con props, contrato de datos, validaciones y comportamiento de apertura/descarga.

No diseñes el frontend, no elimines CVDownload y no modifiques páginas, menú o archivos editoriales. La falta actual de los PDFs no debe impedir crear el schema, pero debe quedar documentada como bloqueo de publicación.

Verificá php -l, php artisan view:clear, tests relevantes, funcionamiento del formulario Filament y cms:blocks-list. Informá archivos modificados y qué PDFs faltan cargar.
```

## Prompt posterior de diseño

```text
Diseñá únicamente la vista del bloque CVAccess ya implementado. Leé AGENTS.md, DESIGN.md, docs/cms/blocks/subagents/design-prompt.md y docs/cms/blocks/doc-CVAccess.md. Confirmá primero que ambos PDFs reales están cargados y validados. No cambies backend ni props. Reemplazá sólo resources/views/blocks/CVAccess.blade.php, contemplando ver, descargar, fecha y estados de archivo. Validá responsive, accesibilidad y php artisan view:clear. Si corresponde, informá modelo_usado y motivo_fallback.
```

