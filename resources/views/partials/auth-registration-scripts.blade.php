@php($fileInputs = $fileInputs ?? false)
@if ($fileInputs)
    <script>
        (function () {
            var objectUrls = new WeakMap();

            function isImageFile(file) {
                var t = (file && file.type) || '';
                if (/^image\/(jpeg|png)$/i.test(t)) return true;
                return /\.(jpe?g|png)$/i.test((file && file.name) || '');
            }

            function isPdfFile(file) {
                var t = (file && file.type) || '';
                if (t === 'application/pdf') return true;
                return /\.pdf$/i.test((file && file.name) || '');
            }

            function revokePreviewUrl(input) {
                var prev = objectUrls.get(input);
                if (prev) {
                    URL.revokeObjectURL(prev);
                    objectUrls.delete(input);
                }
            }

            function dropzoneFor(input) {
                return input && input.closest('[data-reg-owner-dropzone]');
            }

            function syncFilename(input) {
                var sid = input.getAttribute('data-filename-span');
                var el = sid ? document.getElementById(sid) : null;
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
            }

            function syncPreview(input) {
                var previewId = input.getAttribute('data-file-preview');
                var preview = previewId ? document.getElementById(previewId) : null;
                if (!preview) return;

                var img = preview.querySelector('.reg-owner-doc-preview__img');
                var pdf = preview.querySelector('.reg-owner-doc-preview__pdf');
                var pdfLabel = preview.querySelector('.reg-owner-doc-preview__pdf-label');
                var file = input.files && input.files[0];
                var zone = dropzoneFor(input);

                revokePreviewUrl(input);

                if (!file) {
                    if (img) {
                        img.hidden = true;
                        img.removeAttribute('src');
                    }
                    if (pdf) pdf.hidden = true;
                    preview.hidden = true;
                    if (zone) zone.classList.remove('reg-owner-dropzone--has-file');
                    return;
                }

                preview.hidden = false;
                if (zone) zone.classList.add('reg-owner-dropzone--has-file');

                if (isImageFile(file) && img) {
                    var url = URL.createObjectURL(file);
                    objectUrls.set(input, url);
                    img.src = url;
                    img.hidden = false;
                    if (pdf) pdf.hidden = true;
                } else if (isPdfFile(file) && pdf) {
                    if (img) {
                        img.hidden = true;
                        img.removeAttribute('src');
                    }
                    if (pdfLabel) pdfLabel.textContent = file.name;
                    pdf.hidden = false;
                } else {
                    preview.hidden = true;
                    if (zone) zone.classList.remove('reg-owner-dropzone--has-file');
                }
            }

            function syncFileUi(input) {
                syncFilename(input);
                syncPreview(input);
            }

            function clearFileUi(input) {
                try { input.value = ''; } catch (e) {}
                revokePreviewUrl(input);
                syncFileUi(input);
                var sid = input.getAttribute('data-filename-span');
                var el = sid ? document.getElementById(sid) : null;
                if (el) {
                    var span = el.querySelector('span');
                    if (span) {
                        span.classList.remove('font-semibold', 'text-red-700');
                    }
                }
            }

            window.RegOwnerFileUI = {
                sync: syncFileUi,
                clear: clearFileUi,
            };

            document.querySelectorAll('[data-registration-file]').forEach(function (input) {
                input.addEventListener('change', function () {
                    syncFileUi(input);
                });
            });

            document.querySelectorAll('[data-reg-owner-preview-clear]').forEach(function (btn) {
                btn.addEventListener('click', function () {
                    var fieldId = btn.getAttribute('data-reg-owner-preview-clear');
                    var input = fieldId ? document.getElementById(fieldId) : null;
                    if (input) clearFileUi(input);
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
