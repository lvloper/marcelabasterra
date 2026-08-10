# Mapa actual del sitio

**Estado:** implementado y verificado

**Actualizado:** 6 de agosto de 2026

**Regla visual:** toda modificación debe respetar `DESIGN.md`.

## Arquitectura pública

```text
/
├── /sobre-mi
│   ├── #biografia
│   ├── #trayectoria-en-cifras
│   ├── #cargos
│   └── #reconocimientos
├── /actividad-academica
│   ├── /actividad-academica/docencia
│   │   ├── #actividad-docente
│   │   ├── #materias
│   │   ├── #posgrados
│   │   ├── #doctorado
│   │   ├── #programas
│   │   ├── #trayectoria-docente
│   │   └── #material-para-alumnos
│   ├── /actividad-academica/conferencias
│   │   ├── #videos
│   │   └── #agenda
│   └── /actividad-academica/jornadas-y-congresos
│       ├── #proximos
│       ├── #historial
│       └── #archivo-legacy
├── /publicaciones
│   ├── /publicaciones/libros
│   └── /publicaciones/articulos-academicos
├── /actualidad
├── /cv
└── /contacto
```

Rutas auxiliares que no forman parte de la navegación principal:

```text
/novedades
/error-404
/muestra-bloques        # demostración técnica; no publicar en sitemap
```

## Menú principal

Accesos persistentes del header:

1. Sobre mí.
2. Publicaciones.
3. Actividad académica.
4. Botón CV.
5. Panel Ver más.

El panel y el footer consumen el mismo registro `Menu` con slug `header`. El panel expone todos los anchors y destinos secundarios.

## Criterio editorial

- `Actividad académica` funciona como portada de Docencia, Conferencias y Jornadas.
- `Publicaciones` conserva Libros y Artículos académicos como archivos canónicos.
- `Material para alumnos` enlaza a Artículos académicos; no duplica registros.
- `Actualidad` conserva las 165 piezas legacy y permite filtrar por tipo y tema histórico.
- `Conferencias` reúne las seis fichas audiovisuales estructuradas.
- `Jornadas y Congresos` queda reservado a eventos como unidad organizativa; sus 39 crónicas legacy se consultan desde Actualidad.
- Los cuatro programas docentes recuperados se muestran como antecedentes históricos, no como actividad vigente.
- El total institucional de 56 libros se mantiene como métrica; el catálogo sólo publica las 12 fichas verificadas.

## Redirecciones activas

```text
/home                              -> /
/actividad-docente                 -> /actividad-academica/docencia
/libros                            -> /publicaciones/libros
/articulos-especializados          -> /publicaciones/articulos-academicos
/actualidad-y-produccion-academica -> /actualidad
/actualidad-y-medios               -> /actualidad
/trayectoria                       -> /sobre-mi#trayectoria-en-cifras
/programas                         -> /actividad-academica/docencia#programas
```

## Fuentes CMS reutilizables

| Dominio | Recurso | Estado preservado |
|---|---|---:|
| Docencia | `Docencia` | 13 vigentes + 4 antecedentes de grado |
| Instituciones | `InstitucionAcademica` | 8 instituciones |
| Artículos | `ArticuloAcademico` | 103 artículos y PDFs |
| Libros | `Libro` | 12 fichas verificadas |
| Actualidad | `Blog` + `PublicacionMedio` | 165 piezas únicas |
| Cargos | `CargoInstitucional` | 9 cargos |
| Conferencias | `Conferencia` | 6 fichas estructuradas |

## Reglas vigentes

- No duplicar entidades CMS dentro de bloques o páginas derivadas.
- No inventar fichas para completar métricas institucionales.
- Marcar como histórico todo antecedente cuya vigencia no esté confirmada.
- Mantener las páginas demo y técnicas fuera del sitemap público.
- Ejecutar `SiteArchitectureSeeder` después de los seeders de contenido para reproducir la estructura.
- Todo bloque nuevo o rediseño debe respetar `DESIGN.md` y WCAG 2.2 AA.
