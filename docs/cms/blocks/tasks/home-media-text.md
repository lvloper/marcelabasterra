# Home — Reconocimiento destacado con MediaText

## Archivos de entrada

- `DESIGN.md`
- `docs/cms/blocks/doc-MediaText.md`
- `app/Filament/Blocks/MediaTextBlock.php`
- `resources/views/blocks/MediaText.blade.php`

## Prompt para una tarea nueva

```text
Rediseñá únicamente el bloque existente MediaText para que pueda presentar en la Home el reconocimiento “Personalidad Destacada de la Cultura en el Ámbito de las Ciencias Jurídicas”.

Leé AGENTS.md, DESIGN.md, docs/cms/blocks/subagents/design-prompt.md, docs/cms/blocks/doc-MediaText.md, MediaTextBlock.php y la vista actual. Inspeccioná sólo uno o dos bloques del proyecto como referencia técnica.

Conservá exactamente el backend, nombre y props existentes. El bloque debe resolver imagen o video de YouTube, título, contenido enriquecido y CTA. No hardcodees el nombre del reconocimiento, el video ni textos: todo debe provenir de los props.

Reemplazá únicamente resources/views/blocks/MediaText.blade.php con una composición editorial de dos áreas claramente separadas. En mobile, el medio debe aparecer antes del texto. El video debe ser responsive, tener título accesible y no reproducirse automáticamente. La imagen debe usar alt cuando el componente actual lo permita; si el contrato no lo soporta, documentá la limitación sin ampliar el backend en esta tarea.

Respetá DESIGN.md: sin overlays, texto sobre fotografía, sombras, radios o negro estructural. Validá ambos layouts, ambos tipos de medio, ausencia de CTA, contenido largo, mobile, teclado y reduced motion.

No cambies modelos, schema, páginas, menú ni datos. Ejecutá php artisan view:clear y pruebas relevantes. Informá modelo_usado y, si aplica, motivo_fallback.
```

