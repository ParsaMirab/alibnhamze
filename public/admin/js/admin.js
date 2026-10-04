(() => {
    const sidebar = document.querySelector('.admin-sidebar');
    const backdrop = document.querySelector('.admin-sidebar-backdrop');

    const setSidebar = (open) => {
        sidebar?.classList.toggle('is-open', open);
        backdrop?.classList.toggle('is-open', open);
        document.body.classList.toggle('overflow-hidden', open);
    };

    document.querySelectorAll('[data-admin-sidebar-toggle]').forEach((button) => {
        button.addEventListener('click', () => setSidebar(!sidebar?.classList.contains('is-open')));
    });

    document.querySelectorAll('[data-admin-sidebar-close]').forEach((element) => {
        element.addEventListener('click', () => setSidebar(false));
    });

    document.querySelectorAll('[data-admin-password-toggle]').forEach((button) => {
        button.addEventListener('click', () => {
            const input = button.closest('[data-admin-password]')?.querySelector('input');
            const icon = button.querySelector('i');

            if (!input) return;

            const show = input.type === 'password';
            input.type = show ? 'text' : 'password';
            icon?.classList.toggle('ki-eye', !show);
            icon?.classList.toggle('ki-eye-slash', show);
        });
    });

    document.querySelectorAll('[data-confirm-delete]').forEach((form) => {
        form.addEventListener('submit', (event) => {
            const message = form.dataset.confirmDelete
                || 'آیا از حذف این پرسش متداول مطمئن هستید؟ این عملیات قابل بازگشت نیست.';

            if (!window.confirm(message)) {
                event.preventDefault();
            }
        });
    });
})();
