<script>
    (function () {
        document.querySelectorAll('input[data-image-preview]').forEach(function (input) {
            var previewId = input.getAttribute('data-image-preview');
            var preview = previewId ? document.getElementById(previewId) : null;
            if (!preview) return;

            input.addEventListener('change', function () {
                preview.innerHTML = '';
                preview.classList.remove('is-visible');

                var file = input.files && input.files[0];
                if (!file || !file.type.startsWith('image/')) return;

                var img = document.createElement('img');
                img.alt = 'Selected photo preview';
                img.src = URL.createObjectURL(file);
                preview.appendChild(img);
                preview.classList.add('is-visible');
            });
        });
    })();
</script>
