# T15 — TimelineBlock

- **Estado**: ⬜ pendiente
- **Depende de**: Ninguna
- **Categoría**: Contenido

---

## Schema

| Campo | Tipo | Requerido | Default | Notas |
|-------|------|-----------|---------|-------|
| title | text | no | | Título de la sección timeline |
| items | repeater | no | | Hitos de la línea de tiempo |
| → year | text | sí | | Año del hito |
| → title | text | sí | | Título del hito |
| → description | textarea/rich | no | | Descripción del hito |

---

## Checklist

- [ ] Draft → `docs/cms/blocks/draft-timeline.md`
- [ ] Clase `TimelineBlock.php`
- [ ] Vista `TimelineBlock.blade.php` funcional
- [ ] Registrar en `DefaultTemplate.php`
- [ ] `php -l` + `view:clear`
- [ ] Probar en admin

---

## Notas

- Orden default: por año ascendente (cronológico).
- Diseño mobile-first: columna vertical con línea conectora.
