    <!-- Footer -->
    <footer class="footer mt-auto">
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <h5><?php echo APP_NAME; ?></h5>
                    <p>Sistem pengaduan warga untuk melayani masyarakat dengan lebih baik.</p>
                </div>
                <div class="col-md-3">
                    <h6>Menu</h6>
                    <ul class="list-unstyled">
                        <li><a href="<?php echo BASE_URL; ?>" class="text-light">Beranda</a></li>
                        <li><a href="<?php echo BASE_URL; ?>home/about" class="text-light">Tentang</a></li>
                        <li><a href="<?php echo BASE_URL; ?>home/contact" class="text-light">Kontak</a></li>
                    </ul>
                </div>
                <div class="col-md-3">
                    <h6>Kontak</h6>
                    <p class="mb-1"><i class="fas fa-phone me-2"></i>(021) 123-4567</p>
                    <p class="mb-1"><i class="fas fa-envelope me-2"></i>info@pengaduan.com</p>
                    <p><i class="fas fa-map-marker-alt me-2"></i>Jakarta, Indonesia</p>
                </div>
            </div>
            <hr class="my-4">
            <div class="row">
                <div class="col-md-12 text-center">
                    <p>&copy; <?php echo date('Y'); ?> <?php echo APP_NAME; ?>. All rights reserved.</p>
                </div>
            </div>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Custom JS -->
    <script src="<?php echo BASE_URL; ?>js/main.js"></script>
</body>
</html>
