# Propuesta de reestructuración — punto medio

**Fecha:** 6 de agosto de 2026  
**Estado:** implementada y verificada.

## Criterio

La propuesta combina:

- la navegación y los anchors útiles del sitio actual;
- la organización solicitada por el cliente;
- la estructura simple del sitio legacy;
- el contenido que ya fue importado al CMS;
- la regla del design system de mostrar sólo dos o tres accesos prioritarios en el header y llevar el mapa completo a un panel amplio.

No se interpreta el cuadro del cliente como una orden de crear una página independiente por cada rótulo. Cuando una necesidad se resuelve mejor con un anchor, un filtro o una vista derivada, se conserva una sola fuente de datos y una sola URL canónica.

## Árbol recomendado

```text
Inicio /
├── Sobre mí /sobre-mi
│   ├── Biografía #biografia
│   ├── Trayectoria en cifras #trayectoria-en-cifras
│   ├── Cargos institucionales #cargos
│   └── Reconocimientos #reconocimientos
├── Actividad académica /actividad-academica
│   ├── Docencia /actividad-academica/docencia
│   │   ├── Actividad docente #actividad-docente
│   │   ├── Materias de grado #materias
│   │   ├── Posgrados y maestrías #posgrados
│   │   ├── Doctorado #doctorado
│   │   ├── Programas de cátedra #programas
│   │   ├── Trayectoria docente #trayectoria-docente
│   │   └── Material para alumnos #material-para-alumnos
│   ├── Conferencias /actividad-academica/conferencias
│   │   ├── Videos y galería audiovisual #videos
│   │   └── Agenda #agenda
│   └── Jornadas y Congresos /actividad-academica/jornadas-y-congresos
│       ├── Próximos eventos #proximos
│       └── Historial #historial
├── Publicaciones /publicaciones
│   ├── Libros /publicaciones/libros
│   └── Artículos académicos /publicaciones/articulos-academicos
│       └── Destino compartido desde Material para alumnos
├── Actualidad /actualidad
│   ├── Todos
│   ├── Noticias
│   ├── Prensa
│   └── Entrevistas
├── CV /cv
└── Contacto /contacto
```

### Cómo se muestra en el header

Accesos prioritarios visibles:

1. `Sobre mí`
2. `Publicaciones`
3. `Actividad académica`
4. botón institucional `CV`
5. disparador `Ver más`

El panel `Ver más` contiene el árbol completo y accesos directos destacados a `Libros`, `Actualidad`, `Jornadas y Congresos` y `Contacto`.

Esto conserva la lógica editorial actual y evita una navegación principal con demasiados ítems. `Jornadas y Congresos` sigue siendo fácil de encontrar aunque jerárquicamente dependa de Actividad académica.

## Decisiones por sección

### Sobre mí

Se mantiene como página única con anchors. No se fragmenta en páginas separadas.

Composición recomendada:

1. Hero interno.
2. Biografía.
3. Trayectoria en cifras.
4. Cargos actuales y destacados.
5. Reconocimientos.
6. Acceso al CV.

La página `/trayectoria` deja de ser un destino principal. Debe redirigir a `/sobre-mi#trayectoria-en-cifras` cuando se confirme que no tiene contenido exclusivo.

### CV

Se crea `/cv` como destino estable para consultar y descargar el documento.

- El header usa un botón `CV`.
- Las páginas principales pueden cerrar con un acceso contextual al CV.
- No se repite un CTA grande después de cada bloque: el design system establece que las acciones deben ser secundarias frente a la trayectoria y la autoridad institucional.
- Se conserva como versión inicial el PDF completo verificable del sitio anterior. El cliente puede reemplazarlo desde el CMS por una versión actualizada.

### Actividad académica

Se convierte en una portada breve de sección, no en un archivo de publicaciones.

Sus tres destinos son:

- Docencia.
- Conferencias.
- Jornadas y Congresos.

La actual página `/actividad-academica`, que hoy contiene la actividad docente, se recompone como portada y su contenido docente pasa a `/actividad-academica/docencia`.

### Docencia

Se combinan las secciones pedidas por el cliente con anchors, evitando siete páginas pequeñas.

Los artículos para alumnos no se duplican. El anchor `Material para alumnos` enlaza al archivo de artículos académicos. Todos los registros siguen administrándose en `ArticuloAcademico`; un filtro de audiencia sólo se agregará cuando exista metadata fiable para distinguir el material.

Los programas de cátedra deben distinguirse de las noticias legacy categorizadas como `Programas`. Esas noticias hablan principalmente de programas institucionales de acceso a la justicia y deben permanecer en Actualidad.

### Publicaciones

Permanece como sección principal, tal como funciona actualmente.

- `Libros`: archivo de libros y fichas individuales.
- `Artículos académicos`: archivo temático, filtros, PDFs y vista específica para alumnos.
- La “Biblioteca digital” solicitada por el cliente es una presentación o bloque dentro de Publicaciones, no una tercera base de datos.

### Actualidad

Se conserva un único archivo cronológico con filtros, siguiendo la lógica del legacy.

Filtros editoriales principales:

- Todos.
- Noticias.
- Prensa.
- Entrevistas.

Filtros temáticos secundarios:

- Jornadas y Congresos.
- Charlas.
- Programas históricos.
- Publicaciones.

Los filtros secundarios preservan la taxonomía del legacy sin convertir cada categoría en una página principal.

### Conferencias vs. Jornadas y Congresos

Regla propuesta:

- `Conferencias`: intervenciones de Marcela como expositora, entrevistas audiovisuales, videos, grabaciones y agenda de exposiciones.
- `Jornadas y Congresos`: eventos académicos como unidad organizativa, con próximos eventos e historial, independientemente de que exista o no video.

Una misma actividad puede aparecer en ambas vistas cuando corresponda, pero debe existir como un único registro con una sola URL canónica.

## Conservación del contenido real

### Archivo legacy de Actualidad

El CMS contiene 165 entradas legacy reales y etiquetadas:

| Etiqueta legacy | Registros |
|---|---:|
| Noticias | 62 |
| Jornadas y Congresos | 39 |
| Medios | 30 |
| Programas | 25 |
| Publicaciones | 6 |
| Charlas | 3 |

El catálogo actual ya normaliza ese archivo en 165 piezas únicas:

- 135 noticias.
- 29 artículos/prensa.
- 1 entrevista real.

Las 30 piezas de `PublicacionMedio` provienen de las entradas legacy etiquetadas como `Medios`. Cuando existe la misma pieza en ambos recursos, el catálogo conserva la ficha específica de prensa y evita mostrarla dos veces.

**Decisión:** conservar las 165 piezas reales, sus categorías y sus URLs. No realizar una curaduría destructiva antes del lanzamiento.

### Datos demostrativos que no deben mezclarse con el archivo

- 22 registros `Entrevista` llamados `Entrevista ejemplo 1..22`, sin enlaces, videos ni rutas.
- 4 entradas Blog sobre el uso genérico del CMS, sin fecha ni categorías.

**Decisión ejecutada:** se retiraron los 22 registros de entrevista demo y las 4 entradas Blog genéricas mediante un seeder conservador que sólo coincide con esos patrones inequívocos.

### Artículos académicos

- El legacy expone 103 PDFs.
- El CMS contiene 103 `ArticuloAcademico` con enlace externo al PDF.
- Se preservaron 23 áreas temáticas.

**Decisión:** el archivo académico fue importado de forma completa. Mantener todos los registros, sus PDFs y temas; agregar filtros de tema, año y audiencia sin duplicar artículos.

### Libros

- El CMS tiene 12 fichas: 6 de autoría, 3 de coautoría y 3 de dirección.
- El legacy afirma una participación total en 56 libros, pero su página pública sólo presenta una selección.

**Decisión:** conservar las 12 fichas verificadas y la cifra institucional de 56 como dato de trayectoria. No inventar las 44 fichas faltantes. El inventario completo debe extraerse de un CV actualizado o ser entregado por el cliente antes de ampliar el catálogo.

### Docencia e instituciones

- CMS actual: 13 actividades de posgrado, maestría y doctorado.
- CMS actual: 7 instituciones académicas.
- Legacy: cuatro materias de grado en UBA, UCA y UCES con programas descargables.

**Decisión:** conservar estos antecedentes. Las actividades que ya no estén vigentes se marcan como históricas y no se presentan como cargos o materias actuales. No borrar UCA o UCES sólo porque no aparezcan en el cuadro del cliente.

### Cargos

- 9 cargos institucionales cargados.
- 7 sin fecha de finalización.
- 2 destacados.

**Decisión:** mostrar los 2 destacados en Inicio, los cargos actuales en Sobre mí y mantener los históricos disponibles sin darles la misma prominencia.

### Conferencias, jornadas y charlas

- 6 conferencias audiovisuales estructuradas en el CMS.
- 39 entradas legacy etiquetadas `Jornadas y Congresos`.
- 3 entradas legacy etiquetadas `Charlas`.

**Decisión:** usar las 6 fichas estructuradas como base de Conferencias. El historial de Jornadas puede consultar las 39 entradas legacy sin copiarlas. Las 3 Charlas se incorporan como filtro o vista relacionada de Conferencias/Actualidad.

## Redirecciones recomendadas

```text
/home                                      -> /
/actividad-docente                         -> /actividad-academica/docencia
/libros                                    -> /publicaciones/libros
/articulos-especializados                  -> /publicaciones/articulos-academicos
/actualidad-y-produccion-academica         -> /actualidad
/actualidad-y-medios                       -> /actualidad
/trayectoria                               -> /sobre-mi#trayectoria-en-cifras
/programas                                 -> /actividad-academica/docencia#programas
```

`/actividad-academica/jornadas-y-congresos` se conserva como URL definitiva.

## Implementación ejecutada

1. Se crearon los destinos nuevos y los anchors sin duplicar fuentes de datos.
2. Se separaron Docencia, Conferencias y Jornadas bajo Actividad académica.
3. Se incorporaron filtros por tipo y tema histórico en Actualidad.
4. Se activaron y probaron las redirecciones legacy.
5. Header y footer consumen el mismo menú CMS.
6. Se limpiaron únicamente demos confirmados y dos respaldos obsoletos de vistas.
7. La arquitectura quedó cubierta por pruebas automatizadas e inspección del sitio renderizado.

## Fuentes legacy consultadas

- `https://marcelabasterra.com.ar/`
- `https://marcelabasterra.com.ar/actividad-docente/`
- `https://marcelabasterra.com.ar/libros/`
- `https://marcelabasterra.com.ar/articulos-especializados/`
- `https://marcelabasterra.com.ar/actualidad/`
