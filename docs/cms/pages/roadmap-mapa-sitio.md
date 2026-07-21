# Roadmap para alcanzar el mapa de sitio objetivo

## Principios de ejecución

- Implementar primero contratos de datos, luego bloques, después páginas y por último contenido/diseño.
- Mantener las URLs actuales hasta tener listas las redirecciones.
- Ejecutar cada draft de bloque como tarea individual: aprobación → backend con dump → carga/validación → diseño → preview.
- No diseñar vistas nuevas antes de validar datos reales.

## Fase 0 — Confirmación editorial

**Objetivo:** cerrar las decisiones que afectan estructura y migración.

- Confirmar retiro o conservación secundaria de Dossier de prensa.
- Confirmar destino de `/trayectoria`.
- Confirmar que `/novedades` y sus cuatro posts son demo y pueden retirarse.
- Recibir los 14 grupos de contenido pendiente enumerados en el mapa fuente.
- Auditar los textos actuales que mencionan CONICET y Universidad de Palermo antes de migrarlos.

**Salida:** matriz de contenido confirmado, pendiente y descartado.

## Fase 1 — Contratos y modelos de contenido

**Objetivo:** hacer que el CMS pueda representar el contenido final sin campos improvisados.

1. Ampliar `Libro` para autoría, área temática, tomos, páginas, ISBN por tomo y tipo de obra.
2. Ampliar `ArticuloAcademico` para publicación, institución/editorial, coautoría y clasificación.
3. Crear recurso de Publicación en medios o unificar/migrar `Entrevista` con discriminador de tipo.
4. Ampliar `Evento` para institución, rol, modalidad, confirmación, imagen y video.
5. Ampliar `CargoInstitucional` con enlace institucional y destacado en Home.
6. Ampliar `ProgramaAcademico` con materia, año, cuatrimestre, archivo y estado.
7. Ampliar `Docencia` con facultad, carrera/programa, rol, período, modalidad y enlace.
8. Actualizar `ContactForm` para institución/medio, motivo y consentimiento de privacidad.

**Criterio de salida:** migraciones, recursos Filament y validaciones permiten cargar todos los campos del mapa.

## Fase 2 — Bloques nuevos, una tarea por draft

Los prompts autocontenidos para ejecutar esta fase en chats separados están en `docs/cms/blocks/tasks/mapa-sitio-bloques.md`.

Orden sugerido:

1. `draft-content-list.md`
2. `draft-events-listing.md`
3. `draft-teaching-listing.md`
4. `draft-programs-listing.md`
5. `draft-press-feed.md`
6. `draft-cv-access.md`

Para cada uno:

1. Revisar y aprobar el draft.
2. Ejecutar `block-backend`: PHP + Blade con dump + registro + `doc-{Name}.md`.
3. Cargar datos reales.
4. Ejecutar validación contra el doc.
5. Diseñar la vista respetando `DESIGN.md`.
6. Generar preview y probar responsive/accesibilidad.

En paralelo lógico, registrar `BiographySummary`, que hoy figura como `SIN_REGISTRO`; no requiere bloque nuevo.

## Fase 3 — Árbol de páginas y redirecciones

**Objetivo:** construir la jerarquía sin perder tráfico ni enlaces.

1. Crear `/cv`.
2. Crear hijos de Actividad académica: docencia, grado UBA, posgrado, doctorado, programas, jornadas y conferencias.
3. Crear hijos de Publicaciones: libros y artículos académicos.
4. Crear hijos de Prensa: artículos en medios, entrevistas y noticias.
5. Preparar redirecciones:
   - `/agenda` → `/actividad-academica/jornadas-y-congresos#proximos`
   - `/actualidad-y-medios` → `/prensa`
   - `/exposicion-publica` → `/actividad-academica/conferencias`
   - `/docencia` → `/actividad-academica/docencia`
   - `/programas` → `/actividad-academica/programas`
   - `/jornadas-y-congresos` → `/actividad-academica/jornadas-y-congresos`
   - destinos de `/trayectoria` y `/dossier-de-prensa`, una vez confirmados.
6. No activar redirecciones hasta que los destinos estén publicados.

**Criterio de salida:** todas las rutas objetivo resuelven 200 y las antiguas resuelven 301 al destino correcto.

## Fase 4 — Composición de páginas

**Objetivo:** montar las páginas con los bloques documentados en `mapa-sitio-objetivo.md`.

Orden por dependencia y visibilidad:

1. Home.
2. Sobre mí.
3. CV.
4. Actividad académica y sus hijos.
5. Publicaciones y sus hijos.
6. Prensa y sus hijos.
7. Contacto.

Usar bloques existentes cuando cubran el contrato. No crear variantes visuales antes de intentar composición con Hero, Text, Cards, MediaText, Media, CTA, Timeline, FeaturedResources y PublicationsHighlight.

## Fase 5 — Carga y saneamiento editorial

- Cargar CV completo/reducido y sus fechas.
- Corregir Home y Sobre mí con cargos actuales confirmados.
- Cargar reconocimiento con fecha y organismo.
- Cargar libro 2025 completo y marcarlo destacado.
- Eliminar UCA, UCES y Equipo docente de todo contenido publicado.
- Clasificar prensa en artículo, entrevista o noticia.
- Clasificar actividades en próximas/realizadas y por tipo.
- Completar metadatos de videos.
- Retirar contenido demo confirmado.

**Criterio de salida:** ninguna pantalla publicada depende de placeholders o datos ficticios.

## Fase 6 — Menú, SEO y lanzamiento

1. Reemplazar el menú header por los siete ítems finales y sus hijos.
2. Revisar títulos, descripciones, canonical, breadcrumbs y sitemap.
3. Activar redirecciones 301.
4. Excluir muestra técnica y demos del sitemap.
5. Probar enlaces internos, archivos, embeds, formularios y estados vacíos.
6. Validar mobile, teclado, foco, contraste y `prefers-reduced-motion`.
7. Regenerar sitemap y limpiar caches.

## Hitos de control

| Hito | Resultado verificable |
|---|---|
| M1 — Datos listos | Todos los tipos de contenido aceptan los campos objetivo. |
| M2 — Bloques listos | Seis bloques nuevos registrados, validados con datos y diseñados. |
| M3 — Arquitectura lista | Rutas objetivo publicadas y redirecciones preparadas. |
| M4 — Contenido listo | Sin referencias eliminadas, placeholders ni demos. |
| M5 — Lanzamiento | Menú final, SEO, accesibilidad y QA aprobados. |

## Fuera de alcance de esta actualización documental

- No se modificó la base de datos.
- No se crearon ni editaron rutas o menús.
- No se generaron clases PHP ni vistas Blade.
- No se implementaron migraciones, recursos CMS, filtros ni redirecciones.
- No se realizó diseño visual; se aplicará en las tareas individuales posteriores.
