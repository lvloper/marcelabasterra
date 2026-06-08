# T22 — EventsHighlightBlock

- **Estado**: ⬜ pendiente
- **Depende de**: T6 (Evento)
- **Categoría**: Listados

---

## Schema

| Campo | Tipo | Requerido | Default | Notas |
|-------|------|-----------|---------|-------|
| title | text | no | | Título de la sección |
| description | textarea/rich | no | | Texto introductorio |
| eventos | multi-select | no | | Eventos seleccionados |
| max_items | number | no | 6 | Máximo de items a mostrar |
| show_past | toggle | no | false | Mostrar eventos pasados (default: solo próximos) |

---

## Checklist

- [ ] Draft → `docs/cms/blocks/draft-events-highlight.md`
- [ ] Clase `EventsHighlightBlock.php`
- [ ] Vista `EventsHighlightBlock.blade.php` funcional
- [ ] Registrar en `DefaultTemplate.php`
- [ ] `php -l` + `view:clear`
- [ ] Probar en admin

---

## Notas

- Por defecto filtra eventos con `fecha_inicio >= now()`.
- La vista muestra fecha, ubicación y título.
