# Design System — Marcela Basterra

> Manual de identidad visual e implementación web  
> Versión: 1.0  
> Estado: base operativa para diseño y maquetación  
> Uso principal: adaptar y crear bloques para el sitio institucional de Marcela Basterra  
> Base estructural permitida: OpenTailwind  
> Base estética permitida: únicamente este documento

---

## 1. Propósito del documento

Este documento define el sistema visual, editorial y de interacción para las interfaces web de la marca personal **Marcela Basterra**, abogada constitucionalista.

Su objetivo principal es permitir que una persona desarrolladora o una LLM agéntica pueda:

1. seleccionar estructuras útiles de OpenTailwind;
2. eliminar la identidad visual original del bloque;
3. adaptar el bloque a la identidad de Marcela Basterra;
4. crear nuevos bloques compatibles con el resto del sitio;
5. mantener consistencia entre páginas, secciones y contenidos;
6. evitar decisiones estéticas improvisadas.

Este manual no debe interpretarse como una colección de sugerencias. Las reglas marcadas como **obligatorias** deben cumplirse en todos los bloques.

---

## 2. Idea central de marca

Marcela Basterra debe presentarse como:

> **Una autoridad constitucional que explica con claridad, fundamenta con rigor e interviene en el presente.**

La identidad debe comunicar, en este orden:

1. autoridad institucional;
2. trayectoria profesional y académica;
3. rigor jurídico;
4. claridad expositiva;
5. vigencia e intervención pública;
6. una personalidad editorial propia.

El sitio no debe parecer:

- una plantilla genérica para estudios jurídicos;
- un sitio universitario tradicional;
- una landing de servicios tecnológicos;
- una interfaz SaaS;
- un portfolio creativo experimental;
- un diario clásico trasladado literalmente a la web.

La dirección visual es **editorial digital contemporánea**, con recursos tomados de publicaciones y plataformas actuales: tipografía de gran escala, bloques de color, grillas asimétricas, fotografía protagonista, líneas visibles y movimiento editorial.

---

## 3. Objetivo principal del sitio

El objetivo central es:

> **Presentar trayectoria y autoridad.**

Las decisiones de interfaz deben priorizar:

- antecedentes;
- cargos y funciones;
- publicaciones;
- actividad académica;
- intervenciones públicas;
- participación en medios;
- conferencias;
- pensamiento jurídico;
- vigencia profesional.

Las llamadas a la acción deben ser secundarias respecto de la construcción de autoridad. No convertir la portada en una landing comercial agresiva.

---

## 4. Personalidad visual

### 4.1 Dirección general

La identidad combina:

- estructura editorial contemporánea;
- autoridad institucional;
- ritmo visual activo;
- fotografía documental y de retrato;
- tipografía expresiva;
- superficies planas;
- composiciones modulares;
- animación visible pero controlada.

### 4.2 Principios obligatorios

- Diseño flat.
- Sin sombras.
- Sin bordes redondeados.
- Sin tarjetas flotantes.
- Sin negro como color estructural.
- Sin texto superpuesto sobre fotografías.
- Sin degradados como recurso habitual.
- Sin iconografía decorativa dominante.
- Sin estética de dashboard o aplicación.
- Sin automatismos que distraigan de la lectura.
- Sin adoptar la identidad visual original de OpenTailwind.

### 4.3 Referencia conceptual

La dirección debe sentirse más cercana a un cruce entre:

- publicación digital moderna;
- marca institucional contemporánea;
- archivo vivo de trayectoria;
- plataforma de pensamiento y actualidad.

La estructura puede ser enérgica y asimétrica, pero nunca caótica.

---

## 5. Jerarquía de reglas

Al adaptar un bloque, aplicar esta prioridad:

1. accesibilidad y legibilidad;
2. reglas obligatorias de este manual;
3. identidad de marca;
4. función del contenido;
5. estructura del bloque original;
6. detalles visuales del bloque original.

Si una decisión de OpenTailwind contradice este manual, se elimina o reemplaza.

---

# 6. Uso de OpenTailwind

## 6.1 Rol de la librería

OpenTailwind se utiliza como:

- catálogo de estructuras;
- punto de partida para grillas;
- referencia de comportamiento responsive;
- base de componentes;
- fuente de patrones funcionales;
- acelerador de desarrollo.

OpenTailwind **no** se utiliza como:

- sistema de color;
- sistema tipográfico;
- criterio de espaciado final;
- identidad visual;
- referencia de bordes;
- referencia de botones;
- referencia de tarjetas;
- referencia de animación final;
- criterio para elegir iconos;
- fuente definitiva de contenido.

## 6.2 Colecciones

Cuando haya varias alternativas estructurales:

1. revisar primero bloques de composición más contemporánea o asimétrica;
2. evaluar luego bloques simples que puedan transformarse;
3. elegir por estructura, no por apariencia;
4. evitar seleccionar un bloque solo porque sus colores o imágenes parecen adecuados.

La colección de origen no determina el resultado final.

## 6.3 Qué puede conservarse

Puede conservarse, si es útil:

- orden semántico;
- estructura HTML;
- grilla responsive;
- distribución de columnas;
- comportamiento de menú;
- acordeones;
- tabs;
- carruseles manuales;
- lógica de interacción;
- jerarquía funcional;
- atributos ARIA correctos;
- relaciones de aspecto;
- breakpoints;
- organización de datos;
- estructura de formularios.

## 6.4 Qué debe revisarse siempre

Revisar y adaptar obligatoriamente:

- colores;
- fuentes;
- tamaño tipográfico;
- interlineado;
- `border-radius`;
- sombras;
- bordes;
- fondos oscuros;
- botones;
- badges;
- iconos;
- espaciado vertical;
- ancho de contenedores;
- animaciones;
- overlays;
- posición del texto;
- tratamiento de imágenes;
- estados hover;
- estados focus;
- modo oscuro;
- contenido de ejemplo.

## 6.5 Qué debe eliminarse siempre

Eliminar:

- todas las clases `rounded-*`;
- todas las clases `shadow-*`;
- fondos negros o casi negros usados como superficie;
- gradientes decorativos;
- blur decorativo;
- glow;
- glassmorphism;
- blobs;
- círculos decorativos genéricos;
- badges redondeados tipo píldora;
- iconos grandes como protagonistas;
- overlays de color sobre fotografías;
- texto ubicado encima de fotografías;
- autoplay;
- cursores personalizados;
- animaciones elásticas;
- efectos 3D;
- elevación de tarjetas en hover;
- estilos propios de modo oscuro no definidos en este manual.

## 6.6 Regla de transformación

Un bloque adaptado no debe percibirse como “un bloque de OpenTailwind con otros colores”.

Debe percibirse como una pieza creada específicamente para Marcela Basterra.

---

# 7. Logotipo

## 7.1 Archivos disponibles

- Con tagline: `public/logos/logo-con-tagline.svg`
- Sin tagline: `public/logos/logo-sin-tagline.svg`
- Horizontal: `public/logos/logo-horizontal-sin-tagline.svg`
- Horizontal con tagline: `public/logos/logo-horizontal-con-tagline.svg`
- Monograma: `public/logos/monograma.svg`
- Logotipo solo: `public/logos/logotipo.svg`
- Logotipo horizontal: `public/logos/logotipo-horizontal.svg`

## 7.2 Uso principal

La marca principal es el nombre completo.

Siempre que el espacio permita leerlo correctamente, usar una versión que incluya **Marcela Basterra**.

## 7.3 Header

Estado inicial:

- usar `logo-sin-tagline.svg`;
- no usar tagline en la navegación principal;
- respetar un área de seguridad visual;
- mantener alta legibilidad.

Estado reducido durante scroll:

- reemplazar el logo completo por `monograma.svg`;
- reducir la altura del header;
- no deformar ni recortar el logo;
- ejecutar el cambio con una transición breve.

## 7.4 Mobile

En mobile puede utilizarse:

- logo sin tagline cuando el ancho lo permita;
- monograma en estados compactos;
- monograma dentro del header reducido;
- nombre completo dentro del panel de navegación.

## 7.5 Usos secundarios

El monograma puede utilizarse como:

- sello institucional;
- firma visual en el footer;
- marcador editorial;
- favicon;
- recurso de cierre;
- detalle en piezas reducidas.

No utilizar el monograma como patrón decorativo repetido en exceso.

## 7.6 Prohibiciones

- No agregar sombras al logo.
- No encerrar el logo en cápsulas.
- No aplicar degradados.
- No alterar proporciones.
- No cambiar sus tipografías.
- No colocar el logo sobre una fotografía compleja.
- No combinar varias versiones del logo en un mismo bloque sin motivo funcional.

---

# 8. Sistema de color

## 8.1 Paleta principal

| Token | Valor | Función |
|---|---:|---|
| `brand-primary` | `#2a3461` | Azul institucional principal |
| `brand-primary-muted` | `#9499b0` | Azul reducido, fondos secundarios y elementos no textuales |
| `brand-accent` | `#45bfe3` | Acento puntual |
| `text-base` | `#5c6b73` | Texto base sobre fondos claros |
| `text-muted` | `#bec4c7` | Texto muy secundario y elementos decorativos; no usar para párrafos |
| `surface-white` | `#ffffff` | Fondo general |
| `surface-light` | `#f3f4f4` | Fondo gris claro |
| `surface-ivory` | `#f5f0e8` | Fondo editorial cálido |
| `border-light` | `#bec4c7` | Divisores suaves |
| `on-primary` | `#ffffff` | Texto principal sobre azul |
| `on-primary-soft` | `#f3f4f4` | Texto secundario sobre azul |

`surface-ivory` se adopta como valor operativo para el fondo marfil. Si la dirección de arte final modifica este valor, debe actualizarse en un único token y no directamente en componentes.

## 8.2 Fondo general

El fondo base del sitio es:

```css
background: #ffffff;
```

El blanco puro permite que las secciones marfil, gris claro y azul institucional generen contrastes claros.

## 8.3 Azul institucional

`#2a3461` es el único color oscuro estructural.

Se utiliza para:

- fondos de secciones destacadas;
- títulos sobre fondos claros;
- botones primarios;
- bordes de alta jerarquía;
- navegación;
- destacados;
- subrayados institucionales;
- superficies de cierre;
- bloques de trayectoria o pensamiento;
- estados hover.

No sustituirlo por negro.

## 8.4 Marfil

`#f5f0e8` puede utilizarse frecuentemente en secciones completas.

Funciones:

- aportar temperatura editorial;
- separar capítulos;
- acompañar textos largos;
- panel de navegación;
- citas;
- publicaciones;
- transiciones entre bloques fotográficos y bloques institucionales.

No usarlo como fondo de todas las tarjetas dentro de una sección marfil.

## 8.5 Gris claro

`#f3f4f4` se utiliza para:

- secciones secundarias;
- fichas;
- áreas informativas;
- formularios;
- estados suaves;
- fondos sobre los que el marfil no sea apropiado.

No debe competir en frecuencia con el blanco y el marfil.

## 8.6 Celeste de acento

`#45bfe3` debe usarse poco.

Usos permitidos:

- subrayar una palabra;
- línea corta;
- marcador activo;
- estado focus;
- pequeño detalle de botón;
- indicador de navegación;
- borde parcial;
- dato puntual;
- microinteracción.

Usos prohibidos:

- grandes fondos de sección;
- párrafos;
- titulares completos;
- múltiples tarjetas simultáneas;
- superficies dominantes;
- gradientes.

Regla recomendada: el celeste no debería ocupar visualmente más del 5 % de una vista.

## 8.7 Gris base

`#5c6b73` se utiliza para:

- párrafos;
- navegación secundaria;
- descripciones;
- formularios;
- metadatos legibles;
- texto funcional.

Puede usarse el azul institucional para titulares y datos de mayor jerarquía.

## 8.8 Colores reducidos

`#bec4c7` y `#9499b0` no deben utilizarse para párrafos pequeños sobre blanco.

Reservarlos para:

- bordes;
- fondos;
- marcas de paginación;
- elementos deshabilitados;
- decoraciones;
- metadatos grandes;
- texto sobre fondos donde exista contraste suficiente.

## 8.9 Combinaciones preferidas

### Fondo blanco

- título: azul institucional;
- párrafo: gris base;
- borde: gris reducido o azul;
- acento: celeste;
- botón primario: azul con texto blanco.

### Fondo marfil

- título: azul institucional;
- párrafo: gris base;
- borde: azul o gris base con baja presencia;
- botón: azul pleno o borde azul.

### Fondo gris claro

- título: azul institucional;
- párrafo: gris base;
- borde: gris reducido;
- acento: celeste puntual.

### Fondo azul institucional

- título: blanco;
- párrafo: gris claro;
- borde: blanco con opacidad controlada;
- acento: celeste;
- botón primario invertido: blanco con texto azul;
- botón secundario: borde blanco.

---

# 9. Tipografía

## 9.1 Familias

### Bellota Text

Uso:

- titulares;
- destacados;
- conceptos;
- frases institucionales;
- nombres de bloques importantes;
- palabras protagonistas.

No usar en:

- navegación;
- botones;
- etiquetas pequeñas;
- formularios;
- párrafos largos;
- metadatos;
- tablas.

Pesos permitidos:

- regular;
- bold.

### Work Sans

Uso:

- texto base;
- navegación;
- botones;
- formularios;
- mensajes funcionales;
- pies de imagen;
- metadatos operativos;
- textos breves de interfaz.

### Source Serif 4

Uso:

- artículos;
- textos editoriales;
- citas textuales;
- párrafos introductorios;
- fechas destacadas;
- años;
- estadísticas;
- cartelería editorial;
- etiquetas de sección;
- extractos de publicaciones;
- contenido académico.

## 9.2 Jerarquía sugerida

| Nivel | Fuente | Tamaño desktop | Tamaño mobile | Interlineado |
|---|---|---:|---:|---:|
| Display XL | Bellota Text | `clamp(72px, 7.5vw, 112px)` | `clamp(42px, 12vw, 56px)` | `0.90–0.96` |
| H1 | Bellota Text | `clamp(64px, 6vw, 96px)` | `40–52px` | `0.94–1` |
| H2 | Bellota Text | `48–72px` | `36–44px` | `0.98–1.05` |
| H3 | Bellota Text | `32–48px` | `28–36px` | `1–1.08` |
| H4 | Bellota Text o Source Serif 4 | `24–32px` | `22–28px` | `1.1` |
| Intro | Source Serif 4 | `24–32px` | `21–26px` | `1.25–1.4` |
| Texto editorial | Source Serif 4 | `20px` | `18px` | `1.55–1.7` |
| Texto base | Work Sans | `17px` | `16px` | `1.5–1.65` |
| Texto pequeño | Work Sans | `14–15px` | `14px` | `1.4–1.55` |
| Etiqueta editorial | Source Serif 4 | `13–16px` | `12–14px` | `1.2–1.4` |

## 9.3 Titulares

Los titulares:

- se alinean a la izquierda;
- pueden tener saltos manuales;
- pueden revelarse por línea o palabra;
- deben tener interlineado cerrado;
- deben mantener una forma legible;
- pueden ocupar entre el 40 % y el 70 % del ancho del contenedor;
- pueden dominar una sección;
- no se colocan sobre fotografías.

No centrar titulares por defecto.

## 9.4 Estándar de titular de sección (obligatorio)

Todo titular de sección de un bloque CMS (EventsListing, Search, EventsHighlight, Cards, ContactForm, etc.) debe componerse en **dos escalas**:

- **Etiqueta de sección**: Source Serif 4, `clamp` entre 24 px y 32 px (`text-2xl`), color gris base sobre superficies claras (`text-gray`) o gris claro sobre azul institucional (`text-gray-3`), alineada a la izquierda, sin guiones, viñetas ni decoraciones previas.
- **Título**: Bellota Text, `font-size: clamp(2.75rem, 5.5vw, 5rem)`, `font-weight: normal`, `line-height: 0.96`, `letter-spacing: -0.035em`, `max-width: 16ch`, `color: azul institucional` sobre superficies claras (`text-primary`) o blanco sobre azul institucional (`text-white`), alineado a la izquierda.

Reglas de aplicación:

- Etiqueta y título separados por 16 px (`mb-4` en la etiqueta).
- No usar `font-bold`, mayúsculas sistemáticas ni centrado en titulares de sección.
- La escala es fluida (clamp), no escalones de breakpoint.
- **Variantes compactas**: banners (CTA) y paneles funcionales (CVDownload) mantienen la misma familia y tratamiento (normal, tracking negativo, izquierda) pero con escala reducida: `clamp(1.75rem, 3vw, 2.75rem)`.
- En superficies azules, invertir únicamente los colores del token (etiqueta `text-gray-3`, título `text-white`); nunca cambiar tipografía, peso ni alineación.
- Los titulares de página (h1 de Home, hero y detalle de contenido) conservan su escala Display propia (§ 9.2), no esta regla.

## 9.5 Saltos manuales

Se permiten en:

- portada;
- aperturas de sección;
- frases institucionales;
- campañas;
- páginas editoriales controladas.

No usarlos en:

- títulos dinámicos;
- tarjetas reutilizables;
- listados;
- contenidos que puedan cambiar desde CMS sin control editorial.

## 9.6 Texto base

Tamaño recomendado en desktop:

```css
font-size: 17px;
line-height: 1.6;
```

No reducir el cuerpo principal a 14 px o 15 px.

## 9.7 Lectura larga

Los textos extensos deben:

- usar Source Serif 4;
- tener un tamaño aproximado de 20 px en desktop;
- tener un ancho máximo de `68ch`;
- utilizar un contenedor reducido;
- mantener espacios claros entre párrafos;
- ofrecer contraste suficiente;
- evitar columnas múltiples para artículos largos.

## 9.8 Introducciones

Los párrafos introductorios pueden usar Source Serif 4 entre 24 px y 32 px.

Se utilizan en:

- apertura biográfica;
- páginas de publicaciones;
- manifiestos;
- textos institucionales;
- introducciones de trayectoria.

## 9.9 Citas

- Declaración institucional o concepto breve: Bellota Text.
- Cita textual, jurídica o académica: Source Serif 4.
- Autor y fuente: Work Sans.
- No usar comillas gigantes decorativas como icono.
- Se permite una línea vertical o borde superior.
- Se permite un monograma pequeño como sello.

## 9.10 Mayúsculas

No utilizar mayúsculas sistemáticamente.

Permitidas para:

- metadatos muy pequeños;
- siglas;
- etiquetas técnicas;
- categorías breves cuando la lectura siga siendo clara.

Las etiquetas editoriales principales pueden permanecer en escritura normal con Source Serif 4.

## 9.11 Subrayados

Se permite subrayar una palabra o fragmento con celeste.

No subrayar títulos completos.

El recurso debe representar:

- énfasis editorial;
- actualidad;
- idea central;
- vínculo visual entre bloques.

---

# 10. Contenedores y grilla

## 10.1 Contenedor principal

Ancho máximo:

```css
max-width: 1440px;
```

Márgenes laterales desktop:

```css
padding-inline: clamp(48px, 4.5vw, 64px);
```

En pantallas mayores, el contenido debe permanecer controlado.

## 10.2 Contenedor reducido

Ancho orientativo:

```css
max-width: 720px;
```

Puede ampliarse hasta:

```css
max-width: 800px;
```

Uso:

- artículos;
- textos largos;
- biografía;
- introducciones;
- citas extensas;
- cuerpo académico;
- secciones que necesiten cambiar el ritmo.

El cambio de ancho debe ser visible y deliberado.

## 10.3 Full width

Se permiten secciones que ocupen todo el viewport para:

- fondos azules;
- fondos marfil;
- galerías;
- fotografías;
- videos;
- separadores editoriales;
- aperturas;
- cierres.

El contenido interno puede volver al contenedor de 1440 px.

## 10.4 Grilla

Usar preferentemente una grilla de 12 columnas en desktop.

Distribuciones frecuentes:

- 8 + 4;
- 7 + 5;
- 5 + 7;
- 9 + 3;
- 6 + 6;
- 4 + 4 + 4;
- 6 + 3 + 3.

No repetir siempre dos columnas iguales.

## 10.5 Asimetría

La asimetría está permitida y recomendada cuando:

- existe una pieza principal;
- una fotografía debe dominar;
- un contenido actual se diferencia de secundarios;
- una fecha o cita funciona como contrapunto;
- se busca ritmo editorial.

La asimetría no debe romper:

- alineaciones de base;
- jerarquía;
- orden de lectura;
- accesibilidad;
- comportamiento responsive.

## 10.6 Alineaciones

Todo elemento debe alinearse al menos con uno de estos ejes:

- borde del contenedor;
- columna de la grilla;
- borde de imagen;
- línea divisoria;
- eje tipográfico;
- bloque anterior o posterior.

Evitar desplazamientos arbitrarios.

---

# 11. Espaciado

## 11.1 Escala base

Usar una escala consistente:

```text
4, 8, 12, 16, 24, 32, 48, 64, 80, 96, 128
```

## 11.2 Separación entre secciones

La separación habitual es compacta:

```text
64–80px
```

Se permiten valores mayores solo para:

- portada;
- grandes cambios temáticos;
- cierre;
- bloques fotográficos;
- pausas editoriales importantes.

## 11.3 Espaciado interno

Referencias:

- bloques compactos: `24–32px`;
- tarjetas estándar: `24–40px`;
- secciones editoriales: `48–80px`;
- bloques hero: según altura y composición;
- botones: altura aproximada de `48px`.

## 11.4 Tarjetas

Las tarjetas deben tener espacio entre sí.

No deben compartir borde como una tabla continua.

El espacio depende del bloque:

- `16px` para listados compactos;
- `24px` como valor frecuente;
- `32px` para piezas con fotografía;
- `48px` cuando cada pieza tenga fuerte autonomía editorial.

## 11.5 Regla de ritmo

No aplicar el mismo padding a todas las secciones.

Alternar:

- bloque amplio;
- bloque reducido;
- superficie plena;
- grilla;
- pausa;
- contenido de lectura.

La variación debe estar dentro de la escala definida.

---

# 12. Secciones y superficies

## 12.1 Tipos de superficie

Se permiten:

1. blanco;
2. marfil;
3. gris claro;
4. azul institucional;
5. fotografía como bloque independiente, sin texto encima.

## 12.2 Alternancia

No alternar fondos de forma mecánica.

Cambiar la superficie cuando exista:

- cambio de capítulo;
- diferencia jerárquica;
- contenido destacado;
- pausa;
- cambio de ritmo;
- cierre;
- cita;
- transición entre actualidad y trayectoria.

## 12.3 Secciones azules

Pueden ocupar todo el ancho.

Uso:

- autoridad;
- cargos;
- frases institucionales;
- hitos;
- llamados a explorar publicaciones;
- cierres;
- navegación;
- timeline;
- cifras;
- pensamiento.

No usar más de dos grandes superficies azules consecutivas sin una pausa blanca, marfil, gris o fotográfica.

## 12.4 Bordes

Los bloques pueden delimitarse con bordes sólidos de 1 px.

El color depende del fondo.

No usar bordes punteados ni discontinuos.

No utilizar bordes de 2 px salvo una excepción funcional claramente justificada.

---

# 13. Fotografía

## 13.1 Rol

La fotografía es uno de los recursos principales de identidad.

Debe mostrar:

- retratos editoriales;
- primeros planos;
- actividad pública;
- conferencias;
- docencia;
- intervenciones en medios;
- espacios institucionales;
- libros y publicaciones;
- contexto profesional.

No limitar la identidad a retratos posados.

## 13.2 Tratamiento

Las fotografías deben conservar:

- color natural;
- saturación moderada;
- contraste controlado;
- piel realista;
- iluminación coherente;
- carácter documental;
- temperatura ligeramente cálida cuando resulte natural.

No aplicar:

- duotono general;
- blanco y negro sistemático;
- filtros azules;
- overlays de color;
- efectos vintage fuertes;
- desenfoques decorativos;
- marcos redondeados.

## 13.3 Formatos

Las relaciones de aspecto son libres según la composición.

Permitidas:

- horizontal panorámica;
- 16:9;
- 3:2;
- 4:3;
- 1:1;
- 4:5;
- 3:4;
- vertical larga;
- recortes editoriales personalizados.

## 13.4 Encuadre

El recorte depende de la jerarquía.

- Hero o retrato principal: preservar presencia, expresión y postura.
- Tarjeta: se permiten recortes más cerrados.
- Actividad pública: priorizar gesto y contexto.
- Primer plano: puede recortar parte del cabello o cuerpo si la composición lo justifica.
- Contenido documental: evitar recortes que eliminen información relevante.

## 13.5 Fotografías y bordes

Las imágenes pueden:

- tocar los límites del bloque;
- ocupar toda una columna;
- extenderse hasta el borde del viewport;
- dominar una tarjeta;
- ocupar casi toda la tarjeta;
- alternarse entre vertical y horizontal.

No necesitan marco.

## 13.6 Texto e imagen

Regla obligatoria:

> **No superponer texto sobre fotografías.**

El texto debe ubicarse:

- al lado;
- arriba;
- debajo;
- en una superficie independiente;
- en una columna separada;
- en un bloque contiguo.

Tampoco colocar cajas de texto encima de la imagen.

## 13.7 Hover y scroll

Se permite:

- zoom suave;
- desplazamiento mínimo;
- revelado por máscara;
- cambio moderado de escala;
- transición de recorte.

No se permite:

- giro;
- inclinación 3D;
- distorsión;
- rebote;
- zoom agresivo;
- desplazamiento que impida reconocer la imagen.

## 13.8 Accesibilidad

Toda fotografía informativa debe tener `alt` descriptivo.

Fotografías puramente decorativas:

```html
alt=""
```

No repetir en el `alt` el mismo texto visible junto a la imagen.

---

# 14. Iconografía

## 14.1 Librería

Usar Lucide.

## 14.2 Rol

La iconografía acompaña. No protagoniza.

Usos permitidos:

- contacto;
- descarga;
- compartir;
- abrir enlace externo;
- reproducir video;
- navegación;
- acordeones;
- formularios;
- indicadores funcionales;
- redes sociales.

## 14.3 Usos prohibidos

No usar iconos como:

- elemento principal de una tarjeta;
- sustituto de una fotografía;
- decoración repetida;
- insignia grande;
- ilustración de conceptos abstractos;
- destacado de beneficios estilo SaaS.

## 14.4 Flechas

Para enlaces editoriales, preferir caracteres tipográficos:

```text
→
←
↗
```

Ejemplo:

```text
Leer publicación →
```

Usar Lucide cuando la acción necesite una affordance funcional más explícita.

## 14.5 Estilo

- trazo regular;
- tamaño coherente con el texto;
- sin contenedor circular por defecto;
- sin fondo redondeado;
- color azul, gris o blanco según superficie.

---

# 15. Bordes, radios y sombras

## 15.1 Bordes redondeados

Valor global:

```css
border-radius: 0;
```

Eliminar:

- `rounded`;
- `rounded-sm`;
- `rounded-md`;
- `rounded-lg`;
- `rounded-xl`;
- `rounded-2xl`;
- `rounded-3xl`;
- `rounded-full`.

Excepción:

- avatares circulares solo si existe una necesidad real de identificación; no son el patrón recomendado para este sitio.

## 15.2 Sombras

No utilizar sombras.

Eliminar:

- `shadow`;
- `shadow-sm`;
- `shadow-md`;
- `shadow-lg`;
- `shadow-xl`;
- `drop-shadow-*`;
- sombras internas decorativas.

## 15.3 Bordes permitidos

- sólidos;
- 1 px;
- rectos;
- visibles;
- dependientes del fondo;
- animables en su longitud.

## 15.4 Elevación

La jerarquía se construye mediante:

- color;
- escala;
- borde;
- espacio;
- composición;
- fotografía;
- tipografía.

Nunca mediante elevación artificial.

---

# 16. Botones y enlaces

## 16.1 Variantes principales

### Botón primario

- fondo azul institucional;
- texto blanco;
- borde azul;
- altura aproximada de 48 px;
- rectangular;
- Work Sans;
- escritura normal;
- sin sombra;
- sin radio.

Hover:

- invertir a fondo blanco o marfil;
- texto azul;
- desplazar flecha si existe.

### Botón secundario

- fondo transparente;
- borde de 1 px;
- texto azul;
- altura aproximada de 48 px;
- rectangular.

Hover:

- fondo azul;
- texto blanco;
- flecha desplazada.

### Sobre fondo azul

Primario invertido:

- fondo blanco;
- texto azul;
- borde blanco.

Secundario:

- fondo transparente;
- texto blanco;
- borde blanco.

## 16.2 Texto

Usar escritura normal.

Ejemplos:

- Ver trayectoria
- Explorar publicaciones
- Leer artículo
- Ver participación
- Descargar CV

No usar mayúsculas completas por defecto.

## 16.3 Flechas

Se permite:

```text
Ver publicación →
```

La flecha puede moverse entre 4 px y 8 px en hover.

## 16.4 Enlaces editoriales

Pueden presentarse como:

- texto con flecha;
- texto subrayado;
- texto con línea superior;
- texto con borde inferior animado.

No convertir cada enlace en botón.

## 16.5 Focus

Todo elemento interactivo debe tener un estado focus visible.

Preferencia:

- outline celeste;
- separación suficiente;
- contraste alto;
- no depender solo del cambio de color.

---

# 17. Tarjetas

## 17.1 Uso

Las tarjetas están permitidas frecuentemente.

Deben sentirse como piezas editoriales, no como widgets.

## 17.2 Construcción

Pueden usar:

- fondo blanco;
- fondo marfil;
- fondo gris;
- fondo azul;
- borde de 1 px;
- fotografía;
- tipografía grande;
- fecha;
- categoría;
- flecha;
- distribución vertical u horizontal.

## 17.3 Imagen

Puede:

- estar arriba;
- estar al costado;
- ocupar gran parte del bloque;
- dominar visualmente;
- tocar bordes;
- cambiar de relación de aspecto.

## 17.4 Espaciado

Las tarjetas deben estar separadas entre sí.

No elevarlas con sombras.

No usar un radio para diferenciarlas del fondo.

## 17.5 Hover

Se permite combinar:

- cambio de fondo;
- cambio de color de texto;
- zoom suave de imagen;
- desplazamiento de flecha;
- movimiento mínimo de contenido;
- dibujo de borde.

No elevar la tarjeta.

No modificar su posición más de unos pocos píxeles.

## 17.6 Tarjeta azul

Una tarjeta azul debe:

- usar texto blanco o gris claro;
- mantener alto contraste;
- usar celeste solo como detalle;
- evitar múltiples iconos;
- evitar bordes internos innecesarios.

## 17.7 Tarjetas de publicaciones

Estructura preferida:

1. categoría o tipo;
2. título;
3. fecha o editorial;
4. extracto opcional;
5. enlace;
6. imagen opcional.

La publicación debe ser el centro, no el icono.

---

# 18. Header y navegación

## 18.1 Comportamiento

El header es fijo.

Estado inicial:

- altura amplia;
- logo sin tagline;
- navegación reducida;
- fondo coherente con la portada;
- presencia editorial.

Estado al hacer scroll:

- reduce altura;
- cambia al monograma;
- mantiene acceso a navegación;
- conserva legibilidad;
- no utiliza sombra;
- puede usar borde inferior de 1 px.

## 18.2 Transición

La reducción debe:

- ser visible;
- ser rápida;
- no rebotar;
- no mover abruptamente el contenido;
- respetar `prefers-reduced-motion`.

## 18.3 Navegación desktop

Usar un menú reducido y un panel desplegable amplio.

El header puede mostrar:

- dos o tres accesos prioritarios;
- botón o enlace “Menú”;
- acción institucional secundaria.

El panel amplio contiene el mapa completo del sitio.

## 18.4 Panel de navegación

Fondo:

```text
Gris claro (`gray-3`, #f3f4f4)
```

Puede incluir:

- nombre completo;
- monograma;
- navegación principal;
- enlaces secundarios;
- publicaciones recientes;
- datos de contacto;
- fotografía, si no compite con la navegación.

No usar fondo negro.

No usar cards redondeadas dentro del panel.

## 18.5 Mobile

- header fijo;
- monograma o logo reducido;
- panel de navegación a pantalla completa o casi completa;
- áreas táctiles mínimas de 44 px;
- scroll interno cuando sea necesario;
- cierre claramente identificable.

## 18.6 Accesibilidad

- navegación por teclado;
- gestión de foco;
- `aria-expanded`;
- `aria-controls`;
- cierre con Escape;
- bloqueo correcto del scroll;
- retorno del foco al disparador.

---

# 19. Footer

## 19.1 Rol

El footer debe cerrar el sitio con autoridad, no actuar como una zona residual.

Puede ser:

- azul institucional;
- marfil;
- blanco con borde superior.

## 19.2 Contenido

- logo o monograma;
- navegación;
- contacto;
- redes;
- créditos;
- datos profesionales;
- publicaciones o enlaces clave;
- información legal.

## 19.3 Composición

Se permiten:

- tipografía grande;
- monograma amplio;
- línea divisoria;
- grilla asimétrica;
- título de cierre;
- fecha o actualización.

No usar múltiples columnas pequeñas indistinguibles.

---

# 20. Recursos editoriales

## 20.1 Texto vertical

Está permitido libremente como recurso editorial.

Puede utilizarse para:

- etiquetas;
- años;
- categorías;
- nombres de capítulo;
- navegación lateral;
- numeración;
- notas.

## 20.2 Reglas de texto vertical

- debe seguir siendo legible;
- no debe contener párrafos;
- no debe ser esencial para comprender el contenido;
- debe volver a horizontal en mobile cuando sea necesario;
- puede tener movimiento diferencial durante scroll;
- debe respetar `prefers-reduced-motion`.

## 20.3 Líneas

Las líneas son un recurso importante.

Pueden:

- separar columnas;
- introducir títulos;
- organizar fechas;
- dibujarse al entrar;
- marcar jerarquías;
- conectar hitos.

## 20.4 Grandes números y fechas

Usar Source Serif 4.

Aplicaciones:

- años;
- número de publicaciones;
- cargos;
- estadísticas;
- décadas;
- hitos.

No tratar cifras como métricas de una startup.

## 20.5 Etiquetas de sección

Usar Source Serif 4.

No son obligatorias en todas las secciones.

Cuando un bloque incluye etiqueta + titular de sección, aplicar la composición estándar de **dos escalas** definida en § 9.4: etiqueta Source Serif 4 a escala `text-2xl` (24–32 px), gris base, sin guiones ni viñetas decorativas, separada 16 px del titular.

Incluirlas cuando:

- ayudan a orientar;
- existe un cambio de capítulo;
- el título es conceptual;
- la jerarquía lo requiere.

Evitar:

```text
ETIQUETA
Título
```

de forma mecánica en cada bloque.

## 20.6 Subrayados y acentos

El celeste puede:

- subrayar una palabra;
- marcar un estado activo;
- señalar un dato;
- iniciar una línea;
- aparecer en una transición.

No usar varios acentos simultáneos en el mismo viewport.

---

# 21. Animación e interacción

## 21.1 Dirección

La animación debe ser editorial y visible.

No debe sentirse:

- lúdica;
- elástica;
- tecnológica;
- futurista;
- ornamental;
- continua.

## 21.2 Entradas

Permitidas:

- fade;
- revelado por máscara;
- aparición por línea;
- aparición por palabra;
- dibujo de bordes;
- desplazamiento muy corto;
- secuencias escalonadas.

## 21.3 Titulares

Pueden aparecer:

- línea por línea;
- palabra por palabra;
- mediante clip o máscara.

No animar letra por letra en textos largos.

## 21.4 Fotografías

Permitido:

- zoom suave en hover;
- zoom vinculado a scroll;
- revelado por máscara;
- cambio leve de encuadre.

Duración orientativa:

```text
500–1000 ms
```

## 21.5 Bordes

Pueden:

- crecer horizontalmente;
- crecer verticalmente;
- aparecer desde un extremo;
- acompañar la entrada del contenido.

## 21.6 Texto vertical

Puede desplazarse a una velocidad distinta del contenido.

El efecto debe ser sutil y no impedir la lectura.

## 21.7 Hover de tarjetas

Combinar con moderación:

- cambio de fondo;
- zoom de imagen;
- desplazamiento de flecha;
- variación de borde;
- cambio tipográfico o cromático.

No utilizar todos los efectos en todas las tarjetas.

## 21.8 Botones

Hover recomendado:

- inversión de color;
- desplazamiento de flecha.

## 21.9 Carruseles

Permitidos según el bloque.

Condiciones:

- control manual;
- sin autoplay;
- navegación clara;
- teclado;
- swipe en mobile;
- indicadores accesibles;
- no ocultar contenido esencial.

Usos posibles:

- publicaciones;
- prensa;
- apariciones;
- testimonios;
- fotografías;
- videos;
- citas.

## 21.10 Video

El video solo comienza por acción del usuario.

Requisitos:

- botón de reproducción;
- poster;
- controles;
- subtítulos cuando corresponda;
- no autoplay;
- no sonido automático;
- no fondo de video decorativo.

## 21.11 Duraciones

Orientativas:

- microinteracción: `150–250 ms`;
- botón o enlace: `200–350 ms`;
- tarjeta: `300–500 ms`;
- entrada de bloque: `500–900 ms`;
- máscara de imagen: `700–1200 ms`.

## 21.12 Easing

Preferir curvas suaves y controladas.

Ejemplo:

```css
cubic-bezier(0.22, 1, 0.36, 1)
```

Evitar:

- `bounce`;
- `elastic`;
- overshoot;
- resortes notorios.

## 21.13 Reducción de movimiento

Obligatorio respetar:

```css
@media (prefers-reduced-motion: reduce)
```

En ese modo:

- eliminar parallax;
- eliminar revelados complejos;
- eliminar zoom por scroll;
- mostrar contenido inmediatamente;
- mantener solo transiciones funcionales breves;
- desactivar smooth scrolling.

---

# 22. Responsive

## 22.1 Principio

Mobile no debe ser una versión encogida del desktop.

Debe preservar:

- jerarquía;
- claridad;
- ritmo;
- autoridad;
- fotografía;
- lectura.

## 22.2 Contenedores mobile

Padding orientativo:

```css
padding-inline: 20px;
```

Puede subir a 24 px en dispositivos amplios.

## 22.3 Titulares

Escala fluida:

```css
font-size: clamp(42px, 12vw, 56px);
```

Evitar que una palabra se corte o desborde.

## 22.4 Grillas

En mobile:

- apilar columnas;
- reordenar por importancia;
- mantener la fotografía cerca del contenido relacionado;
- evitar carruseles como solución automática;
- convertir textos verticales a horizontales cuando sea necesario.

## 22.5 Orden de contenido

La prioridad de lectura debe existir en el DOM, no depender solo de CSS.

Orden recomendado:

1. contexto;
2. título;
3. texto principal;
4. imagen;
5. acciones;
6. contenido secundario.

Puede variar según el bloque, pero debe ser deliberado.

## 22.6 Tarjetas

En mobile:

- pueden ocupar todo el ancho;
- mantener separación;
- evitar padding excesivo;
- imágenes amplias;
- botones de ancho completo solo cuando la acción lo justifique.

## 22.7 Header

Estado reducido desde el inicio o luego de un scroll breve.

El panel debe ser fácil de cerrar y navegar con una mano.

---

# 23. Accesibilidad

## 23.1 Estándar

Objetivo mínimo:

```text
WCAG 2.2 AA
```

## 23.2 Contraste

- texto base `#5c6b73` sobre blanco es adecuado para cuerpo;
- `#bec4c7` no debe usarse para cuerpo sobre blanco;
- celeste no debe utilizarse como texto pequeño sobre blanco;
- blanco y gris claro son adecuados sobre azul institucional;
- verificar cada combinación nueva.

## 23.3 Tipografía

- no reducir el texto base por debajo de 16 px en mobile;
- evitar bloques extensos en mayúsculas;
- mantener altura de línea suficiente;
- limitar textos largos a 68ch;
- permitir zoom del navegador.

## 23.4 Teclado

Todos los controles deben:

- recibir foco;
- tener orden lógico;
- mostrar focus visible;
- funcionar con teclado;
- no depender de hover.

## 23.5 Movimiento

Respetar `prefers-reduced-motion`.

## 23.6 Formularios

- labels visibles;
- errores asociados;
- instrucciones claras;
- no usar placeholder como única etiqueta;
- estados de éxito y error legibles;
- targets de 44 px como mínimo.

## 23.7 Contenido

- estructura correcta de encabezados;
- un solo `h1` por página;
- enlaces descriptivos;
- fechas legibles;
- tablas solo para datos tabulares;
- listas semánticas;
- `blockquote` para citas reales.

---

# 24. Componentes

## 24.1 Hero

Características permitidas:

- titular muy grande;
- grilla asimétrica;
- fotografía independiente;
- fondo blanco, marfil o azul;
- introducción en Source Serif 4;
- botón primario y secundario;
- año o etiqueta vertical;
- borde animado.

No permitido:

- texto sobre foto;
- carrusel automático;
- gradiente;
- múltiples badges;
- iconos de beneficios;
- caja flotante con sombra.

## 24.2 About / Biografía

Preferir:

- fotografía + texto;
- cronología;
- citas;
- hitos;
- cargos;
- publicaciones;
- contenedor reducido para narrativa.

La biografía debe combinar dimensión personal e institucional, sin parecer un “About us” corporativo.

## 24.3 Trayectoria

Patrones útiles:

- timeline;
- años grandes;
- columnas asimétricas;
- acordeones;
- capítulos;
- hitos con fotografía;
- bloques azules.

No convertirla en una lista de logos o badges.

## 24.4 Publicaciones

Permitir:

- grilla;
- listado;
- filtros;
- portada de libro;
- ficha editorial;
- fecha;
- extracto;
- descarga;
- enlace externo.

El diseño debe priorizar título, tipo y fecha.

## 24.5 Actualidad / Prensa

Puede tener mayor ritmo visual:

- piezas de distinto tamaño;
- fotografía;
- video;
- fecha;
- medio;
- categoría;
- carrusel manual;
- grilla asimétrica.

Debe seguir pareciendo institucional.

## 24.6 Citas

- gran escala;
- Source Serif 4 o Bellota Text según tipo;
- borde o línea;
- fondo marfil, blanco o azul;
- atribución clara;
- sin comillas decorativas gigantes.

## 24.7 Estadísticas

Usar solo cuando sean relevantes.

- número en Source Serif 4;
- explicación en Work Sans;
- borde;
- sin icono grande;
- sin estilo de dashboard;
- sin contador animado obligatorio.

## 24.8 FAQ

Puede usar acordeón.

- borde superior e inferior;
- icono pequeño;
- sin tarjetas redondeadas;
- animación breve;
- pregunta legible;
- navegación por teclado.

## 24.9 Testimonios

Usar con moderación.

Pueden adoptar formato:

- cita editorial;
- referencia institucional;
- recomendación profesional;
- extracto de prensa.

Evitar carrusel automático y retratos circulares genéricos.

## 24.10 Galerías

- grilla asimétrica;
- relaciones libres;
- lightbox accesible;
- sin bordes redondeados;
- sin texto encima;
- pie de foto opcional;
- navegación manual.

## 24.11 Formularios

- campos rectangulares;
- borde de 1 px;
- fondo blanco o gris claro;
- altura cómoda;
- foco celeste o azul;
- etiquetas visibles;
- mensajes claros.

## 24.12 CTA

Debe ser institucional.

Ejemplos:

- Explorar publicaciones
- Consultar trayectoria
- Ver intervenciones
- Acceder a una conferencia
- Contactar

No usar urgencia artificial ni lenguaje comercial agresivo.

## 24.13 Vistas previas de conferencias y exposiciones (estándar)

Estándar obligatorio de presentación para las vistas previas del bloque **Conferencias y exposiciones** (EventsListing en modo vídeo) y para cualquier catálogo de intervenciones audiovisuales (conferencias, exposiciones, entrevistas).

### Superficie y cabecera

- Sección sobre gris claro (`surface-light` / `bg-gray-3`) delimitada con borde superior e inferior de 1 px en `border-light`.
- Cabecera estándar de dos escalas (§ 9.4): etiqueta Source Serif 4 (`text-2xl`, gris base) y título Bellota Text `clamp(2.75rem, 5.5vw, 5rem)`, azul institucional, alineado a la izquierda.
- Descripción opcional a la derecha en Source Serif 4 (`text-xl`, gris base, `max-width` 46–48ch).

### Carrusel

- Carrusel de desplazamiento manual, **sin autoplay**, sin indicadores automáticos.
- `slides-per-view: auto` con separación de 24 px entre diapositivas.
- La primera diapositiva puede presentarse con más presencia visual (mayor ancho).
- Navegación con botones prev/next cuadrados de 48 px: fondo blanco, borde azul institucional de 1 px, chevron de Lucide; en hover invierten (fondo azul, chevron blanco); foco celeste visible.
- En mobile, swipe manual; la tarjeta ocupa el ancho útil del viewport y la navegación sigue disponible.

### Anatomía de la tarjeta de vista previa (obligatoria)

Cada vista previa es una tarjeta editorial rectangular:

1. **Contenedor**: `<article>` con borde de 1 px azul institucional, fondo blanco, sin radio, sin sombra, altura completa dentro de la diapositiva.
2. **Barra superior**: borde inferior de 1 px azul institucional; índice numerado en Source Serif 4 (escala pequeña, azul institucional) a la izquierda; tipo de actividad e institución en Work Sans (escala pequeña, gris base) a la derecha.
3. **Poster**: relación de aspecto 16:9 (`aspect-video`), `object-cover`, color natural; en hover zoom suave moderado (≈ 1.02, 400–600 ms) con `prefers-reduced-motion`; el enlace abre en pestaña nueva (`target="_blank"`).
4. **Cuerpo** (padding 20 px): fecha en Source Serif 4 (escala pequeña, gris base); título en Bellota Text `clamp(1.15rem, 1.35vw, 1.4rem)`, peso normal, `line-height` ≈ 1.12, `letter-spacing: -0.02em`, azul institucional; acción anclada al pie.
5. **Acción**: botón secundario compacto ("Reproducir" + flecha `→`, escritura normal), borde azul de 1 px, texto azul, altura mínima 44 px; en hover invierte (fondo azul, texto blanco) y la flecha se desplaza 4 px.

### Reglas invariables

- Sin texto superpuesto sobre la fotografía.
- Sin autoplay ni reproducción automática.
- Sin radio, sin sombra, sin hover que eleve la tarjeta.
- `alt=""` en posters decorativos (el título visible y el enlace ya identifican el contenido).
- Foco celeste visible en todos los enlaces, botones y controles.
- Índice numerado solo si aporta orden editorial; no numerar de forma mecánica en otros bloques.

---

# 25. Tokens técnicos

## 25.1 Variables CSS

```css
:root {
  --color-brand-primary: #2a3461;
  --color-brand-primary-muted: #9499b0;
  --color-brand-accent: #45bfe3;

  --color-text-base: #5c6b73;
  --color-text-muted: #bec4c7;

  --color-surface-white: #ffffff;
  --color-surface-light: #f3f4f4;
  --color-surface-ivory: #f5f0e8;

  --color-border-light: #bec4c7;
  --color-on-primary: #ffffff;
  --color-on-primary-soft: #f3f4f4;

  --font-display: "Bellota Text", serif;
  --font-body: "Work Sans", sans-serif;
  --font-editorial: "Source Serif 4", serif;

  --container-main: 1440px;
  --container-reading: 760px;

  --page-padding-mobile: 20px;
  --page-padding-desktop: clamp(48px, 4.5vw, 64px);

  --section-space-compact: 64px;
  --section-space-default: 80px;
  --section-space-large: 96px;

  --border-width: 1px;
  --radius: 0px;

  --duration-fast: 200ms;
  --duration-medium: 400ms;
  --duration-slow: 800ms;

  --ease-editorial: cubic-bezier(0.22, 1, 0.36, 1);
}
```

## 25.2 Utilidades conceptuales

El proyecto debería contar con equivalentes a:

```text
container-main
container-reading
section-space
surface-white
surface-light
surface-ivory
surface-primary
font-display
font-body
font-editorial
border-editorial
link-editorial
button-primary
button-secondary
motion-reveal
motion-line
```

## 25.3 Contenedores

```css
.container-main {
  width: min(100%, var(--container-main));
  margin-inline: auto;
  padding-inline: var(--page-padding-mobile);
}

.container-reading {
  width: min(100%, var(--container-reading));
  margin-inline: auto;
  padding-inline: var(--page-padding-mobile);
}

@media (min-width: 1024px) {
  .container-main {
    padding-inline: var(--page-padding-desktop);
  }

  .container-reading {
    padding-inline: 0;
  }
}
```

## 25.4 Display

```css
.text-display {
  font-family: var(--font-display);
  font-size: clamp(4.5rem, 7.5vw, 7rem);
  font-weight: 400;
  line-height: 0.94;
  letter-spacing: -0.035em;
}
```

## 25.5 Texto editorial

```css
.text-editorial {
  max-width: 68ch;
  font-family: var(--font-editorial);
  font-size: 1.25rem;
  line-height: 1.65;
}
```

## 25.6 Botón base

```css
.button {
  min-height: 48px;
  display: inline-flex;
  align-items: center;
  justify-content: center;
  gap: 0.75rem;
  padding-inline: 1.5rem;
  border: 1px solid currentColor;
  border-radius: 0;
  font-family: var(--font-body);
  transition:
    color var(--duration-fast) var(--ease-editorial),
    background-color var(--duration-fast) var(--ease-editorial),
    border-color var(--duration-fast) var(--ease-editorial);
}
```

## 25.7 Reducción de movimiento

```css
@media (prefers-reduced-motion: reduce) {
  *,
  *::before,
  *::after {
    scroll-behavior: auto !important;
    animation-duration: 0.01ms !important;
    animation-iteration-count: 1 !important;
    transition-duration: 0.01ms !important;
  }
}
```

---

# 26. Mapeo orientativo a Tailwind

La implementación exacta depende de la versión del proyecto. Centralizar los valores en el tema y evitar hexadecimales repetidos en los componentes.

## 26.1 Colores

```text
brand-primary
brand-primary-muted
brand-accent
text-base
text-muted
surface-white
surface-light
surface-ivory
border-light
```

## 26.2 Clases a buscar y reemplazar

### Radios

Buscar:

```text
rounded
rounded-*
```

Reemplazar por:

```text
rounded-none
```

o eliminar.

### Sombras

Buscar:

```text
shadow
shadow-*
drop-shadow-*
```

Eliminar.

### Fondos negros

Buscar:

```text
bg-black
bg-neutral-950
bg-zinc-950
bg-slate-950
bg-gray-950
```

Evaluar y reemplazar generalmente por:

```text
bg-brand-primary
```

### Tipografía

Reemplazar fuentes genéricas del bloque por:

```text
font-display
font-body
font-editorial
```

### Pills

Buscar combinaciones como:

```text
rounded-full
px-3
py-1
text-xs
```

Transformar en etiqueta editorial sin cápsula.

### Overlays

Buscar:

```text
absolute inset-0
bg-black/*
from-black
to-transparent
```

Si sostienen texto sobre foto, reestructurar el bloque. No limitarse a quitar el overlay.

---

# 27. Procedimiento para adaptar un bloque

## Paso 1 — Definir la función

Antes de copiar un bloque, identificar:

- qué contenido resuelve;
- qué jerarquía necesita;
- qué acción permite;
- qué parte debe ser protagonista;
- si necesita fotografía;
- si necesita contenedor amplio o reducido.

## Paso 2 — Evaluar la estructura

Conservar solo si ayuda:

- columnas;
- orden;
- interacción;
- responsive;
- semántica.

## Paso 3 — Neutralizar el diseño original

Eliminar:

- colores;
- tipografías;
- sombras;
- radios;
- gradientes;
- decoraciones;
- iconografía protagonista;
- overlays;
- animaciones;
- modo oscuro;
- contenido ficticio.

## Paso 4 — Aplicar tokens

Sustituir valores directos por tokens del sistema.

No dejar:

- hexadecimales arbitrarios;
- fuentes no aprobadas;
- tamaños improvisados;
- radios;
- sombras.

## Paso 5 — Rehacer la jerarquía

Ajustar:

- tamaño de título;
- ancho de texto;
- relación imagen/texto;
- fondo;
- bordes;
- espacio;
- orden mobile;
- etiqueta editorial.

## Paso 6 — Integrar fotografía

Verificar:

- color natural;
- recorte adecuado;
- ausencia de texto encima;
- relación con la grilla;
- `alt`;
- comportamiento responsive.

## Paso 7 — Aplicar interacción

Elegir solo los efectos que mejoren el bloque:

- fade;
- máscara;
- línea;
- zoom suave;
- flecha;
- inversión de color.

## Paso 8 — Revisar accesibilidad

- contraste;
- foco;
- teclado;
- semántica;
- motion reduce;
- encabezados;
- alt;
- tamaño táctil.

## Paso 9 — Revisar coherencia

Comparar con al menos dos bloques existentes.

El nuevo bloque debe compartir:

- tokens;
- alineaciones;
- tipografía;
- bordes;
- motion;
- densidad;
- comportamiento responsive.

## Paso 10 — Eliminar restos de plantilla

Revisar visualmente y en código que no queden:

- colores originales;
- copy genérico;
- clases sin uso;
- iconos innecesarios;
- estilos dark;
- radios ocultos;
- sombras en hover;
- gradientes;
- overlays.

---

# 28. Creación de bloques nuevos

Cuando no exista una estructura adecuada en OpenTailwind:

1. definir objetivo;
2. seleccionar contenedor;
3. seleccionar superficie;
4. definir jerarquía tipográfica;
5. elegir grilla;
6. asignar fotografía;
7. definir bordes;
8. agregar acciones;
9. definir responsive;
10. definir motion;
11. validar accesibilidad.

No copiar la estética de un bloque cercano solo para acelerar.

## 28.1 Defaults

Si no existe una regla específica:

- fondo: blanco;
- título: Bellota Text en azul;
- párrafo: Work Sans en gris;
- borde: 1 px gris reducido;
- contenedor: 1440 px;
- sección: 64–80 px vertical;
- botón: azul rectangular de 48 px;
- imagen: color natural;
- animación: fade;
- radio: 0;
- sombra: ninguna.

## 28.2 Decisiones que requieren consulta

La LLM no debe decidir por sí sola:

- agregar un nuevo color;
- cambiar una tipografía;
- usar negro;
- usar degradados;
- superponer texto sobre foto;
- introducir un nuevo estilo de botón;
- crear un nuevo tipo de logo;
- cambiar el comportamiento global del header;
- usar autoplay;
- alterar la personalidad de la marca;
- reemplazar una regla obligatoria.

---

# 29. Patrones recomendados

## 29.1 Apertura editorial

- fondo blanco;
- título Bellota Text XL;
- introducción Source Serif 4;
- fotografía en columna contigua;
- etiqueta vertical;
- borde inferior;
- CTA rectangular.

## 29.2 Capítulo institucional

- fondo azul;
- año o concepto en Source Serif 4;
- título blanco;
- párrafo gris claro;
- línea celeste breve;
- imagen en bloque separado.

## 29.3 Listado de publicaciones

- fondo marfil;
- grilla con separación;
- tarjetas blancas o transparentes;
- títulos en Bellota o Source Serif según jerarquía;
- fecha en Source Serif;
- enlace con flecha;
- borde de 1 px.

## 29.4 Actualidad destacada

- pieza principal 8 columnas;
- piezas secundarias 4 columnas;
- fotos sin texto encima;
- fechas;
- bordes;
- hover editorial;
- fondo blanco.

## 29.5 Trayectoria

- años verticales o grandes;
- línea;
- hitos;
- alternancia de ancho;
- fotografías;
- bloques azules puntuales;
- movimiento de borde.

## 29.6 Cita de pensamiento

- contenedor reducido;
- Source Serif 4 o Bellota Text;
- fondo marfil;
- borde izquierdo;
- atribución;
- amplio espacio.

---

# 30. Patrones prohibidos

- Hero con foto de fondo y texto encima.
- Cards redondeadas con sombra.
- Bento grid de cápsulas.
- Fondo negro.
- Neón.
- Degradados tecnológicos.
- Botones píldora.
- Iconos grandes dentro de círculos.
- Cards que se elevan.
- Testimonios con autoplay.
- Texto gris muy claro.
- Tres o más tipografías en un mismo bloque.
- Títulos centrados por defecto.
- Etiqueta en mayúsculas sobre cada título.
- Secciones alternadas mecánicamente.
- Monograma repetido como textura.
- Animación en cada palabra de cada párrafo.
- Scroll horizontal obligatorio.
- Cursor personalizado.
- Video automático.
- Glassmorphism.
- Parallax agresivo.
- Copy comercial genérico.
- Stock photos jurídicas estereotipadas.

---

# 31. Checklist de validación visual

Antes de aprobar un bloque, responder:

## Identidad

- [ ] ¿Se percibe institucional y editorial?
- [ ] ¿La jerarquía principal es clara?
- [ ] ¿El bloque parece propio de Marcela Basterra?
- [ ] ¿Se evitó la estética jurídica genérica?
- [ ] ¿Se evitó la estética SaaS?

## Color

- [ ] ¿Solo usa tokens aprobados?
- [ ] ¿El celeste es un acento?
- [ ] ¿No hay negro?
- [ ] ¿El texto tiene contraste suficiente?
- [ ] ¿El fondo está justificado por la jerarquía?

## Tipografía

- [ ] ¿Bellota Text está limitada a titulares y destacados?
- [ ] ¿Work Sans resuelve la interfaz?
- [ ] ¿Source Serif 4 resuelve el contenido editorial?
- [ ] ¿El título está alineado a la izquierda?
- [ ] ¿El texto largo no supera 68ch?

## Estructura

- [ ] ¿Usa el contenedor correcto?
- [ ] ¿La asimetría mantiene alineaciones?
- [ ] ¿El espaciado pertenece a la escala?
- [ ] ¿Las tarjetas tienen separación?
- [ ] ¿No hay elementos flotantes?

## Fotografía

- [ ] ¿La foto conserva color natural?
- [ ] ¿El recorte es adecuado?
- [ ] ¿No hay texto encima?
- [ ] ¿No hay overlay?
- [ ] ¿Tiene `alt` correcto?

## UI

- [ ] ¿No hay sombras?
- [ ] ¿No hay bordes redondeados?
- [ ] ¿Los bordes son sólidos de 1 px?
- [ ] ¿Los botones tienen 48 px aproximadamente?
- [ ] ¿Los iconos acompañan y no protagonizan?

## Interacción

- [ ] ¿La animación tiene una función?
- [ ] ¿No hay autoplay?
- [ ] ¿No hay rebote ni elasticidad?
- [ ] ¿El hover no eleva tarjetas?
- [ ] ¿Se respeta `prefers-reduced-motion`?

## Vistas previas de conferencias (estándar § 24.13)

- [ ] ¿Superficie gris claro con bordes de 1 px?
- [ ] ¿Tarjeta rectangular con borde azul y fondo blanco?
- [ ] ¿Poster 16:9 con zoom suave y sin texto encima?
- [ ] ¿Carrusel manual sin autoplay con botones de 48 px?
- [ ] ¿Acción "Reproducir →" con hover invertido?

## Accesibilidad

- [ ] ¿Funciona con teclado?
- [ ] ¿El focus es visible?
- [ ] ¿La semántica es correcta?
- [ ] ¿Los controles tienen nombre accesible?
- [ ] ¿El orden mobile coincide con la lectura?

---

# 32. Checklist específico de OpenTailwind

Luego de copiar un bloque:

- [ ] Eliminar `rounded-*`.
- [ ] Eliminar `shadow-*`.
- [ ] Eliminar fondos negros.
- [ ] Eliminar gradientes.
- [ ] Eliminar modo oscuro no solicitado.
- [ ] Reemplazar fuentes.
- [ ] Reemplazar colores.
- [ ] Revisar paddings.
- [ ] Ajustar contenedor a 1440 px o lectura.
- [ ] Separar texto de fotografías.
- [ ] Eliminar overlays.
- [ ] Reemplazar iconos decorativos.
- [ ] Revisar botones.
- [ ] Rehacer hover.
- [ ] Rehacer motion.
- [ ] Quitar autoplay.
- [ ] Revisar ARIA.
- [ ] Reemplazar copy.
- [ ] Comprobar mobile.
- [ ] Confirmar que no parezca la plantilla original.

---

# 33. Instrucción maestra para una LLM agéntica

Al trabajar sobre este proyecto:

> Utilizá OpenTailwind únicamente como base estructural. Antes de implementar cualquier bloque, neutralizá su identidad visual original y aplicá los tokens, tipografías, reglas de composición, fotografía, interacción y accesibilidad definidos en este documento. No agregues sombras, bordes redondeados, fondos negros, texto sobre fotografías, autoplay, gradientes ni iconografía protagonista. Priorizá autoridad institucional, lectura editorial contemporánea, grillas asimétricas, fotografía natural y una jerarquía tipográfica fuerte. Cuando una decisión estética importante no esté definida, consultá antes de inventarla.

---

# 34. Resumen obligatorio

## Sí

- Editorial digital contemporáneo.
- Autoridad institucional.
- Bellota Text en titulares.
- Work Sans en interfaz.
- Source Serif 4 en contenido editorial.
- Azul institucional.
- Blanco general.
- Marfil frecuente.
- Gris claro secundario.
- Celeste como acento.
- Bordes de 1 px.
- Grillas asimétricas.
- Titulares grandes.
- Fotografías naturales.
- Tarjetas separadas.
- Header fijo y reducido.
- Menú amplio marfil.
- Animación editorial.
- Carruseles manuales.
- Vistas previas de conferencias en carrusel de tarjetas editoriales (borde azul, poster 16:9, índice editorial, botón "Reproducir").
- Video por acción del usuario.
- Texto vertical.
- Contenedores de 1440 px y 720–800 px.

## No

- Sombras.
- Bordes redondeados.
- Negro.
- Texto sobre fotos.
- Overlays.
- Gradientes.
- Cards flotantes.
- Autoplay.
- Cursor personalizado.
- Iconos protagonistas.
- Animación elástica.
- Estética SaaS.
- Estética jurídica genérica.
- Heredar la identidad visual de OpenTailwind.
