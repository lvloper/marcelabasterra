# T23 — RelatedResourcesBlock

- **Estado**: ⬜ pendiente
- **Depende de**: T3 (Libro), T4 (ArtículoAcadémico), T5 (Entrevista)
- **Categoría**: Listados

---

## Schema

| Campo | Tipo | Requerido | Default | Notas |
|-------|------|-----------|---------|-------|
| title | text | no | | Título de la sección |
| resource_types | multi-select | no | | Tipos a incluir (Libro/Artículo/Entrevista) |
| tags | tags input | no | | Filtrar por tags/temática |
| max_items | number | no | 4 | Máximo de items |

---

## Checklist

- [ ] Draft → `docs/cms/blocks/draft-related-resources.md`
- [ ] Clase `RelatedResourcesBlock.php`
- [ ] Vista `RelatedResourcesBlock.blade.php` funcional
- [ ] Registrar en `DefaultTemplate.php`
- [ ] `php -l` + `view:clear`
- [ ] Probar en admin

---

## Notas

- Útil en páginas de detalle de Libro/Artículo para mostrar contenido relacionado.
- Puede filtrar por tags o por temática compartida.
- Si no se especifican tags, muestra los últimos N destacados de los tipos seleccionados.
