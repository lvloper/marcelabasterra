# Timeline — Props del bloque

## Schema

| Prop | Tipo | Requerido | Default | Descripción |
|------|------|-----------|---------|-------------|
| `title` | `text` | no | | Título de la sección timeline |
| `items` | `repeater` | no | | Hitos de la línea de tiempo |
| `→ year` | `text` | sí | | Año del hito |
| `→ title` | `text` | sí | | Título del hito |
| `→ description` | `textarea` | no | | Descripción del hito |

## Contrato de datos

```json
{
  "type": "Timeline",
  "data": {
    "title": "Mi trayectoria",
    "items": [
      { "year": "2010", "title": "Título universitario", "description": "Descripción del hito" }
    ]
  }
}
```

## Reglas de renderizado

- Orden default: por año ascendente (cronológico).
- Diseño mobile-first: columna vertical con línea conectora.
