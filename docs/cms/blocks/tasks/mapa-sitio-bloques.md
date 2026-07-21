# Ejecución individual de los bloques del nuevo mapa

Este índice permite crear la base funcional de cada bloque en tareas separadas, sin maquetar sus vistas en el mismo contexto.

## Cómo usarlo

1. Abrir una tarea nueva de Codex en la raíz del proyecto.
2. Copiar solamente el prompt del archivo correspondiente.
3. Esperar que la tarea termine y verificar su checklist.
4. Continuar con el siguiente bloque respetando el orden.
5. Cargar datos reales en el CMS.
6. Abrir otra tarea para diseñar cada bloque usando el prompt de diseño incluido al final de su archivo.

Cada tarea de backend debe terminar con una vista mínima:

```blade
<x-block>
    @dump(get_defined_vars())
</x-block>
```

No debe tomar decisiones visuales ni maquetar el frontend.

## Orden recomendado

| Orden | Tarea | Dependencia principal | Estado |
|---|---|---|---|
| 1 | [ContentList](content-list.md) | Listado textual reutilizable | Hecho |
| 2 | [EventsListing](events-listing.md) | Ampliar Evento | Pendiente |
| 3 | [TeachingListing](teaching-listing.md) | Ampliar Docencia | Pendiente |
| 4 | [ProgramsListing](programs-listing.md) | Ampliar ProgramaAcademico | Pendiente |
| 5 | [PressFeed](press-feed.md) | Definir/crear PublicacionMedio | Pendiente |
| 6 | [CVAccess](cv-access.md) | Ninguna migración obligatoria | Pendiente |

## Regla de contexto

En cada tarea leer únicamente:

- `AGENTS.md`.
- `DESIGN.md` sólo como restricción futura; no diseñar.
- La skill `create-block`.
- `docs/cms/blocks/subagents/block-backend.md`.
- El draft indicado por el prompt.
- Los modelos, recursos y bloques concretamente relacionados.

No releer el mapa completo ni los demás drafts salvo que aparezca una dependencia técnica directa.

## Definición de “base funcional”

- El modelo/recurso dependiente soporta los campos requeridos.
- La migración es reversible y preserva datos existentes.
- Existe `app/Filament/Blocks/{Name}Block.php`.
- Existe `resources/views/blocks/{Name}.blade.php` con dump, sin diseño.
- Está registrado en `DefaultTemplate.php`.
- Existe `docs/cms/blocks/doc-{Name}.md`.
- El draft original se conserva o se marca como ejecutado según las convenciones del proyecto.
- Pasan sintaxis, migraciones, limpieza de vistas y pruebas relevantes.
- El bloque aparece en el selector del administrador.

## Después de completar los seis

Ejecutar:

```bash
php artisan cms:blocks-list
```

Los seis deben figurar como `REGISTRADO`. Recién entonces comenzar las tareas de carga, validación y diseño una por una.
