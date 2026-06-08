# T12 — Auditoría de bloques existentes

- **Estado**: ⬜ pendiente
- **Depende de**: Ninguna

---

## Bloques a auditar

### MediaBlock (`app/Filament/Blocks/MediaBlock.php`)
- Campos actuales: ?
- Vista actual: `resources/views/blocks/Media.blade.php`
- Requisitos: imagen/video + caption
- Acción: verificar si cubre o necesita ajustes

### CardsBlock (`app/Filament/Blocks/CardsBlock.php`)
- Campos actuales: title, description, repeater items (title, description, image, route)
- Vista actual: `resources/views/blocks/Cards.blade.php`
- Requisitos: items (cards genéricos)
- Acción: verificar si cubre o necesita ajustes

### TextBlock (`app/Filament/Blocks/TextBlock.php`)
- Campos actuales: ?
- Vista actual: `resources/views/blocks/Text.blade.php`
- Requisitos: rich text (RichTextBlock)
- Acción: verificar si cubre o necesita ajustes

---

## Checklist

- [ ] Leer `MediaBlock.php` y evaluar vs requisitos (imagen/video + caption)
- [ ] Leer `CardsBlock.php` y evaluar vs requisitos (items cards)
- [ ] Leer `TextBlock.php` y evaluar vs requisitos (contenido rich text)
- [ ] Documentar diferencias y acciones necesarias
- [ ] Ejecutar ajustes si hacen falta

---

## Resultado

| Bloque | ¿Cubre? | Acción |
|--------|---------|--------|
| MediaBlock | ⬜ | |
| CardsBlock | ⬜ | |
| TextBlock | ⬜ | |
