# T19 — FeaturedResourcesBlock

- **Estado**: ⬜ pendiente
- **Depende de**: Ninguna (usa select manual de resources, no query automática)
- **Categoría**: Listados

---

## Schema

| Campo | Tipo | Requerido | Default | Notas |
|-------|------|-----------|---------|-------|
| title | text | no | | Título de la sección |
| description | textarea/rich | no | | Texto introductorio |
| items | repeater | no | | Recursos destacados manuales |
| → resource_type | select | sí | | Libro / Artículo / Entrevista |
| → resource_id | select (dinámico) | sí | | ID del recurso según tipo |

---

## Checklist

- [ ] Draft → `docs/cms/blocks/draft-featured-resources.md`
- [ ] Clase `FeaturedResourcesBlock.php`
- [ ] Vista `FeaturedResourcesBlock.blade.php` funcional
- [ ] Registrar en `DefaultTemplate.php`
- [ ] `php -l` + `view:clear`
- [ ] Probar en admin

---

## Notas

- El select de `resource_id` debe filtrarse según el `resource_type` elegido.
- La vista consulta el modelo correspondiente y muestra thumbnail + título + link.
