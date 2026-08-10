# Hero — Props del bloque

## Schema

| Prop | Tipo | Requerido | Default | Descripción |
|------|------|-----------|---------|-------------|
| `variant` | `toggleButtons` | sí | `editorial` | Composición visual: `editorial`, `institutional`, `portrait` o `listing` |
| `profile_photo` | `image` | no | | Retrato principal 4:5 (1200x1500) |
| `image_alt` | `text` | no | | Descripción accesible de la fotografía |
| `badge` | `text` | no | | Etiqueta editorial de contexto |
| `name` | `text` | sí | | Nombre o título principal |
| `subtitle` | `text` | sí | | Área de especialización |
| `description` | `textarea` | no | | Presentación breve, máximo 360 caracteres |
| `indicators` | `repeater` | no | `[]` | Hasta cuatro cargos, áreas o hitos; cada ítem contiene `label` |
| `featured_positions` | `multi-select` | no | `[]` | Cargos institucionales relacionados, mostrados en el orden editorial seleccionado |
| `cta_primary` | `route` | no | | Acción principal con etiqueta integrada |
| `cta_secondary` | `route` | no | | Acción secundaria con etiqueta integrada |
| `cta_tertiary` | `route` | no | | Enlace editorial terciario con etiqueta integrada |

## Contrato de datos

```json
{
  "type": "Hero",
  "data": {
    "variant": "editorial",
    "profile_photo": "images/hero/retrato.jpg",
    "image_alt": "Retrato de Marcela Basterra",
    "badge": "Perfil institucional",
    "name": "Marcela Basterra",
    "subtitle": "Abogada constitucionalista",
    "description": "Trayectoria académica, profesional e intervención pública.",
    "indicators": [{ "label": "Derecho constitucional" }],
    "cta_primary": { "route_id": "1", "btn_label": "Ver trayectoria" }
  }
}
```

## Reglas de renderizado

- Las cuatro versiones renderizan el mismo contrato de contenido; `variant` solo cambia la composición.
- La variante `listing` (intro de catálogo) replica el intro de la página Libros: superficie blanca con borde inferior, titular enorme con punto de acento y columna derecha con introducción, presentación breve y cifras con borde superior. Las cifras se activan cuando el `label` de un indicador comienza con un número (ej. `13 Actividades vigentes`).
- La variante `listing` no muestra la franja inferior de indicadores ni la sección de cargos destacados.
- La fotografía nunca lleva texto superpuesto y conserva color natural.
- Si no hay fotografía, las versiones `editorial` e `institutional` mantienen una superficie editorial alternativa.
- Los CTA sin URL o sin etiqueta no se renderizan.
- Los cargos se leen de `CargoInstitucional`; no se duplican sus textos dentro del bloque.
- Las rutas admiten destino interno, URL externa y descarga de archivo.
- El título principal se renderiza como `h1`; usar el bloque una sola vez por página.
- Las entradas respetan `prefers-reduced-motion` y se muestran inmediatamente si GSAP no está disponible.
