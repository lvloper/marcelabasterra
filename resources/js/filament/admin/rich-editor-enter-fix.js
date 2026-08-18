/**
 * Filament parses legacy <figure> markup as root-level image/text nodes.
 * ProseMirror cannot split those nodes with Enter, so provide the missing
 * paragraph insertion behavior while leaving normal paragraphs untouched.
 */
export default () => {
    const { Extension } = window.FilamentRichEditor.tiptap.core;
    const { Fragment } = window.FilamentRichEditor.tiptap.pmModel;
    const { TextSelection } = window.FilamentRichEditor.tiptap.pmState;

    return Extension.create({
        name: 'documentEndParagraph',
        priority: 10_000,

        onCreate() {
            const { state, view } = this.editor;
            const paragraph = state.schema.nodes.paragraph;

            if (! paragraph) {
                return;
            }

            const normalizedNodes = [];
            let inlineNodes = [];

            const flushInlineNodes = () => {
                if (! inlineNodes.length) {
                    return;
                }

                normalizedNodes.push(
                    paragraph.create(null, Fragment.fromArray(inlineNodes)),
                );
                inlineNodes = [];
            };

            state.doc.forEach((node) => {
                if (node.isInline) {
                    if (node.type.name === 'image' && inlineNodes.length) {
                        flushInlineNodes();
                    }

                    inlineNodes.push(node);

                    return;
                }

                flushInlineNodes();
                normalizedNodes.push(node);
            });

            flushInlineNodes();

            if (
                normalizedNodes.length === state.doc.childCount
                && normalizedNodes.every((node, index) => node === state.doc.child(index))
            ) {
                return;
            }

            view.dispatch(
                state.tr
                    .replaceWith(
                        0,
                        state.doc.content.size,
                        Fragment.fromArray(normalizedNodes),
                    )
                    .setMeta('addToHistory', false),
            );
        },

        addKeyboardShortcuts() {
            return {
                Enter: () => {
                    const { schema, selection } = this.editor.state;

                    if (selection.$from.parent.type.name === 'codeBlock') {
                        return this.editor.commands.newlineInCode();
                    }

                    if (this.editor.isActive('listItem')) {
                        return this.editor.commands.splitListItem('listItem');
                    }

                    if (selection.$from.parent.isTextblock) {
                        return this.editor.commands.splitBlock();
                    }

                    const paragraph = schema.nodes.paragraph;

                    if (! paragraph || selection.$from.depth !== 0) {
                        return false;
                    }

                    const insertAt = selection.to;
                    const transaction = this.editor.state.tr.insert(
                        insertAt,
                        paragraph.create(),
                    );

                    transaction.setSelection(
                        TextSelection.create(transaction.doc, insertAt + 1),
                    );

                    this.editor.view.dispatch(transaction.scrollIntoView());

                    return true;
                },
            };
        },
    });
};
