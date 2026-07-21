# Home — Contacto con CTA

## Archivos de entrada

- `DESIGN.md`
- `docs/cms/blocks/doc-CTA.md`
- `app/Filament/Blocks/CTABlock.php`
- `resources/views/blocks/CTA.blade.php`

## Prompt para una tarea nueva

```text
Rediseñá únicamente el bloque existente CTA para usarlo como cierre de contacto en la Home.

Leé AGENTS.md, DESIGN.md, docs/cms/blocks/subagents/design-prompt.md, docs/cms/blocks/doc-CTA.md, CTABlock.php y la vista actual. Inspeccioná sólo uno o dos bloques ya adaptados como referencia técnica.

Conservá exactamente el backend, nombre y props actuales. La instancia de Home contendrá un mensaje breve para invitaciones académicas, institucionales y periodísticas y enlazará a Contacto. No hardcodees esos textos ni la URL.

Reemplazá únicamente resources/views/blocks/CTA.blade.php. Diseñá un cierre editorial visible pero secundario respecto de la construcción de autoridad de la página. Debe admitir título opcional, texto opcional y botón requerido, y funcionar correctamente si aparece varias veces en el sitio.

Respetá DESIGN.md: superficie plana, sin sombras, radios, gradientes, negro estructural ni estética de landing comercial agresiva. Implementá jerarquía semántica, foco visible, área táctil adecuada, mobile-first y reduced motion si corresponde.

No cambies backend, props, páginas, menú ni datos. Ejecutá php artisan view:clear y pruebas relevantes. Informá modelo_usado y, si aplica, motivo_fallback.
```

