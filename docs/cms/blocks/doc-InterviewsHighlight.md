# InterviewsHighlight — Props del bloque

## Schema

| Prop | Tipo | Requerido | Default | Descripción |
|------|------|-----------|---------|-------------|
| `title` | `text` | no | | Título de la sección |
| `description` | `textarea` | no | | Texto introductorio |
| `entrevistas` | `multi-select` | no | | Entrevistas seleccionadas |
| `max_items` | `number` | no | `6` | Máximo de items a mostrar |

## Contrato de datos

```json
{
  "type": "InterviewsHighlight",
  "data": {
    "title": "Entrevistas",
    "entrevistas": [1, 2, 5],
    "max_items": 6
  }
}
```

## Reglas de renderizado

- El multi-select consulta el modelo Entrevista.
- La vista muestra medio, fecha y descripción breve.
