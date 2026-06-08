# T18 — ContactFormBlock

- **Estado**: ⬜ pendiente
- **Depende de**: Ninguna
- **Categoría**: Interacción

---

## Schema

| Campo | Tipo | Requerido | Default | Notas |
|-------|------|-----------|---------|-------|
| title | text | no | | Título del formulario |
| description | textarea/rich | no | | Texto introductorio |
| recipient_email | text | sí | | Email donde llegan los mensajes |
| success_message | text | no | "Mensaje enviado" | Mensaje de confirmación |
| show_phone | toggle | no | false | Incluir campo teléfono |
| show_subject | toggle | no | false | Incluir campo asunto |

---

## Checklist

- [ ] Draft → `docs/cms/blocks/draft-contact-form.md`
- [ ] Clase `ContactFormBlock.php`
- [ ] Vista `ContactFormBlock.blade.php` funcional
- [ ] Crear ruta POST para envío del formulario
- [ ] Crear mail y controller para el envío
- [ ] Registrar en `DefaultTemplate.php`
- [ ] `php -l` + `view:clear`
- [ ] Probar envío de formulario
