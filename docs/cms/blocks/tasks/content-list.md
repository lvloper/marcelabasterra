# Tarea individual — ContentList

## Prompt para una tarea nueva

```text
Trabajá únicamente en la base funcional del bloque ContentList del CMS.

Leé AGENTS.md, la skill create-block, docs/cms/blocks/subagents/block-backend.md y docs/cms/blocks/draft-content-list.md. Inspeccioná PageBlock, DefaultTemplate y uno o dos bloques de listados como referencia técnica.

El draft está aprobado. Antes del bloque, ampliá CargoInstitucional con los campos institutional_url y featured definidos por el draft. Creá una migración reversible y actualizá modelo, formulario y tabla del recurso CMS sin perder datos actuales.

Después generá ContentListBlock.php, registralo en DefaultTemplate.php y creá resources/views/blocks/ContentList.blade.php únicamente con <x-block> y @dump(get_defined_vars()). Generá docs/cms/blocks/doc-ContentList.md con el contrato real de props y reglas de renderizado.

No maquetes ni diseñes el frontend. No modifiques páginas, menú ni contenido editorial. No agregues campos fuera del draft salvo metadatos técnicos imprescindibles, que deberás justificar.

Verificá php -l, migraciones, php artisan view:clear, tests relevantes y que php artisan cms:blocks-list muestre el bloque como REGISTRADO. Informá archivos modificados, verificaciones y cualquier dato que deba cargarse antes del diseño.
```

## Prompt posterior de diseño

```text
Diseñá únicamente la vista del bloque ContentList ya implementado. Leé AGENTS.md, DESIGN.md, docs/cms/blocks/subagents/design-prompt.md y docs/cms/blocks/doc-ContentList.md. Usá datos reales ya cargados, no cambies backend ni props, y reemplazá sólo resources/views/blocks/ContentList.blade.php. Validá responsive, accesibilidad, estados vacíos y php artisan view:clear. Si el modelo de diseño recomendado no está disponible, informá modelo_usado y motivo_fallback.
```
