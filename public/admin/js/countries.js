(() => {
    const form = document.querySelector('[data-country-form]');

    if (!form) return;

    const provinces = form.querySelector('[data-provinces]');
    const template = form.querySelector('[data-province-template]');
    const addButton = form.querySelector('[data-add-province]');
    let nextIndex = Number(provinces?.dataset.nextIndex ?? 0);

    const updateEmptyState = () => {
        const hasProvinces = Boolean(provinces?.querySelector('[data-province]'));
        form.querySelector('[data-provinces-empty]')?.classList.toggle('hidden', hasProvinces);
    };

    addButton?.addEventListener('click', () => {
        if (!provinces || !template) return;

        const wrapper = document.createElement('div');
        wrapper.innerHTML = template.innerHTML.replaceAll('__INDEX__', String(nextIndex++));
        provinces.append(wrapper.firstElementChild);
        updateEmptyState();
    });

    provinces?.addEventListener('click', (event) => {
        const removeButton = event.target.closest('[data-remove-province]');

        if (!removeButton) return;

        removeButton.closest('[data-province]')?.remove();
        updateEmptyState();
    });

    const flagInput = form.querySelector('[data-flag-input]');
    const flagPreview = form.querySelector('[data-flag-preview]');
    const flagWrapper = form.querySelector('[data-flag-preview-wrapper]');
    const removeFlag = form.querySelector('[data-remove-flag]');
    let objectUrl = null;

    flagInput?.addEventListener('change', () => {
        if (objectUrl) URL.revokeObjectURL(objectUrl);

        const file = flagInput.files?.[0];

        if (!file || !flagPreview) return;

        objectUrl = URL.createObjectURL(file);
        flagPreview.src = objectUrl;
        flagWrapper?.classList.remove('hidden');

        if (removeFlag) removeFlag.checked = false;
    });

    removeFlag?.addEventListener('change', () => {
        if (removeFlag.checked) {
            if (flagInput) flagInput.value = '';
            flagWrapper?.classList.add('hidden');
        } else if (flagPreview?.src) {
            flagWrapper?.classList.remove('hidden');
        }
    });

    window.addEventListener('pagehide', () => {
        if (objectUrl) URL.revokeObjectURL(objectUrl);
    }, { once: true });

    updateEmptyState();
})();
