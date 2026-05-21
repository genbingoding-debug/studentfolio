document.addEventListener('DOMContentLoaded', function() {
    // Tempatkan skrip umum yang ingin dijalankan di semua halaman
    // Contoh: inisialisasi tooltip, validasi singkat, atau manipulasi DOM ringan
    console.log('main.js terpasang dan siap dijalankan');

    var confirmModalEl = document.getElementById('globalConfirmModal');
    var confirmMessageEl = document.getElementById('globalConfirmMessage');
    var confirmBtn = document.getElementById('globalConfirmBtn');
    var confirmModal = confirmModalEl ? new bootstrap.Modal(confirmModalEl) : null;
    var confirmTargetHref = null;

    if (confirmBtn) {
        confirmBtn.addEventListener('click', function() {
            if (confirmTargetHref) {
                window.location.href = confirmTargetHref;
            }
        });
    }

    document.body.addEventListener('click', function(event) {
        var target = event.target;
        while (target && target !== document.body) {
            if (target.matches('a.confirm-link[data-confirm]')) {
                event.preventDefault();

                var message = target.getAttribute('data-confirm');
                confirmTargetHref = target.href;

                if (confirmMessageEl) {
                    confirmMessageEl.textContent = message;
                }

                if (confirmModal) {
                    confirmModal.show();
                } else {
                    if (window.confirm(message)) {
                        window.location.href = confirmTargetHref;
                    }
                }
                return;
            }
            target = target.parentElement;
        }
    });
});
