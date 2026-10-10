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

        onReady(function () {
            document.querySelectorAll('[data-parent-toggle]').forEach(function (toggle) {
                var target = document.querySelector(toggle.getAttribute('data-parent-toggle'));

                if (!target) {
                    return;
                }

                function apply() {
                    target.classList.toggle('hidden', !toggle.checked);
                }

                toggle.addEventListener('change', apply);
                apply();
            });
        });
    })();
</script>
@endpush
