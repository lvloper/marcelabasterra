# Tarea individual — PressFeed

## Prompt para una tarea nueva

```text
Trabajá únicamente en la base funcional del bloque PressFeed y su fuente de datos.

Leé AGENTS.md, la skill create-cms-resource, la skill create-block, docs/cms/blocks/subagents/block-backend.md y docs/cms/blocks/draft-press-feed.md. Inspeccioná Entrevista, Blog, sus recursos/migraciones, los patrones ResourceBase y los bloques InterviewsHighlight/FeaturedResources. No uses Blog como sustituto del nuevo dominio de prensa.

El draft está aprobado. Diseñá e implementá primero el recurso ruteable PublicacionMedio con tipo articulo/entrevista/noticia, medio, fecha, imagen opcional, resumen, enlace externo opcional, autoría/coautoría opcional, tema y destacado. Definí una estrategia explícita para los registros actuales de Entrevista: compatibilidad temporal o migración segura, sin borrar datos.

Después generá PressFeedBlock.php, registralo en DefaultTemplate.php y creá resources/views/blocks/PressFeed.blade.php únicamente con <x-block> y @dump(get_defined_vars()). Generá docs/cms/blocks/doc-PressFeed.md con contrato, filtros, orden y estrategia de datos.

No diseñes el frontend, no migres contenido demo de Blog y no modifiques páginas o menú. Si la decisión Entrevista versus PublicacionMedio afecta datos existentes, elegí la opción reversible más simple y documentala.

Verificá sintaxis, migraciones, recursos Filament, php artisan view:clear, tests relevantes y cms:blocks-list. Informá la estrategia adoptada y cualquier migración editorial pendiente.
```

## Prompt posterior de diseño

```text
Diseñá únicamente la vista del bloque PressFeed ya implementado. Leé AGENTS.md, DESIGN.md, docs/cms/blocks/subagents/design-prompt.md y docs/cms/blocks/doc-PressFeed.md. No cambies backend ni props. Reemplazá sólo resources/views/blocks/PressFeed.blade.php usando datos reales, diferenciando artículo, entrevista y noticia, con filtros accesibles cuando estén activos. Validá responsive, estados vacíos y php artisan view:clear. Si corresponde, informá modelo_usado y motivo_fallback.
```

