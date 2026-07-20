# T20 — PublicationsHighlightBlock

- **Estado**: ⬜ pendiente
- **Depende de**: T3 (Libro), T4 (ArtículoAcadémico)
- **Categoría**: Listados

---

## Schema

| Campo | Tipo | Requerido | Default | Notas |
|-------|------|-----------|---------|-------|
| title | text | no | | Título de la sección |
| description | textarea/rich | no | | Texto introductorio |
| libros | multi-select | no | | Libros destacados a mostrar |
| articulos | multi-select | no | | Artículos destacados a mostrar |
| max_items | number | no | 6 | Máximo de items a mostrar |
| show_type_label | toggle | no | true | Mostrar etiqueta "Libro" / "Artículo" |

---

## Checklist

- [ ] Draft → `docs/cms/blocks/draft-publications-highlight.md`
- [ ] Clase `PublicationsHighlightBlock.php`
- [ ] Vista `PublicationsHighlightBlock.blade.php` funcional
- [ ] Registrar en `DefaultTemplate.php`
- [ ] `php -l` + `view:clear`
- [ ] Probar en admin (requiere Libro y ArtículoAcademico con data)

---

## Notas

- Los multi-select consultan `Libro::where('destacado', true)` y `ArticuloAcademico::where('destacado', true)`.
- Si no se seleccionan manualmente, se pueden tomar los últimos N destacados automáticamente.
- La vista renderiza cards con portada/título/fecha para cada recurso.
