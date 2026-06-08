# T21 — InterviewsHighlightBlock

- **Estado**: ⬜ pendiente
- **Depende de**: T5 (Entrevista)
- **Categoría**: Listados

---

## Schema

| Campo | Tipo | Requerido | Default | Notas |
|-------|------|-----------|---------|-------|
| title | text | no | | Título de la sección |
| description | textarea/rich | no | | Texto introductorio |
| entrevistas | multi-select | no | | Entrevistas seleccionadas |
| max_items | number | no | 6 | Máximo de items a mostrar |

---

## Checklist

- [ ] Draft → `docs/cms/blocks/draft-interviews-highlight.md`
- [ ] Clase `InterviewsHighlightBlock.php`
- [ ] Vista `InterviewsHighlightBlock.blade.php` funcional
- [ ] Registrar en `DefaultTemplate.php`
- [ ] `php -l` + `view:clear`
- [ ] Probar en admin

---

## Notas

- El multi-select consulta `Entrevista::where('destacado', true)`.
- La vista muestra medio, fecha y descripción breve.
