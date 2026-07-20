# T14 — CTABlock

- **Estado**: ⬜ pendiente
- **Depende de**: Ninguna
- **Categoría**: Interacción

---

## Schema

| Campo | Tipo | Requerido | Default | Notas |
|-------|------|-----------|---------|-------|
| title | text | no | | Título del CTA |
| text | textarea/rich | no | | Texto descriptivo |
| button_label | text | sí | | Texto del botón |
| button_route | route | sí | | Ruta del botón |

---

## Checklist

- [ ] Draft → `docs/cms/blocks/draft-cta.md`
- [ ] Clase `CTABlock.php`
- [ ] Vista `CTABlock.blade.php` funcional
- [ ] Registrar en `DefaultTemplate.php`
- [ ] `php -l` + `view:clear`
- [ ] Probar en admin

---

## Notas

- Bloque reutilizable para calls-to-action en cualquier página.
- Puede aparecer múltiples veces en una misma página con diferente contenido.
