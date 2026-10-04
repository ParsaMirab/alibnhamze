(() => {
    const form = document.querySelector('[data-tutorial-video-form]');

    if (!form) return;

    const items = form.querySelector('[data-tutorial-items]');
    const template = form.querySelector('[data-tutorial-item-template]');
    const addButton = form.querySelector('[data-add-tutorial-item]');
    let nextIndex = Number(items?.dataset.nextIndex ?? 0);

    const updateEmptyState = () => {
        const hasItems = Boolean(items?.querySelector('[data-tutorial-item]'));
        form.querySelector('[data-tutorial-items-empty]')?.classList.toggle('hidden', hasItems);
    };

    addButton?.addEventListener('click', () => {
        if (!items || !template) return;

        const wrapper = document.createElement('div');
        wrapper.innerHTML = template.innerHTML.replaceAll('__INDEX__', String(nextIndex++));
        items.append(wrapper.firstElementChild);
        updateEmptyState();
    });

    items?.addEventListener('click', (event) => {
        const removeButton = event.target.closest('[data-remove-tutorial-item]');

        if (!removeButton) return;

        removeButton.closest('[data-tutorial-item]')?.remove();
        updateEmptyState();
    });

    form.querySelectorAll('[data-media-input]').forEach((input) => {
        input.addEventListener('change', () => {
            const file = input.files?.[0];
            const preview = form.querySelector(`[data-media-preview="${input.dataset.mediaInput}"]`);
            const removeInput = form.querySelector(`[data-media-remove="${input.dataset.mediaInput}"]`);

            if (!file || !preview) return;

            if (preview.dataset.objectUrl) {
                URL.revokeObjectURL(preview.dataset.objectUrl);
            }

            const objectUrl = URL.createObjectURL(file);
            preview.dataset.objectUrl = objectUrl;
            preview.src = objectUrl;
            preview.classList.remove('hidden');
            preview.closest('[data-media-preview-wrapper]')?.classList.remove('hidden');

            if (removeInput) removeInput.checked = false;
        });
    });

    form.querySelectorAll('[data-media-remove]').forEach((input) => {
        input.addEventListener('change', () => {
            const fileInput = form.querySelector(`[data-media-input="${input.dataset.mediaRemove}"]`);
            const preview = form.querySelector(`[data-media-preview="${input.dataset.mediaRemove}"]`);
            const wrapper = preview?.closest('[data-media-preview-wrapper]');

            if (input.checked) {
                if (fileInput) fileInput.value = '';
                wrapper?.classList.add('hidden');
            } else if (preview?.src) {
                wrapper?.classList.remove('hidden');
            }
        });
    });

    updateEmptyState();
})();
