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

    <script src="<?= BASE_URL ?>assets/js/main.js"></script>
</body>
</html>