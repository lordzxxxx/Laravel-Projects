{{-- Sync ?per_page=5 on viewports ≤768px so property listings paginate 5 per page on phones. --}}
<script>
(function () {
    if (window.__exploreStaysPerPageSync) return;
    window.__exploreStaysPerPageSync = true;

    var mq = window.matchMedia('(max-width: 768px)');

    function desiredPerPage() {
        return mq.matches ? '5' : '12';
    }

    function syncPerPageInUrl() {
        var url = new URL(window.location.href);
        var current = url.searchParams.get('per_page');

        if (mq.matches) {
            if (current === '5') {
                return;
            }
            url.searchParams.set('per_page', '5');
            window.location.replace(url.toString());
            return;
        }

        if (current === '5') {
            url.searchParams.delete('per_page');
            window.location.replace(url.toString());
        }
    }

    function syncHiddenInputs() {
        var want = desiredPerPage();
        document.querySelectorAll('.explore-stays-filters__per-page').forEach(function (input) {
            input.value = want;
        });
    }

    mq.addEventListener('change', function () {
        syncHiddenInputs();
        syncPerPageInUrl();
    });

    syncHiddenInputs();
    syncPerPageInUrl();
})();
</script>
