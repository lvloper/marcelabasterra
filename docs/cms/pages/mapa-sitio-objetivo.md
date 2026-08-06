# Mapa actual del sitio

**Estado:** implementado

**Actualizado:** 22 de julio de 2026

**Regla visual:** toda modificación debe respetar `DESIGN.md`.

## Arquitectura pública

```text
/
├── /sobre-mi
│   └── /trayectoria
├── /actividad-academica
├── /programas
├── /jornadas-y-congresos
├── /publicaciones
│   ├── /publicaciones/libros
│   └── /publicaciones/articulos-academicos
├── /actualidad-y-produccion-academica
└── /contacto
```

Rutas auxiliares que no forman parte de la navegación principal:

```text
/novedades
/error-404
/muestra-bloques        # demostración técnica; no publicar en sitemap
```

Las fichas de libros, artículos, docencias, instituciones, conferencias y otras entidades reutilizables se resuelven mediante sus recursos CMS ruteables y no se enumeran individualmente en este mapa.

## Menú principal vigente

1. **Home** → `/`
2. **Sobre mí** → `/sobre-mi`
   - Biografía
   - Trayectoria → `/trayectoria`
   - Cargos institucionales
   - CV
3. **Actividad Académica** → `/actividad-academica`
   - Programas → `/programas`
   - Jornadas y Congresos → `/jornadas-y-congresos`
4. **Publicaciones** → `/publicaciones`
   - Libros
   - Artículos académicos
   - Actualidad y Producción Académica → `/actualidad-y-produccion-academica`
5. **Contacto** → `/contacto`

## Composición actual por página

### Inicio `/`

1. `Hero`: identidad y presentación principal.
2. `Intro`: síntesis biográfica.
3. `PublicationsHighlight`: último libro desde el CMS.
4. `PressFeed`: actualidad y publicaciones recientes.
5. `EventsListing`: conferencias y exposiciones.
6. `CTA`: invitaciones académicas, institucionales y de prensa.

### Sobre mí `/sobre-mi`

1. `Intro`: perfil profesional.
2. `ContentList`: trayectoria en cifras.
3. `ContentList`: responsabilidades institucionales consultadas desde `CargoInstitucional`.
4. `MediaText`: reconocimiento como Personalidad Destacada en Ciencias Jurídicas.
5. `CVAccess`: acceso a currículum vitae.

### Trayectoria `/trayectoria`

1. `Cards`: cargos institucionales y accesos relacionados.

### Docencia y Actividad Académica `/actividad-academica`

1. `Hero`: apertura editorial de la trayectoria académica.
2. `TeachingListing`: índice desplegable con 13 actividades administradas desde `Docencia` y 7 entidades `InstitucionAcademica`.
   - Resumen por Posgrados, Maestrías y Doctorados.
   - Separación entre universidades nacionales e internacionales.
   - Detalle plegable por institución.
   - Material para alumnos en lateral sticky.
   - Grilla de marcas institucionales.
3. `ContentList`: **Artículos especializados** desde `ArticuloAcademico`.
   - 12 artículos iniciales.
   - Carga incremental mediante el componente Livewire `AcademicArticles`.
   - Botón `Ver más`, sin recargar ni modificar la URL.

### Programas `/programas`

1. `FeaturedResources`: programas académicos administrados desde el CMS.

### Jornadas y Congresos `/jornadas-y-congresos`

1. `Hero`: apertura de la sección.
2. `EventsHighlight`: actividad destacada automática.
3. `EventsListing`: agenda futura y archivo de eventos realizados.

### Publicaciones `/publicaciones`

1. `PublicationsHighlight`: publicaciones principales.
2. `FeaturedResources`: recursos editoriales destacados.
3. Fichas ruteables de `Libro` y `ArticuloAcademico`.

### Libros `/publicaciones/libros`

- Archivo ruteable de libros.
- 12 registros actuales administrados desde `Libro`.
- Cada ficha puede incluir autoría, editorial, fecha, temática, portada y descripción.

### Artículos académicos `/publicaciones/articulos-academicos`

- Archivo ruteable de artículos académicos.
- 103 registros actuales administrados desde `ArticuloAcademico`.
- Enlaces internos o documentos externos según disponibilidad.

### Actualidad y Producción Académica `/actualidad-y-produccion-academica`

1. `Hero`: apertura editorial.
2. `Search`: búsqueda unificada de publicaciones, actividades e intervenciones.
3. `FeaturedResources`: contenido destacado automático.
4. `ContentList`: **Publicaciones**, fuente `academic_publications`.
   - Catálogo combinado de 115 libros y artículos.
   - 10 publicaciones iniciales.
   - Reutiliza el componente Livewire `AcademicArticles` con fuente `publications`.
   - Botón `Ver más`, sin recargar ni modificar la URL.
5. `PressFeed`: Noticias y Medios desde el catálogo unificado.
6. `EventsListing`: Conferencias y Actividades.
7. `PublicationsHighlight`: Biblioteca Digital.
8. `EventsListing`: Videos.

### Contacto `/contacto`

1. `ContactForm`: formulario principal.
2. `Cards`: información de contacto.
3. `CTA`: invitaciones y conferencias.

## Fuentes CMS reutilizables

| Dominio | Recurso principal | Uso actual |
|---|---|---|
| Docencia | `Docencia` | 13 actividades de posgrado, maestría y doctorado |
| Instituciones | `InstitucionAcademica` | 7 universidades y organizaciones vinculadas |
| Artículos | `ArticuloAcademico` | 103 artículos especializados/académicos |
| Libros | `Libro` | 12 libros |
| Producción unificada | `AcademicProductionCatalog` | Libros, artículos, noticias, prensa, conferencias y videos |
| Cargos | `CargoInstitucional` | Responsabilidades institucionales reutilizadas en Sobre mí |
| Actividades | `Evento` y `Conferencia` | Jornadas, agenda, conferencias y piezas audiovisuales |

## Reglas vigentes

- No duplicar libros, artículos, docencias, instituciones, cargos o eventos dentro de los bloques.
- Los bloques consultan las entidades CMS ruteables correspondientes.
- `AcademicArticles` es el componente Livewire compartido por Artículos especializados y Publicaciones.
- Artículos especializados carga tandas de 12; Publicaciones carga tandas de 10.
- Artículos académicos y noticias/medios mantienen semánticas diferenciadas.
- Las páginas demo y técnicas no deben aparecer en el sitemap público.
- Todo bloque nuevo o rediseño debe respetar `DESIGN.md` y WCAG 2.2 AA.
