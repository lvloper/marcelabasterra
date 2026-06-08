# T16 — CVDownloadBlock

- **Estado**: ⬜ pendiente
- **Depende de**: Ninguna
- **Categoría**: Interacción

---

## Schema

| Campo | Tipo | Requerido | Default | Notas |
|-------|------|-----------|---------|-------|
| title | text | no | | Título (ej: "Descargar CV") |
| description | textarea/rich | no | | Texto de apoyo |
| file | file | sí | | Archivo PDF del CV |
| button_text | text | no | "Descargar" | Texto del botón |
| button_style | select | no | | Variante visual (primario, secundario, outline) |

---

## Checklist

- [ ] Draft → `docs/cms/blocks/draft-cv-download.md`
- [ ] Clase `CVDownloadBlock.php`
- [ ] Vista `CVDownloadBlock.blade.php` funcional
- [ ] Registrar en `DefaultTemplate.php`
- [ ] `php -l` + `view:clear`
- [ ] Probar en admin
