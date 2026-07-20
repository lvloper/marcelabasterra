# Design System — Marcela Basterra

> Última actualización: 2026-06-08
> Versión: 2.0

---

## 1. Principios

- **Mobile-first responsive** — todo se diseña mobile primero, luego breakpoints up.
- **Accesible WCAG AA** — contraste mínimo 4.5:1 en texto, focus visible, labels en formularios.
- **Tokens via CSS custom properties** — colores, tamaños y espaciados referencian variables, no valores hardcodeados.
- **Todo editable desde CMS** — ningún texto, imagen o color debe estar hardcodeado en vistas.
- **Tailwind-first** — usar clases utilitarias de Tailwind. Las custom properties se exponen vía `tailwind.config.js`.

---

## 2. Colores

### 2.1 Paleta

| Token | Valor | Uso |
|-------|-------|-----|
| `--color-primary` | `#2a3461` | Botones default, links, títulos, footer bg, acciones estándar |
| `--color-primary-hover` | `#1f2748` | Hover de botones y links primary |
| `--color-secondary` | `#9499b0` | Texto secundario, iconos, destacados suaves |
| `--color-secondary-hover` | `#7a80a0` | Hover secondary |
| `--color-secondary-light` | `#c4c7d4` | Fondos sutiles, bordes, separadores |
| `--color-accent` | `#45bfe3` | SOLO botones MUY destacados / featured |
| `--color-accent-hover` | `#3aaed2` | Hover accent |
| `--color-black` | `#000000` | Texto principal |
| `--color-white` | `#FFFFFF` | Fondos, texto sobre oscuro |
| `--color-gray` | `#5c6b73` | Texto base gris |
| `--color-gray-2` | `#bec4c7` | Gris claro, bordes, fondos alternados |
| `--color-gray-3` | `#f3f4f4` | Gris fondo, secciones |

### 2.2 Reglas de uso

```
FONDO           → gray-3 (#f3f4f4) o white (#ffffff)
TEXTO           → black (#000000) o gray (#5c6b73)
ACCIÓN          → primary (#2a3461)
DESTAQUE EXTRA  → accent (#45bfe3)
```

- Primary se usa para botones comunes, links, footer, títulos destacados.
- Accent se reserva para CTAs principales, botones de conversión, acciones premium.
- No usar accent para texto ni fondos de sección.

### 2.3 Gradientes

| Token | Valor |
|-------|-------|
| `--gradient-primary-secondary` | `linear-gradient(to right, var(--color-primary), var(--color-secondary))` |
| `--gradient-secondary-primary` | `linear-gradient(to right, var(--color-secondary), var(--color-primary))` |

### 2.4 Clases Tailwind correspondientes

```css
text-primary           → color: var(--color-primary)
bg-primary             → background-color: var(--color-primary)
text-secondary         → color: var(--color-secondary)
bg-secondary           → background-color: var(--color-secondary)
bg-secondary-light     → background-color: var(--color-secondary-light)
text-accent            → color: var(--color-accent)
bg-accent              → background-color: var(--color-accent)
text-accent-hover      → color: var(--color-accent-hover)
bg-accent-hover        → background-color: var(--color-accent-hover)
text-black             → color: var(--color-black)
bg-white               → background-color: var(--color-white)
text-gray              → color: var(--color-gray)
bg-gray                → background-color: var(--color-gray)
bg-gray-2              → background-color: var(--color-gray-2)
bg-gray-3              → background-color: var(--color-gray-3)
```

### 2.5 Fondos combinados (clases custom)

| Clase | Descripción |
|-------|-------------|
| `bg-primary-white` | Mitad superior `--color-primary`, mitad inferior blanco |
| `bg-white-gray` | Mitad superior blanco, mitad inferior `--color-gray-3` |

---

## 3. Tipografía

### 3.1 Fuentes

| Familia | Pesos | Archivos | Uso | Clase Tailwind |
|---------|-------|----------|-----|----------------|
| **Bellota Text** | 400, 700 | `public/fonts/BellotaText-Regular.ttf`, `public/fonts/BellotaText-Bold.ttf` | Body, headings, UI, todo el sitio | `font-sans` |
| **Source Serif 4** | 500 | `public/fonts/SourceSerif4_18pt-Medium.ttf` | Texto editorial, WYSIWYG, artículos | `font-source` |

### 3.2 Reglas de uso

```
TITULARES   → Bellota Text Bold (700)   → font-sans font-bold
TEXTOS BASE → Bellota Text Regular (400) → font-sans
EDITORIAL   → Source Serif 4 Medium (500) → font-source
```

### 3.3 Escala tipográfica

| Clase | Size | Uso |
|-------|------|-----|
| `text-xs` | 0.75rem | Notas, labels pequeños |
| `text-sm` | 0.85rem | Texto secundario |
| `text-base` | 0.85rem | Body (mismo que sm — ajustado para diseño editorial) |
| `text-md` | 1.1rem | Párrafos destacados |
| `text-lg` | 1.3rem | Subtítulos |
| `text-xl` | 1.4rem | Títulos de sección |
| `text-2xl` | 1.6rem | Títulos de bloque |
| `text-3xl` | 2rem | Títulos principales |
| `text-4xl` | 2.7rem | Hero headings |
| `text-5xl` | 3.5rem | Display headings |

### 3.4 Clases tipográficas de utilidad

| Clase | Definición |
|-------|-----------|
| `title-normal` | `text-xl lg:text-2xl font-bold font-sans` leading 1.15 |
| `description-normal` | `leading-snug font-source text-base` |
| `description2-normal` | `font-source leading-snug text-base` |
| `text-normal` / `normal-text` | `text-base font-sans` |
| `sm-text` | `text-md xl:text-base` |

### 3.5 Mapeo de clases viejas → nuevas

| Vieja | Nueva |
|-------|-------|
| `font-sans` (Manrope) | `font-sans` (Bellota Text) |
| `font-poppins` | `font-source` |
| `font-pt` | `font-sans` |
| `futura-light` | `font-normal` |
| `futura-bold` | `font-bold` |
| `font-heavy` | `font-bold` |

---

## 4. Botones

**Todos flat, sin sombra, border-radius chico (`rounded-sm`). Con icono de flecha a la derecha.**

```blade
{{-- Primario (acciones comunes) --}}
<a class="inline-flex items-center gap-2 bg-primary text-white px-6 py-3
          font-bold rounded-sm hover:bg-primary-hover transition-colors group">
    {{ $label }}
    <x-lucide-arrow-right class="w-4 h-4 group-hover:translate-x-1 transition-transform" />
</a>

{{-- Destacado (SOLO CTAs principales) --}}
<a class="inline-flex items-center gap-2 bg-accent text-white px-6 py-3
          font-bold rounded-sm hover:bg-accent-hover transition-colors group">
    {{ $label }}
    <x-lucide-arrow-right class="w-4 h-4 group-hover:translate-x-1 transition-transform" />
</a>

{{-- Outline --}}
<a class="inline-flex items-center gap-2 border border-primary text-primary px-6 py-3
          font-bold rounded-sm hover:bg-primary hover:text-white transition-colors group">
    {{ $label }}
    <x-lucide-arrow-right class="w-4 h-4 group-hover:translate-x-1 transition-transform" />
</a>
```

---

## 5. Espaciado y Layout

### 5.1 Container

```css
container {
  padding-left: 2rem;
  padding-right: 2rem;
}

@media (min-width: 1280px) {
  container {
    padding-left: 4rem;
    padding-right: 4rem;
  }
}

@media (min-width: 1700px) {
  container {
    padding-left: 6rem;
    padding-right: 6rem;
  }
}
```

### 5.2 Espaciado de secciones

| Clase | Uso |
|-------|-----|
| `py-12 md:py-16` | Padding vertical estándar de sección |
| `py-16 md:py-20` | Padding vertical de sección destacada |
| `py-24 md:py-32` | Padding vertical de hero |

### 5.3 Márgenes de bloque (CMS)

| Clase | Significado |
|-------|------------|
| `mb-0` | Sin margen |
| `mb-6` | Pequeño (1.5rem) |
| `mb-12` | Estándar (3rem) |
| `mb-24` | Grande (6rem) |

### 5.4 Variantes de container

| Clase | Uso |
|-------|-----|
| `container` | Contenido general centrado |
| `container-card` | Cards estrechas en detalle |
| `container-notice` | Contenido de blog/noticia |
| `container-info` | Layout con sidebar |
| `container-modal` | Contenido de modal (50% width en xl+) |
| `container-accordeon` | Contenido de acordeón |
| `container-sm` | Contenido angosto |
| `compact` | `container max-w-[980px]` |
| `container-banners` | Banners full-width con padding lateral |

---

## 6. Breakpoints

| Prefijo | Min-width | Uso típico |
|---------|-----------|------------|
| _default_ | 0px | Mobile (base) |
| `xs` | 480px | Tablets pequeñas |
| `sm` | 640px | Tablets |
| `md` | 768px | Tablets landscape |
| `lg` | 1024px | Desktop |
| `xl` | 1080px | Desktop medio |
| `2xl` | 1280px | Desktop grande |
| `3xl` | 1340px | Desktop XL |
| `4xl` | 1640px | Desktop XXL |
| `5xl` | 1920px | Full HD |

---

## 7. Componentes Base

### 7.1 `<x-block>`
Wrapper de sección para cada bloque del page builder.
```blade
<x-block class="py-12 md:py-20">
  <!-- contenido del bloque -->
</x-block>
```
Atributos: `class` (clases CSS adicionales). Renderiza `<section class="{{ $class }}">`.

### 7.2 `<x-link>`
Link que resuelve rutas internas (route_id), externas (external_url), anclas (#anchor) y descargas de archivo (-1).
```blade
<x-link :attrs="['route_id' => $route_id, 'btn_label' => 'Ver más']" class="btn-primary">
  Ver más
</x-link>
```
Parámetros: `route_id`, `external_url`, `anchor`, `file`, `btn_label`, `new_window`, `disableWireNavitage`, `hideIfNull`.

### 7.3 `<x-layout>`
Layout de página completa (header + main + footer).

### 7.4 `<x-common.header>`
Header con navegación principal.

### 7.5 `<x-common.footer>`
Footer del sitio.

---

## 8. Lenguaje visual

### 8.1 Botones

**Flat, sin sombra, border-radius chico (`rounded-sm`).** Icono de flecha a la derecha con micro-animación.

```blade
{{-- Primario (accion comun) --}}
<a class="inline-flex items-center gap-2 bg-primary text-white px-6 py-3
          font-bold rounded-sm hover:bg-primary-hover transition-colors group">
    {{ $label }}
    <x-lucide-arrow-right class="w-4 h-4 group-hover:translate-x-1 transition-transform" />
</a>

{{-- Destacado (solo CTAs principales) --}}
<a class="inline-flex items-center gap-2 bg-accent text-white px-6 py-3
          font-bold rounded-sm hover:bg-accent-hover transition-colors group">
    {{ $label }}
    <x-lucide-arrow-right class="w-4 h-4 group-hover:translate-x-1 transition-transform" />
</a>

{{-- Outline --}}
<a class="inline-flex items-center gap-2 border border-primary text-primary
          px-6 py-3 font-bold rounded-sm hover:bg-primary hover:text-white
          transition-colors group">
    {{ $label }}
    <x-lucide-arrow-right class="w-4 h-4 group-hover:translate-x-1 transition-transform" />
</a>
```

### 8.2 Cortes y transiciones de sección

**SVG wave dividers** entre secciones de distinto color de fondo. Suavizan el paso entre luz y oscuridad.

```blade
{{-- Wave divider: oscuro arriba, claro abajo --}}
<div class="relative w-full overflow-hidden leading-none z-10 text-gray-3">
    <svg class="relative w-full h-12 md:h-20 lg:h-28" viewBox="0 0 1440 100" preserveAspectRatio="none">
        <path d="M0,80 C480,80 480,0 720,0 C960,0 960,80 1440,80 L1440,100 L0,100 Z" fill="currentColor" />
    </svg>
</div>

{{-- Wave divider inverso: claro arriba, oscuro abajo --}}
<div class="relative w-full overflow-hidden leading-none z-10 text-primary">
    <svg class="relative w-full h-12 md:h-20 lg:h-28 rotate-180" viewBox="0 0 1440 100" preserveAspectRatio="none">
        <path d="M0,80 C480,80 480,0 720,0 C960,0 960,80 1440,80 L1440,100 L0,100 Z" fill="currentColor" />
    </svg>
</div>
```

**Fondo diagonal / angular** para secciones que necesitan corte no rectangular.

```blade
{{-- Corte diagonal: background primary que invade la seccion siguiente --}}
<div class="relative">
    <div class="absolute inset-0 z-0 pointer-events-none">
        <svg class="w-full h-full" preserveAspectRatio="none" viewBox="0 0 100 100">
            <path d="M0,5 L53,5 C56,5 57,0 62,0 L100,0 L100,92 L53,92 C50,92 49,100 44,100 L0,100 Z" fill="#2a3461" />
        </svg>
    </div>
    <div class="relative z-10">
        {{-- contenido --}}
    </div>
</div>
```

**Contenedor con bordes redondeados grandes** para secciones destacadas (blog, testimonios).

```blade
<section class="container rounded-[2.5rem] bg-gray-3 py-20 my-20">
    {{-- contenido --}}
</section>
```

### 8.3 Cards y superficies

**Cards limpias, sin sombra, con hover sutil.**

```blade
{{-- Card estandar --}}
<article class="bg-white rounded-2xl p-6 md:p-8 border border-gray-2
                hover:-translate-y-1 transition-all duration-300 group">
    <div class="w-10 h-10 rounded-full bg-primary/5 flex items-center justify-center mb-4
                group-hover:bg-primary/10 transition-colors">
        <x-lucide-icon class="w-5 h-5 text-primary" />
    </div>
    <h3 class="font-sans font-bold text-lg mb-2">{{ $title }}</h3>
    <p class="text-gray text-sm leading-relaxed">{{ $description }}</p>
</article>

{{-- Card con borde mas redondo (tipo feature) --}}
<article class="bg-gray-3 rounded-3xl p-8 md:p-12 border border-gray-2
                hover:-translate-y-1 hover:bg-white transition-all duration-300">
    {{-- contenido --}}
</article>
```

### 8.4 Fondos decorativos

**Grid pattern sutil** con mascara radial, para secciones que necesitan textura de fondo sin distraer.

```css
.bg-grid {
    background-size: 80px 80px;
    background-image:
        linear-gradient(to right, rgba(0,0,0,0.04) 1px, transparent 1px),
        linear-gradient(to bottom, rgba(0,0,0,0.04) 1px, transparent 1px);
    mask-image: radial-gradient(circle at center, black 40%, transparent 100%);
}
```

```blade
<div class="relative">
    <div class="absolute inset-0 bg-grid pointer-events-none z-0"></div>
    <div class="relative z-10 container">
        {{-- contenido --}}
    </div>
</div>
```

### 8.5 Glass / Vidrio esmerilado

**Para elementos flotantes sobre imágenes o fondos oscuros.** Solo en desktop (mobile usa fondo solido).

```css
.glass-card {
    background: rgba(255, 255, 255, 0.1);
    backdrop-filter: blur(12px);
    -webkit-backdrop-filter: blur(12px);
    border: 1px solid rgba(255, 255, 255, 0.15);
}
```

### 8.6 Hero

**Titulo grande + descripcion + posible imagen lateral. Layout mobile-first que escala a desktop.**

```blade
<section class="relative bg-gray-3 overflow-hidden">
    {{-- Grid decorativo de fondo --}}
    <div class="absolute inset-0 bg-grid pointer-events-none z-0"></div>

    <div class="relative z-10 container">
        <div class="flex flex-col md:flex-row gap-8 md:gap-16 items-center py-12 md:py-24">
            {{-- Columna de texto --}}
            <div class="w-full md:w-3/5">
                <h1 class="font-sans font-bold text-3xl md:text-4xl lg:text-5xl
                           text-primary leading-[1.05] mb-6">
                    {{ $title }}
                </h1>
                @isset($description)
                    <p class="text-md md:text-xl text-gray max-w-xl leading-relaxed">
                        {{ $description }}
                    </p>
                @endisset
                @isset($cta_label)
                    <a href="{{ $cta_url ?? '#' }}"
                       class="inline-flex items-center gap-2 bg-primary text-white
                              px-6 py-3 font-bold rounded-sm mt-8
                              hover:bg-primary-hover transition-colors group">
                        {{ $cta_label }}
                        <x-lucide-arrow-right class="w-4 h-4 group-hover:translate-x-1 transition-transform" />
                    </a>
                @endisset
            </div>

            {{-- Imagen lateral (opcional) --}}
            @isset($image)
            <div class="w-full md:w-2/5">
                <img src="{{ $image }}" alt="{{ $title ?? '' }}"
                     class="w-full h-auto object-cover rounded-2xl"
                     loading="eager" />
            </div>
            @endisset
        </div>
    </div>
</section>
```

### 8.7 CTA / Llamado a la acción

**Imagen full-width con overlay oscuro, texto centrado, botones abajo.**

```blade
<section class="relative h-[400px] md:h-[500px] rounded-t-[2.5rem] overflow-hidden">
    <img src="{{ $image }}" alt="" class="absolute inset-0 w-full h-full object-cover" />
    <div class="absolute inset-0 bg-gradient-to-b from-primary/70 via-primary/40 to-primary/80"></div>
    <div class="relative z-10 flex flex-col items-center justify-center h-full text-center px-6">
        <h2 class="text-3xl md:text-5xl font-sans font-bold text-white mb-6 max-w-2xl">
            {{ $title }}
        </h2>
        <div class="flex flex-col md:flex-row gap-4">
            <a href="{{ $cta_url ?? '#' }}"
               class="bg-white text-primary px-8 py-3 rounded-sm font-bold
                      hover:bg-gray-100 transition-colors inline-flex items-center gap-2 group">
                {{ $cta_label }}
                <x-lucide-arrow-right class="w-4 h-4 group-hover:translate-x-1 transition-transform" />
            </a>
            @isset($secondary_label)
            <a href="{{ $secondary_url ?? '#' }}"
               class="border border-white/40 text-white px-8 py-3 rounded-sm font-bold
                      hover:bg-white/10 transition-colors backdrop-blur-sm">
                {{ $secondary_label }}
            </a>
            @endisset
        </div>
    </div>
</section>
```

### 8.8 Marquesina / Carrusel de logos

**Scroll infinito horizontal con fade en bordes.**

```blade
<div class="border-y border-gray-2 py-8 overflow-hidden relative">
    <div class="absolute left-0 top-0 bottom-0 w-12 bg-gradient-to-r from-gray-3 to-transparent z-10"></div>
    <div class="absolute right-0 top-0 bottom-0 w-12 bg-gradient-to-l from-gray-3 to-transparent z-10"></div>
    <div class="flex gap-8 md:gap-16 items-center w-max animate-scroll">
        {{-- duplicar items para loop --}}
        @foreach([...$items, ...$items] as $item)
            <div class="opacity-40 hover:opacity-100 transition-opacity duration-300">
                {{ $item }}
            </div>
        @endforeach
    </div>
</div>
```

```css
@keyframes scroll {
    0%   { transform: translateX(0); }
    100% { transform: translateX(-50%); }
}
.animate-scroll {
    animation: scroll 40s linear infinite;
}
```

---

## 9. Patrones de diseño

### 9.1 Estructura de bloque estándar

```blade
<x-block class="py-12 md:py-20 {{ $mb }} {{ $mdMb }} {{ implode(' ', $clases ?? []) }}"
         id="{{ $blockAnchor ?? '' }}">
  <div class="container">
    @isset($title)
      <h2 class="title-normal mb-6 md:mb-8">{{ $title }}</h2>
    @endisset
    {{-- contenido del bloque --}}
  </div>
</x-block>
```

### 9.2 Cards

```blade
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 md:gap-8">
  {{-- items --}}
</div>
```

### 9.3 Media + Texto

```blade
<div class="flex flex-col md:flex-row gap-8 md:gap-12 items-center">
  <div class="w-full md:w-1/2">{{-- media --}}</div>
  <div class="w-full md:w-1/2">{{-- texto --}}</div>
</div>
```

### 9.4 Hero

```blade
<div class="relative bg-primary text-white py-16 md:py-24">
  <div class="container">
    <h1 class="text-4xl md:text-5xl font-bold font-sans">{{ $title }}</h1>
    <p class="text-lg md:text-xl mt-4 max-w-2xl">{{ $subtitle }}</p>
  </div>
</div>
```

### 9.5 Formulario

```blade
<form class="space-y-6 max-w-2xl mx-auto">
  <div>
    <label class="block text-sm font-bold mb-1 font-sans">{{ $label }}</label>
    <input type="text" class="w-full border border-gray-2 px-4 py-2 rounded-sm focus:outline-none focus:border-primary">
  </div>
  <button type="submit" class="bg-primary text-white px-8 py-3 font-bold rounded-sm hover:bg-primary-hover transition-colors">
    {{ $submit_label }}
  </button>
</form>
```

---

## 10. Reglas para agentes

1. **No hardcodear colores** — usar clases Tailwind (`text-primary`, `bg-gray-3`, etc.) o tokens CSS (`var(--color-primary)`).
2. **No hardcodear fuentes** — usar `font-sans` (Bellota Text) o `font-source` (Source Serif 4).
3. **Texto editable** — todo texto debe venir de una variable Blade (`{{ $title }}`), no hardcodeado.
4. **Imágenes responsive** — usar `w-full h-auto object-cover` y lazy loading.
5. **Estados interactivos** — hover, focus, active deben estar definidos.
6. **Mobile-first** — escribir el estilo base para mobile, luego override con `md:`, `lg:`, `xl:`.
7. **Animaciones** — GSAP para animaciones de entrada (scroll-triggered). `prefers-reduced-motion` respetado.
8. **Wrapper section** — todo bloque debe envolverse en `<x-block>` para consistencia.
9. **Íconos** — usar Lucide (ya instalado). No introducir otras librerías de iconos.
10. **Variables del CMS** — `$mb`, `$mdMb`, `$clases`, `$blockAnchor`, `$blockTitle` vienen de `PageBlock` y deben usarse en la vista.
11. **Botones** — flat, sin sombra, `rounded-sm`. Primary (`#2a3461`) para acciones comunes. Accent (`#45bfe3`) solo para CTAs principales.
12. **Tipografía** — Bellota Text (`font-sans`) para todo por defecto. Source Serif 4 (`font-source`) solo para texto editorial/WYSIWYG.
13. **Colores de texto** — `text-black` para contenido principal, `text-gray` para secundario. No usar `text-primary` como color de lectura.

---

## 11. CSS Custom Properties (catálogo completo)

```css
:root {
  /* Colores */
  --color-primary: #2a3461;
  --color-primary-hover: #1f2748;
  --color-secondary: #9499b0;
  --color-secondary-hover: #7a80a0;
  --color-secondary-light: #c4c7d4;
  --color-accent: #45bfe3;
  --color-accent-hover: #3aaed2;
  --color-black: #000000;
  --color-white: #FFFFFF;
  --color-gray: #5c6b73;
  --color-gray-2: #bec4c7;
  --color-gray-3: #f3f4f4;

  /* Gradientes */
  --gradient-primary-secondary: linear-gradient(to right, var(--color-primary), var(--color-secondary));
  --gradient-secondary-primary: linear-gradient(to right, var(--color-secondary), var(--color-primary));

  /* Layout */
  --header-height: 80px;
  --side-bar-width: 260px;
  --side-bar-height: calc(100dvh - var(--header-height));
  --scroll-margin-top: calc(var(--header-height) + 1rem);

  /* Tipografía */
  --font-size-base: 0.85rem;
  --font-size-2xl: 0.9rem;
  --font-size-3xl: 1rem;

  /* Swiper */
  --swiper-theme-color: var(--color-primary);
  --swiper-pagination-color: var(--color-primary);
  --swiper-pagination-bullet-width: 20px;
  --swiper-pagination-bullet-border-radius: 5px;
  --swiper-pagination-bullet-horizontal-gap: 6px;
  --swiper-pagination-bullet-inactive-width: 10px;
  --swiper-pagination-bullet-inactive-color: var(--color-primary);
  --swiper-pagination-bullet-inactive-opacity: 0.3;

  /* Scrollbar */
  --scrollbar-color: var(--color-secondary);

  /* Browser */
  interpolate-size: allow-keywords;
}
```

---

## 12. Scripts y dependencias frontend

| Librería | Uso |
|----------|-----|
| **GSAP** + ScrollTrigger | Animaciones de entrada, scroll |
| **Swiper** | Carruseles, sliders |
| **lite-youtube-embed** | Embeds de video ligeros |
| **Alpine.js** | Interactividad (vía Livewire) |
| **Lucide** | Iconos SVG |
