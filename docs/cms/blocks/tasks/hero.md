# T13 — HeroBlock

- **Estado**: ⬜ pendiente
- **Depende de**: Ninguna
- **Categoría**: Hero

---

## Schema

| Campo | Tipo | Requerido | Default | Notas |
|-------|------|-----------|---------|-------|
| title | text | sí | | Título principal |
| subtitle | textarea/rich | no | | Subtítulo |
| image | image | no | | Imagen de fondo/hero |
| cta_label | text | no | | Texto del botón |
| cta_route | route | no | | Ruta del CTA |

---

## Flujo

1. **Draft** → `docs/cms/blocks/draft-hero.md`
2. **Backend** → `HeroBlock.php` + `Hero.blade.php` + registro en `DefaultTemplate`
3. **Diseño** → Diferido (migrar diseño del `Hero.blade.php` solo-vista existente con animaciones GSAP)

---

## Checklist

- [ ] Crear clase `HeroBlock.php` extendiendo `PageBlock`
- [ ] Crear vista `Hero.blade.php` (funcional con `@dump` inicial)
- [ ] Registrar en `DefaultTemplate.php`
- [ ] `php -l` sobre la clase
- [ ] `php artisan view:clear`
- [ ] Probar en `/admin/pages/create`

---

## Notas

- El `Hero.blade.php` actual (solo-vista) tiene animaciones GSAP y dos estilos. Al migrar a bloque registrado se conserva ese diseño.
- NAME = `Hero`, LABEL = `Hero`
