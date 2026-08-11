# Block: Gallery

## Meta
- **Nombre:** Gallery
- **Categoría:** Multimedia
- **Label:** Galería de imágenes

## Schema

| Campo | Tipo | Requerido | Default | Notas |
|-------|------|-----------|---------|-------|
| `title` | text | no | — | Título opcional del bloque |
| `images` | gallery (FileUpload múltiple) | sí | — | Galería de imágenes, se suben a `images/galerias` (resize 1280x640) |
| `style` | select | no | `full` | Opciones: `full` (Carrusel completo), `container` (Contenedor) |
| `auto_play` | toggleButtons | no | `false` | `true` (Sí) / `false` (No). Activa autoplay del carrousel cada 5s |

## Comportamiento
- Renderiza un carrousel de imágenes (Swiper) con una imagen visible por vez.
- El campo `style` controla el ancho del contenedor:
  - `full`: carrusel a ancho completo del bloque.
  - `container`: carrusel dentro del contenedor estándar de la página.
- `auto_play`: si es `true`, el carrousel avanza automáticamente con `autoplay-delay="5000"`.
- Navegación con flechas (prev/next) y paginación con bullets, generadas por el id único del bloque.
- Cada slide muestra la imagen centrada (`object-contain`) con altura máxima de 980px.

## Contrato de datos (JSON)

```json
{
  "blockTitle": "Galería del libro",
  "title": "Galería del libro",
  "images": [
    "galerias/01.jpg",
    "galerias/02.jpg",
    "galerias/03.jpg"
  ],
  "style": "container",
  "auto_play": true
}
```

## Notas de implementación
- `images` es un array de strings (rutas públicas a las imágenes subidas).
- La vista asigna un `id` único por defecto (si no se pasa) para los selectores de navegación y paginación del Swiper.
- Si un ítem del array no es string y trae `route`, el slide se envuelve en un link.
- `style` aplica la clase `gallery-container` cuando es `container`, o la clase `full` en caso contrario.
- No renderizar el bloque si `images` está vacío.
