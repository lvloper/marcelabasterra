# Navegación actual del sitio

**Estado relevado:** 6 de agosto de 2026, antes de la reestructuración solicitada por el cliente.

**Fuentes contrastadas:** sitio renderizado en `http://127.0.0.1:8082`, `resources/views/components/common/header.blade.php`, menú CMS `header`, rutas y páginas persistidas en SQLite.

## Árbol que ve hoy el visitante

```text
Inicio /                                             # acceso por el logo
├── Sobre mí /sobre-mi                               # acceso principal
├── Publicaciones /publicaciones                     # acceso principal
├── Actividad académica /actividad-academica         # acceso principal
└── Ver más / Archivo vivo
    ├── Sobre mí /sobre-mi
    │   ├── Biografía #biografia
    │   ├── Trayectoria #trayectoria-en-cifras
    │   ├── Cargos institucionales #cargos
    │   └── CV #cv
    ├── Actividad académica /actividad-academica
    │   └── Programas /programas
    ├── Publicaciones /publicaciones
    │   ├── Libros /publicaciones/libros
    │   ├── Artículos académicos /publicaciones/articulos-academicos
    │   └── Actividad /actualidad-y-produccion-academica
    ├── Novedades /novedades
    └── Contacto /contacto
```

El panel `Archivo vivo` suma dos grupos editoriales dinámicos que funcionan como accesos contextuales, no como secciones del mapa:

- `Últimos libros`: los tres libros más recientes.
- `En foco`: la novedad, publicación en medios y conferencia más recientes.

### Diferencia detectada

`Jornadas y Congresos` existe y está publicada en `/actividad-academica/jornadas-y-congresos`, pero no aparece como acceso principal ni dentro de `Actividad académica`. El header intenta resolver el path plano `jornadas-y-congresos`, que no coincide con la ruta persistida.

## Árbol público de páginas y contenidos ruteables

```text
/                                                     # Page: Home
├── /sobre-mi                                         # Page
├── /trayectoria                                      # Page secundaria
│   └── /trayectoria/{cargo}                          # 2 de 9 cargos tienen Route
├── /actividad-academica                              # Page
│   ├── /actividad-academica/{docencia}               # 13 docencias
│   ├── /actividad-academica/{institucion}            # 7 instituciones
│   └── /actividad-academica/jornadas-y-congresos     # Page
│       └── /actividad-academica/jornadas-y-congresos/{conferencia}
│                                                       # 6 conferencias
├── /programas                                        # Page; 0 programas cargados
├── /publicaciones                                    # Page
│   ├── /publicaciones/libros                         # índice propio
│   │   └── /publicaciones/libros/{libro}             # 12 libros
│   └── /publicaciones/articulos-academicos           # índice propio
│       └── /publicaciones/articulos-academicos/{articulo}
│                                                       # 103 artículos
├── /actualidad-y-produccion-academica                # Page
│   └── /actualidad-y-produccion-academica/{publicacion}
│                                                       # 30 publicaciones en medios
├── /novedades                                        # Page
│   └── /novedades/{nota}                             # 169 notas Blog
└── /contacto                                         # Page
```

Páginas auxiliares o técnicas, fuera de la navegación principal:

```text
/error-404
/muestra-bloques
```

También existen 22 entrevistas sin una Route propia. Son consumibles por bloques/listados, pero no forman hoy un subárbol navegable. No hay registros cargados en `Evento`, `ProgramaAcademico` ni `DossierPrensa`.

## Composición actual de las páginas navegables

| Página | Bloques persistidos actualmente |
|---|---|
| Inicio | Hero · Presentación (`Intro`) · Último libro (`PublicationsHighlight`) · Actualidad y publicaciones recientes (`PressFeed`) · Conferencias (`EventsListing`) · CTA |
| Sobre mí | Presentación (`Intro`) · Trayectoria en cifras · Responsabilidades institucionales · Reconocimiento destacado · Banner al CV (`CTA` → `/cv`) |
| Actividad académica | Hero · Actividad docente (`TeachingListing`) · Artículos especializados |
| Programas | Recursos destacados; sin registros de programas cargados |
| Jornadas y Congresos | Hero · Actividad destacada · Agenda y archivo |
| Publicaciones | Publicaciones destacadas · Recursos editoriales destacados |
| Libros | Índice especializado resuelto por controlador; sin bloques propios |
| Artículos académicos | Índice especializado resuelto por controlador; sin bloques propios |
| Actividad | Archivo de Noticias · Prensa · Entrevistas (`PressFeed`) |
| Novedades | Texto introductorio + 169 notas ruteables |
| Contacto | Formulario · Información de contacto · CTA |

## Menú CMS heredado y pie de página

El registro CMS `Menu: Header` no gobierna el header actual; el header está construido directamente en Blade. Ese registro sí alimenta el pie de página y conserva esta estructura anterior:

```text
Home /home
├── Sobre mi /sobre-mi
│   ├── Biografia #biografia
│   ├── Trayectoria /trayectoria
│   ├── Cargos institucionales #cargos
│   └── CV #cv
├── Actividad Academica /actividad-academica
│   ├── Programas /programas
│   └── Jornadas y Congresos [referencia almacenada]
├── Publicaciones /publicaciones
│   ├── Libros #libros
│   ├── Articulos Academicos #articulos
│   └── Actualidad y Medios /actualidad-y-produccion-academica
└── Contacto /contacto
```

En el footer sólo se muestran los ítems de primer nivel `Home`, `Sobre mi`, `Actividad Academica`, `Publicaciones` y el CTA `Contacto`.

## Observaciones para la futura migración

- Hay dos fuentes de navegación desalineadas: header hardcodeado y menú CMS usado por el footer.
- La URL pública de Inicio es `/`, pero el menú CMS enlaza `/home`, que luego redirige.
- `Trayectoria` existe simultáneamente como página `/trayectoria` y como ancla de `/sobre-mi`.
- `Actividad` agrupa 30 publicaciones en medios y el bloque también puede consumir 22 entrevistas; `Novedades` conserva por separado 169 notas históricas.
- Las 6 conferencias están anidadas bajo `Jornadas y Congresos`; no existe todavía una página independiente de Conferencias.
- Los 13 registros docentes cubren posgrado, maestría y doctorado; no hay registros de grado cargados.
- De los 9 cargos institucionales, sólo 2 tienen ficha/ruta pública propia.

