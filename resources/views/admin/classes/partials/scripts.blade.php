@push('styles')
<style>
    [data-class-tab] {
        padding: 0.75rem 1.25rem;
        font-weight: 500;
        font-size: 0.875rem;
        color: var(--secondary-foreground, #4b5675);
        background: transparent;
        border: 0;
        border-bottom: 2px solid transparent;
        cursor: pointer;
        transition: color .15s ease, border-color .15s ease;
    }

    [data-class-tab]:hover {
        color: var(--primary);
    }

    [data-class-tab].active {
        color: var(--primary);
        border-bottom-color: var(--primary);
    }

    /* The theme does not ship `inset-0` / high `z-*` utilities, so overlays are
       positioned explicitly (same approach as .admin-sidebar-backdrop). */
    .admin-modal {
        position: fixed;
        inset: 0;
        z-index: 1000;
        display: flex;
        align-items: flex-start;
        justify-content: center;
        padding: 1.25rem;
        overflow-y: auto;
        background: rgb(15 23 42 / 55%);
    }

    .admin-modal.hidden {
        display: none;
    }

    .admin-modal-dialog {
        width: 100%;
        max-width: 32rem;
        margin: auto;
    }

    .admin-combobox {
        position: relative;
        margin-top: 0.5rem;
    }

    .admin-combobox-panel {
        position: absolute;
        top: calc(100% + 0.25rem);
        left: 0;
        right: 0;
        z-index: 20;
        max-height: 15rem;
        overflow-y: auto;
        padding: 0.25rem;
        background: var(--popover, #fff);
        color: var(--popover-foreground, #111827);
        border: 1px solid var(--border, #e5e7eb);
        border-radius: 0.5rem;
        box-shadow: 0 12px 32px rgb(0 0 0 / 18%);
    }

    .admin-combobox-option {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 0.5rem;
        width: 100%;
        padding: 0.5rem 0.75rem;
        border: 0;
        border-radius: 0.375rem;
        background: transparent;
        color: inherit;
        font-size: 0.875rem;
        text-align: right;
        cursor: pointer;
    }

    .admin-combobox-option:hover,
    .admin-combobox-option[aria-selected="true"] {
        background: var(--accent, #f3f4f6);
    }

    .admin-combobox-option[aria-selected="true"] {
        color: var(--primary);
        font-weight: 600;
    }

    .admin-combobox-hint {
        color: var(--muted-foreground, #6b7280);
        font-size: 0.75rem;
    }

    .admin-combobox-empty {
        padding: 0.5rem 0.75rem;
        color: var(--muted-foreground, #6b7280);
        font-size: 0.8rem;
    }
</style>
@endpush

@push('scripts')
<script>
    (function () {
        function onReady(callback) {
            if (document.readyState !== 'loading') {
                callback();
            } else {
                document.addEventListener('DOMContentLoaded', callback);
            }
        }

        function openModal(id) {
            var modal = document.getElementById(id);

            if (!modal) {
                return;
            }

            modal.classList.remove('hidden');
            modal.setAttribute('aria-hidden', 'false');

            var search = modal.querySelector('[data-combobox-search]');

            if (search) {
                window.setTimeout(function () {
                    search.focus();
                }, 50);
            }
        }

        function closeModal(modal) {
            modal.classList.add('hidden');
            modal.setAttribute('aria-hidden', 'true');
        }

        function syncCombobox(valueInput) {
            var box = valueInput.closest('[data-combobox]');

            if (!box || !box._setComboboxValue) {
                return;
            }

            var option = box.querySelector('[data-combobox-option][data-value="' + valueInput.value + '"]');
            box._setComboboxValue(valueInput.value, option ? option.getAttribute('data-label') : '');
        }

        window.ktSyncCombobox = syncCombobox;

        function initCombobox(box) {
            var search = box.querySelector('[data-combobox-search]');
            var valueInput = box.querySelector('[data-combobox-value]');
            var panel = box.querySelector('[data-combobox-panel]');
            var empty = box.querySelector('[data-combobox-empty]');
            var options = Array.prototype.slice.call(box.querySelectorAll('[data-combobox-option]'));

            if (!search || !valueInput || !panel) {
                return;
            }

            function sync() {
                options.forEach(function (option) {
                    option.setAttribute(
                        'aria-selected',
                        option.getAttribute('data-value') === valueInput.value ? 'true' : 'false'
                    );
                });
            }

            function setValue(value, label) {
                valueInput.value = value === null || value === undefined ? '' : String(value);
                search.value = label || '';
                sync();
            }

            function open() {
                panel.classList.remove('hidden');
            }

            function close() {
                panel.classList.add('hidden');
            }

            function filter() {
                var query = search.value.trim().toLowerCase();
                var visible = 0;

                options.forEach(function (option) {
                    var haystack = (option.getAttribute('data-search') || option.getAttribute('data-label') || '').toLowerCase();
                    var match = query === '' || haystack.indexOf(query) !== -1;

                    option.classList.toggle('hidden', !match);

                    if (match) {
                        visible++;
                    }
                });

                if (empty) {
                    empty.classList.toggle('hidden', visible !== 0);
                }
            }

            search.addEventListener('focus', function () {
                filter();
                open();
            });

            search.addEventListener('input', function () {
                valueInput.value = '';
                sync();
                filter();
                open();
            });

            options.forEach(function (option) {
                option.addEventListener('click', function () {
                    setValue(option.getAttribute('data-value'), option.getAttribute('data-label'));
                    close();
                });
            });

            document.addEventListener('click', function (event) {
                if (!box.contains(event.target)) {
                    close();
                }
            });

            box._setComboboxValue = setValue;
            sync();
        }

        onReady(function () {
            // Tabs: switch panels without a page reload.
            document.querySelectorAll('[data-class-tabs]').forEach(function (wrap) {
                var buttons = wrap.querySelectorAll('[data-class-tab]');
                var panels = wrap.querySelectorAll('[data-class-tab-panel]');

                function activate(name) {
                    buttons.forEach(function (button) {
                        var isActive = button.getAttribute('data-class-tab') === name;
                        button.classList.toggle('active', isActive);
                        button.setAttribute('aria-selected', isActive ? 'true' : 'false');
                    });

                    panels.forEach(function (panel) {
                        panel.classList.toggle('hidden', panel.getAttribute('data-class-tab-panel') !== name);
                    });
                }

                buttons.forEach(function (button) {
                    button.addEventListener('click', function () {
                        activate(button.getAttribute('data-class-tab'));
                    });
                });

                activate(wrap.getAttribute('data-initial-tab') || 'students');
            });

            // Modals.
            document.querySelectorAll('[data-modal]').forEach(function (modal) {
                modal.querySelectorAll('[data-modal-close]').forEach(function (button) {
                    button.addEventListener('click', function () {
                        closeModal(modal);
                    });
                });

                modal.addEventListener('click', function (event) {
                    if (event.target === modal) {
                        closeModal(modal);
                    }
                });
            });

            document.querySelectorAll('[data-modal-open]').forEach(function (button) {
                button.addEventListener('click', function () {
                    openModal(button.getAttribute('data-modal-open'));
                });
            });

            // Searchable dropdowns.
            document.querySelectorAll('[data-combobox]').forEach(initCombobox);

            // Fill an edit form from the clicked row.
            document.querySelectorAll('[data-edit-form]').forEach(function (button) {
                button.addEventListener('click', function () {
                    var form = document.querySelector(button.getAttribute('data-edit-form'));

                    if (!form) {
                        return;
                    }

                    var action = button.getAttribute('data-action');

                    if (action) {
                        form.setAttribute('action', action);
                    }

                    var fill = button.getAttribute('data-fill');

                    if (!fill) {
                        return;
                    }

                    try {
                        var values = JSON.parse(fill);

                        Object.keys(values).forEach(function (name) {
                            var field = form.querySelector('[name="' + name + '"]');

                            if (!field) {
                                return;
                            }

                            field.value = values[name];

                            if (field.hasAttribute('data-combobox-value')) {
                                syncCombobox(field);
                            }
                        });
                    } catch (error) {
                        // Ignore malformed payloads.
                    }
                });
            });

            // Auto open the modal requested by the server (e.g. after clicking edit).
            document.querySelectorAll('[data-auto-open]').forEach(function (button) {
                button.click();
            });
        });
    })();
</script>
@endpush
