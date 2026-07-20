# T17 — BiographySummaryBlock

- **Estado**: ⬜ pendiente
- **Depende de**: Ninguna
- **Categoría**: Hero

---

## Schema

| Campo | Tipo | Requerido | Default | Notas |
|-------|------|-----------|---------|-------|
| title | text | no | | Título (ej: "Sobre mí") |
| summary | rich | sí | | Resumen biográfico |
| photo | image | no | | Foto de perfil |
| cta_label | text | no | | Texto del CTA |
| cta_route | route | no | | Ruta del CTA |

---

## Checklist

- [ ] Draft → `docs/cms/blocks/draft-biography-summary.md`
- [ ] Clase `BiographySummaryBlock.php`
- [ ] Vista `BiographySummaryBlock.blade.php` funcional
- [ ] Registrar en `DefaultTemplate.php`
- [ ] `php -l` + `view:clear`
- [ ] Probar en admin

---

## Notas

- Se usa en Home (resumen breve) y en Sobre mí (versión completa).
- Layout sugerido: foto a la izquierda, texto a la derecha (mobile: stacked).
