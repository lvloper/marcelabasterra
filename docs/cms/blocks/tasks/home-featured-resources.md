# Home — Publicación más reciente con FeaturedResources

## Archivos de entrada

- `DESIGN.md`
- `docs/cms/blocks/doc-FeaturedResources.md`
- `app/Filament/Blocks/FeaturedResourcesBlock.php`
- `resources/views/blocks/FeaturedResources.blade.php`

## Prompt para una tarea nueva

```text
Rediseñá únicamente el bloque existente FeaturedResources para usarlo en la Home como “Publicación más reciente”.

Leé AGENTS.md, DESIGN.md, docs/cms/blocks/subagents/design-prompt.md, docs/cms/blocks/doc-FeaturedResources.md, FeaturedResourcesBlock.php y la vista actual. Inspeccioná sólo uno o dos bloques ya adaptados como referencia de convenciones Blade y Tailwind.

Conservá exactamente el backend, el nombre y los props actuales. La instancia de Home seleccionará un único recurso de tipo libro: “Teoría general de los Derechos Económicos, Sociales, Culturales y Ambientales”, publicado en 2025. La vista también debe seguir funcionando si otra página selecciona varios recursos.

Reemplazá únicamente resources/views/blocks/FeaturedResources.blade.php. Diseñá una presentación editorial contemporánea donde el primer recurso tenga jerarquía suficiente para funcionar como publicación destacada, usando portada, título, editorial/año disponibles y enlace a la ficha. No hardcodees el libro ni ningún contenido editorial.

Respetá DESIGN.md: sin sombras, radios, tarjetas flotantes, negro estructural, texto sobre imágenes ni identidad visual genérica. Implementá mobile-first, foco visible, semántica correcta y reduced motion si agregás animación.

No cambies modelos, recursos, schema, páginas, menú ni datos. Ejecutá php artisan view:clear y pruebas relevantes. Informá modelo_usado y, si no fue el recomendado por AGENTS.md, motivo_fallback.
```

