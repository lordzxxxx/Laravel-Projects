@php($fileInputs = $fileInputs ?? false)
@if ($fileInputs)
    <script>
        (function () {
            document.querySelectorAll('[data-registration-file]').forEach(function (input) {
                var sid = input.getAttribute('data-filename-span');
                var el = sid ? document.getElementById(sid) : null;
                input.addEventListener('change', function () {
                    if (!el) return;
                    var span = el.querySelector('span');
                    var name = input.files && input.files[0] ? input.files[0].name : '';
                    var target = span || el;
                    if (name) {
                        target.textContent = 'Selected file: ' + name;
                        target.classList.remove('text-red-200');
                        el.classList.remove('text-brand-medium', 'dark:text-slate-400');
                        el.classList.add('text-brand-dark', 'dark:text-emerald-200', 'font-semibold');
                    } else {
                        target.textContent = 'No file selected.';
                        el.classList.add('text-brand-medium', 'dark:text-slate-400');
                        el.classList.remove('text-brand-dark', 'dark:text-emerald-200', 'font-semibold');
                    }
                });
            });
        })();
    </script>
@endif
<script>
    (function () {
        document.querySelectorAll('[data-registration-form]').forEach(function (form) {
            form.addEventListener('submit', function () {
                var s = form.querySelector('[data-reg-submit]');
                if (s) {
                    s.disabled = true;
                    s.setAttribute('aria-busy', 'true');
                }
            });
        });
    })();
</script>
