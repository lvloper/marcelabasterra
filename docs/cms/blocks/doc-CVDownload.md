# CVDownload — Props del bloque

## Schema

| Prop | Tipo | Requerido | Default | Descripción |
|------|------|-----------|---------|-------------|
| `title` | `text` | no | | Título (ej: "Descargar CV") |
| `description` | `textarea` | no | | Texto de apoyo |
| `button_text` | `text` | no | `"Descargar"` | Texto del botón |

## Contrato de datos

```json
{
  "type": "CVDownload",
  "data": {
    "title": "Descargar CV",
    "description": "Conocé mi trayectoria profesional completa",
    "button_text": "Descargar PDF"
  }
}
```

## Reglas de renderizado

- El archivo PDF se sube mediante el route picker (opción "Subir archivo").
