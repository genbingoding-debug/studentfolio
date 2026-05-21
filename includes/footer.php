<?php
// Lokasi: includes/footer.php
?>
    </div> <footer class="text-center py-4 text-muted mt-auto">
        <small>&copy; <?= 2026 ?>  Genbi Falah. All rights reserved.</small>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Global confirmation modal -->
    <div class="modal fade" id="globalConfirmModal" tabindex="-1" aria-labelledby="globalConfirmModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="globalConfirmModalLabel">Konfirmasi</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                </div>
                <div class="modal-body">
                    <p id="globalConfirmMessage" class="mb-0"></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-danger" id="globalConfirmBtn">Ya, lanjutkan</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Global toast notification (dijalankan bila ada $_SESSION['message']) -->
    <?php if (session_status() !== PHP_SESSION_ACTIVE) { session_start(); } ?>
    <?php if (!empty($_SESSION['message'])): 
        $msg = esc($_SESSION['message']);
        $type = isset($_SESSION['message_type']) ? $_SESSION['message_type'] : 'info';
        // Map ke kelas Bootstrap
        $bg = ($type === 'success') ? 'bg-success text-white' : (($type === 'error' || $type === 'danger') ? 'bg-danger text-white' : (($type === 'warning') ? 'bg-warning text-dark' : 'bg-primary text-white'));
    ?>
    <div class="toast-container position-fixed top-0 start-50 translate-middle-x p-3" style="z-index: 1080; pointer-events: none; margin-top:12px;">
        <div id="globalToast" class="toast <?= $bg; ?> align-items-center mx-auto" role="alert" aria-live="assertive" aria-atomic="true" style="min-width:280px; max-width:720px; pointer-events: auto;">
            <div class="d-flex">
                <div class="toast-body">
                    <?= $msg; ?>
                </div>
                <button type="button" class="btn-close me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
        </div>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var toastEl = document.getElementById('globalToast');
            if (toastEl) {
                var toast = new bootstrap.Toast(toastEl, { delay: 5000 });
                toast.show();
            }
        });
    </script>
    <?php
        // Hapus pesan agar tidak tampil berulang
        unset($_SESSION['message']);
        unset($_SESSION['message_type']);
    ?>
    <?php endif; ?>

    <script src="<?= BASE_URL ?>assets/js/main.js"></script>
</body>
</html>