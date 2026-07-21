# Comparación del mapa de sitio: estado actual vs. objetivo

**Fecha del relevamiento:** 2026-07-20  
**Fuente objetivo:** `/home/leveloper/Downloads/mapa-sitio-marcelabasterra-ajustado.md`  
**Fuente actual:** rutas, menú `header`, páginas y bloques persistidos en la base local; clases de `app/Filament/Blocks`; documentación de `docs/cms/blocks`.

## Resumen ejecutivo

El CMS ya tiene una base reutilizable importante: 17 bloques con clase PHP, 16 registrados, y recursos administrables para libros, artículos académicos, entrevistas, eventos, docencia, programas, cargos institucionales y dossiers. La brecha está en cuatro frentes:

1. La navegación actual es plana y no coincide con la jerarquía final.
2. Algunas páginas objetivo todavía no existen y otras deben reubicarse, renombrarse o retirarse del menú.
3. Los recursos existentes no cubren completamente el contrato editorial nuevo, en especial prensa, actividades, CV y cargos.
4. Faltan seis capacidades de bloque para listar o presentar esos recursos sin cargar tarjetas manualmente.

No corresponde crear un bloque distinto por cada sección del mapa. Hero, texto, multimedia, CTA, publicación destacada, biografía, trayectoria, contacto y navegación por tarjetas ya pueden componerse con bloques existentes.

## Navegación principal

| Objetivo | Estado actual | Brecha / acción |
|---|---|---|
| Inicio | `Home` | Renombrar label a `Inicio`; conservar `/`. |
| Sobre mí | Existe `/sobre-mi` | Conservar y recomponer contenidos. |
| Actividad académica | Existe `/actividad-academica` | Convertir en padre real de siete subsecciones. |
| Publicaciones | Existe `/publicaciones` | Convertir en padre de Libros y Artículos académicos. |
| Prensa y actualidad | Actualmente `Prensa` y `Actualidad y Medios` están separadas | Consolidar bajo `/prensa`; retirar `Actualidad y Medios` como nivel principal. |
| CV | Sólo es un ancla dentro de Sobre mí y un CTA en Home | Crear `/cv` como página principal y agregarla al menú. |
| Contacto | Existe `/contacto` | Conservar y actualizar formulario/contenido. |

### Ítems actuales que dejan de ser principales

- `/agenda`: no debe permanecer como página o ítem aislado. Redirigir a `/actividad-academica/jornadas-y-congresos#proximos`.
- `/actualidad-y-medios`: consolidar en `/prensa` y redirigir.
- `/exposicion-publica`: reubicar en `/actividad-academica/conferencias` y redirigir.
- `/dossier-de-prensa`: mantener sólo como recurso secundario de Prensa si el cliente confirma que sigue vigente; quitarlo de la navegación principal.
- `/trayectoria`: integrar en `/sobre-mi` o conservar como URL secundaria no navegable; decidir con criterio SEO antes de redirigir.
- `/novedades` y sus cuatro entradas de demostración: no pertenecen al mapa objetivo; auditar si son datos de prueba antes de retirar.
- `/muestra-bloques`: página técnica; no incluir en navegación ni sitemap público.

## Páginas

| Página objetivo | Situación actual | Acción requerida |
|---|---|---|
| `/` | Existe con Hero, publicaciones, biografía, entrevistas, eventos, CTA contacto y CTA CV | Reordenar; sumar cargos actuales y reconocimiento; reemplazar CV simple por acceso dual; usar feed unificado de prensa. |
| `/sobre-mi` | Existe con biografía, timeline, cards de cargos y CTA CV | Quitar referencias no vigentes; cargar cargos actuales; sumar reconocimiento; usar acceso dual al CV. |
| `/actividad-academica` | Existe sólo con un bloque Text | Crear portada de sección con accesos a subsecciones. |
| `/actividad-academica/docencia` | Existe `/docencia`, plana | Mover bajo el padre y dejar únicamente la actividad docente vigente en UBA. |
| `/actividad-academica/grado-uba` | No existe | Crear página específica; eliminar UCA, UCES y Equipo docente. |
| `/actividad-academica/posgrado` | No existe | Crear página y cargar Universidad Austral; hay datos pendientes. |
| `/actividad-academica/doctorado` | No existe | Crear página; datos de UBA y Universidad de Mendoza pendientes. |
| `/actividad-academica/programas` | Existe `/programas`, plana | Mover bajo el padre; listar vigentes e históricos. |
| `/actividad-academica/jornadas-y-congresos` | Existe `/jornadas-y-congresos`, plana y sólo con Text | Mover bajo el padre; dividir realizados/próximos. |
| `/actividad-academica/conferencias` | No existe; hay `/exposicion-publica` | Crear destino y migrar contenido audiovisual relevante. |
| `/publicaciones` | Existe con listados, pero sin hijos | Convertir en portada de sección. |
| `/publicaciones/libros` | Es un ancla `#libros` | Crear índice ruteable y fichas de libros; ordenar 2025 primero. |
| `/publicaciones/articulos-academicos` | Es un ancla `#articulos` | Crear índice ruteable y fichas; agregar filtros. |
| `/prensa` | Existe y sólo contiene entrevistas/dossier | Convertir en portada unificada de prensa y actualidad. |
| `/prensa/articulos-en-medios` | No existe | Crear índice; requiere nuevo tipo de contenido de prensa. |
| `/prensa/entrevistas` | Es un ancla | Crear índice ruteable; reutilizar recurso Entrevista. |
| `/prensa/noticias` | No existe | Crear índice; requiere nuevo tipo de contenido de prensa. |
| `/cv` | No existe | Crear página con CV completo y reducido, vista, descarga y fecha. |
| `/contacto` | Existe | Ampliar formulario para institución/medio, motivo y consentimiento. |

## Cobertura de bloques

### Se reutilizan sin crear bloques nuevos

| Necesidad | Bloque existente | Observación |
|---|---|---|
| Hero de Home | `Hero` | Ya admite retrato, especialidad, descripción y CTAs. |
| Biografía | `BiographySummary` | Debe registrarse: actualmente tiene PHP y Blade pero figura `SIN_REGISTRO`. |
| Trayectoria | `Timeline` | Cubre hitos cronológicos. |
| Publicación más reciente | `FeaturedResources` o `PublicationsHighlight` | Puede seleccionar un libro o listar destacados. |
| Reconocimiento destacado | `MediaText` | Cubre texto, imagen/video y CTA; cargar fecha/organismo dentro del contenido. |
| Portadas de sección | `Text` + `Cards` | Suficiente para introducción y accesos. |
| Conferencia individual | `Media` o `MediaText` | Suficiente en fichas o selecciones manuales. |
| Contacto breve | `CTA` | Suficiente para llamadas a contacto. |
| Formulario | `ContactForm` | Requiere actualización de campos, no un bloque nuevo. |
| Libros y artículos académicos | `PublicationsHighlight` | Ya consulta ambos recursos. |

### Bloques nuevos necesarios

1. `ContentList`: listado textual reutilizable para cargos, credenciales o reconocimientos.
2. `PressFeed`: feed unificado de artículos en medios, entrevistas y noticias.
3. `CVAccess`: acceso dual a CV completo/reducido con visualización, descarga y fecha.
4. `EventsListing`: listado automático de actividades próximas, realizadas o todas, con orden correcto.
5. `TeachingListing`: listado de grado, posgrado o doctorado desde el recurso Docencia.
6. `ProgramsListing`: programas vigentes o históricos desde el recurso ProgramaAcademico.

Los schemas editables están en `docs/cms/blocks/draft-*.md`. No se generaron clases PHP, vistas Blade ni registros.

## Brechas de tipos de contenido

| Tipo objetivo | Cobertura actual | Cambio necesario |
|---|---|---|
| Libro | Existe `Libro` | Agregar autoría, área temática, tomos/páginas/ISBN por tomo y tipo de obra; verificar título desde Route. |
| Artículo académico | Existe `ArticuloAcademico` | Agregar publicación, editorial/institución, coautoría y filtros estructurados. |
| Publicación en medios | No existe como recurso propio | Crear recurso con tipo `articulo`, `entrevista` o `noticia`, o migrar Entrevista a un recurso editorial común. |
| Entrevista | Existe `Entrevista` | Puede conservarse y ser consumida por `PressFeed`, o migrarse al recurso común. |
| Actividad académica | Existe `Evento` | Agregar institución, rol, modalidad, estado de confirmación, imagen y video; revisar taxonomía `tipo`. |
| Cargo institucional | Existe `CargoInstitucional` | Agregar enlace institucional y flag destacado en Home; período ya se deriva de fechas. |
| Programa | Existe `ProgramaAcademico` | Agregar materia, año, cuatrimestre, archivo y estado vigente/histórico. |
| Docencia | Existe `Docencia` | Agregar universidad/facultad/carrera/rol/modalidad/período/link según nivel. |
| CV | No existe como recurso | Puede resolverse en el bloque `CVAccess`; si habrá historial/versionado, crear recurso propio. |

## Riesgos y decisiones abiertas

- Las URLs actuales son planas. Los cambios necesitan redirecciones 301 y revisión de enlaces internos antes de alterar slugs.
- El mapa objetivo menciona filtros. La implementación debe decidir si son query strings en listados o taxonomías persistidas.
- `EventsHighlight` no permite “sólo realizados”: `show_past=true` incluye pasados y futuros. Por eso se propone `EventsListing`.
- `CVDownload` sólo tiene título, descripción y texto de botón; no almacena archivo, ruta, versión ni fecha. No cumple el objetivo final.
- `FeaturedResources` no acepta Docencia, Programa, Evento ni Cargo y no reemplaza los listados nuevos.
- Hay contenidos actuales posiblemente incorrectos o de ejemplo (CONICET, Universidad de Palermo, fechas del timeline y cuatro novedades demo). Deben validarse editorialmente, no migrarse a ciegas.
