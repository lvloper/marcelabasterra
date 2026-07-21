# Arquitectura objetivo del sitio

**Estado:** objetivo aprobado como base de implementación  
**Fuente:** `mapa-sitio-marcelabasterra-ajustado.md`  
**Regla visual:** toda futura maquetación debe respetar `DESIGN.md`.

## Árbol de rutas

```text
/
/sobre-mi
/actividad-academica
├── /actividad-academica/docencia
├── /actividad-academica/grado-uba
├── /actividad-academica/posgrado
├── /actividad-academica/doctorado
├── /actividad-academica/programas
├── /actividad-academica/jornadas-y-congresos
└── /actividad-academica/conferencias
/publicaciones
├── /publicaciones/libros
└── /publicaciones/articulos-academicos
/prensa
├── /prensa/articulos-en-medios
├── /prensa/entrevistas
└── /prensa/noticias
/cv
/contacto
```

## Menú principal final

1. Inicio
2. Sobre mí
3. Actividad académica
4. Publicaciones
5. Prensa y actualidad
6. CV
7. Contacto

Los hijos de Actividad académica, Publicaciones y Prensa se presentan como navegación secundaria. CV completo y CV reducido son acciones dentro de `/cv`, no páginas principales independientes salvo que la implementación de visualización lo requiera.

## Composición objetivo por página

### Inicio `/`

1. `Hero`: identidad, especialidades, síntesis, retrato, trayectoria y CV.
2. `ContentList`: dos cargos actuales destacados.
3. `FeaturedResources`: libro más reciente de 2025.
4. `MediaText`: reconocimiento Personalidad Destacada, imagen/video y enlace.
5. `PressFeed`: 3 a 6 contenidos recientes combinados.
6. `EventsListing`: próximas actividades; ocultar si está vacío y ofrecer realizadas como fallback editorial.
7. `CVAccess`: CV completo y reducido.
8. `CTA`: invitaciones académicas, institucionales y de prensa.

### Sobre mí `/sobre-mi`

1. `BiographySummary`: presentación profesional.
2. `Timeline`: trayectoria e hitos.
3. `ContentList`: cargos académicos e institucionales actuales.
4. `MediaText`: reconocimiento destacado.
5. `CVAccess`: ambos CV.

### Actividad académica `/actividad-academica`

1. `Text`: introducción.
2. `Cards`: accesos a Docencia, Grado UBA, Posgrado, Doctorado, Programas, Jornadas y Conferencias.

### Docencia `/actividad-academica/docencia`

1. `Text`: Profesora Titular de Derecho Constitucional, Facultad de Derecho, UBA.
2. `TeachingListing`: actividad docente vigente, sin UCA, UCES ni Equipo docente.

### Grado UBA `/actividad-academica/grado-uba`

1. `Text` o `Hero`: encabezado de Elementos de Derecho Constitucional.
2. `TeachingListing`: ficha institucional de grado filtrada a UBA.
3. `Text`: presentación de la cátedra.
4. `ProgramsListing`: programas relacionados.
5. `Cards`: materiales o enlaces, sólo si existen.

### Posgrado `/actividad-academica/posgrado`

1. `Text`: introducción.
2. `TeachingListing`: materias/programas de nivel posgrado; comenzar por Universidad Austral.

### Doctorado `/actividad-academica/doctorado`

1. `Text`: introducción.
2. `TeachingListing`: actividades de doctorado UBA y Universidad de Mendoza.

### Programas `/actividad-academica/programas`

1. `Text`: encabezado e introducción.
2. `ProgramsListing`: vigentes.
3. `ProgramsListing`: históricos, si existen.

### Jornadas y congresos `/actividad-academica/jornadas-y-congresos`

1. `Text`: introducción.
2. `EventsListing`: próximos, ascendente.
3. `EventsListing`: realizados, descendente.

### Conferencias `/actividad-academica/conferencias`

1. `Text`: introducción.
2. `EventsListing`: tipo conferencia/exposición/panel/clase y estado realizado.
3. `Media` o `MediaText`: sólo para piezas audiovisuales que necesiten destaque manual.

### Publicaciones `/publicaciones`

1. `Text`: introducción.
2. `FeaturedResources`: libro 2025.
3. `Cards`: accesos a Libros y Artículos académicos.

### Libros `/publicaciones/libros`

1. `Text`: introducción.
2. `PublicationsHighlight`: libros ordenados del más reciente al más antiguo.
3. Fichas ruteables de `Libro` con portada, sinopsis, tomos, ISBN y enlaces.

### Artículos académicos `/publicaciones/articulos-academicos`

1. `Text`: introducción.
2. `PublicationsHighlight`: artículos ordenados por fecha.
3. Filtros por año, área temática y tipo, implementados en el listado.

### Prensa `/prensa`

1. `Text`: introducción.
2. `PressFeed`: últimas publicaciones combinadas y filtros por tipo/medio.
3. `CTA` o `FeaturedResources`: dossier, sólo si se mantiene.

### Artículos en medios `/prensa/articulos-en-medios`

1. `Text`: introducción.
2. `PressFeed`: filtro fijo `articulo`.

### Entrevistas `/prensa/entrevistas`

1. `Text`: introducción.
2. `PressFeed`: filtro fijo `entrevista`.

### Noticias `/prensa/noticias`

1. `Text`: introducción.
2. `PressFeed`: filtro fijo `noticia`.

### CV `/cv`

1. `Text`: explica versiones y usos.
2. `CVAccess`: CV completo y reducido, cada uno con ver, descargar y fecha de actualización.

### Contacto `/contacto`

1. `Text`: invitaciones académicas, jornadas, consultas institucionales, entrevistas y libros.
2. `ContactForm`: nombre, institución/medio, email, motivo, mensaje y consentimiento.
3. `Cards`: datos de contacto y redes profesionales confirmadas.

## Reglas editoriales de aceptación

- No quedan referencias vigentes a UCA o UCES en actividad de grado/docencia.
- No existe el título ni ítem independiente Equipo docente.
- El libro 2025 aparece primero.
- Los cargos 2025–2027 y Consejo Directivo UBA aparecen en Home y Sobre mí.
- Artículos académicos y artículos en medios nunca se mezclan semánticamente.
- Entrevistas y noticias son categorías diferenciadas.
- La agenda futura no existe como sección principal duplicada.
- CV aparece en el primer nivel del menú.
- Las páginas técnicas o demo no aparecen en navegación ni sitemap público.
