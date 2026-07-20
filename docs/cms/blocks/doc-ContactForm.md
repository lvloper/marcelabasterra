# ContactForm — Props del bloque

## Schema

| Prop | Tipo | Requerido | Default | Descripción |
|------|------|-----------|---------|-------------|
| `title` | `text` | no | | Título del formulario |
| `description` | `textarea` | no | | Texto introductorio |
| `recipient_email` | `text` (email) | sí | | Email donde llegan los mensajes |
| `success_message` | `text` | no | `"Mensaje enviado correctamente"` | Mensaje de confirmación |
| `show_phone` | `toggle` | no | `false` | Incluir campo teléfono |
| `show_subject` | `toggle` | no | `false` | Incluir campo asunto |

## Contrato de datos

```json
{
  "type": "ContactForm",
  "data": {
    "title": "Contacto",
    "description": "Escribime y te responderé a la brevedad",
    "recipient_email": "marcela@basterra.com",
    "success_message": "Gracias por tu mensaje",
    "show_phone": true,
    "show_subject": false
  }
}
```

## Reglas de renderizado

- El formulario envía por POST a una ruta que procesa y envía email.
- Los campos visibles dependen de los toggles show_phone y show_subject.
- Pendiente: crear ruta POST, controller y mailable para el envío.
