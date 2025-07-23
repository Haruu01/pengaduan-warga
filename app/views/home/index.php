<?php require_once '../app/views/inc/header.php'; ?>

<!-- Hero Section -->
<section class="hero-section">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6">
                <h1 class="display-4 fw-bold mb-4">Sistem Pengaduan Warga</h1>
                <p class="lead mb-4">Sampaikan keluhan dan aspirasi Anda untuk pembangunan yang lebih baik. Kami siap mendengar dan menindaklanjuti setiap pengaduan Anda.</p>
                <?php if (!isset($_SESSION['user_id'])) : ?>
                    <a href="<?php echo BASE_URL; ?>auth/register" class="btn btn-light btn-lg me-3">
                        <i class="fas fa-user-plus me-2"></i>Daftar Sekarang
                    </a>
                    <a href="<?php echo BASE_URL; ?>auth/login" class="btn btn-outline-light btn-lg">
                        <i class="fas fa-sign-in-alt me-2"></i>Login
                    </a>
                <?php else : ?>
                    <a href="<?php echo BASE_URL; ?>complaints/create" class="btn btn-light btn-lg">
                        <i class="fas fa-plus me-2"></i>Buat Pengaduan
                    </a>
                <?php endif; ?>
            </div>
            <div class="col-lg-6">
                <img src="<?php echo BASE_URL; ?>images/hero-illustration.svg" alt="Hero" class="img-fluid">
            </div>
        </div>
    </div>
</section>

<!-- Statistics Section -->
<section class="py-5">
    <div class="container">
        <div class="row text-center">
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="card h-100">
                    <div class="card-body">
                        <i class="fas fa-clipboard-list fa-3x text-primary mb-3"></i>
                        <h3 class="card-title"><?php echo $data['stats']['total']; ?></h3>
                        <p class="card-text">Total Pengaduan</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="card h-100">
                    <div class="card-body">
                        <i class="fas fa-clock fa-3x text-warning mb-3"></i>
                        <h3 class="card-title"><?php echo $data['stats']['pending']; ?></h3>
                        <p class="card-text">Menunggu</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="card h-100">
                    <div class="card-body">
                        <i class="fas fa-cog fa-3x text-info mb-3"></i>
                        <h3 class="card-title"><?php echo $data['stats']['process']; ?></h3>
                        <p class="card-text">Diproses</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="card h-100">
                    <div class="card-body">
                        <i class="fas fa-check-circle fa-3x text-success mb-3"></i>
                        <h3 class="card-title"><?php echo $data['stats']['completed']; ?></h3>
                        <p class="card-text">Selesai</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Categories Section -->
<section class="py-5 bg-light">
    <div class="container">
        <div class="row">
            <div class="col-lg-12 text-center mb-5">
                <h2 class="fw-bold">Kategori Pengaduan</h2>
                <p class="lead">Pilih kategori yang sesuai dengan pengaduan Anda</p>
            </div>
        </div>
        <div class="row">
            <?php foreach ($data['categories'] as $category) : ?>
                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="card h-100">
                        <div class="card-body text-center">
                            <i class="fas fa-folder fa-3x text-primary mb-3"></i>
                            <h5 class="card-title"><?php echo $category->name; ?></h5>
                            <p class="card-text"><?php echo $category->description; ?></p>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- How it works Section -->
<section class="py-5">
    <div class="container">
        <div class="row">
            <div class="col-lg-12 text-center mb-5">
                <h2 class="fw-bold">Cara Kerja</h2>
                <p class="lead">Proses pengaduan yang mudah dan transparan</p>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-3 col-md-6 mb-4 text-center">
                <div class="mb-3">
                    <div class="bg-primary text-white rounded-circle d-inline-flex align-items-center justify-content-center" style="width: 80px; height: 80px;">
                        <span class="fs-3 fw-bold">1</span>
                    </div>
                </div>
                <h5>Daftar/Login</h5>
                <p>Buat akun atau login untuk mulai membuat pengaduan</p>
            </div>
            <div class="col-lg-3 col-md-6 mb-4 text-center">
                <div class="mb-3">
                    <div class="bg-primary text-white rounded-circle d-inline-flex align-items-center justify-content-center" style="width: 80px; height: 80px;">
                        <span class="fs-3 fw-bold">2</span>
                    </div>
                </div>
                <h5>Buat Pengaduan</h5>
                <p>Isi form pengaduan dengan detail yang jelas dan lengkap</p>
            </div>
            <div class="col-lg-3 col-md-6 mb-4 text-center">
                <div class="mb-3">
                    <div class="bg-primary text-white rounded-circle d-inline-flex align-items-center justify-content-center" style="width: 80px; height: 80px;">
                        <span class="fs-3 fw-bold">3</span>
                    </div>
                </div>
                <h5>Proses Verifikasi</h5>
                <p>Tim kami akan memverifikasi dan memproses pengaduan Anda</p>
            </div>
            <div class="col-lg-3 col-md-6 mb-4 text-center">
                <div class="mb-3">
                    <div class="bg-primary text-white rounded-circle d-inline-flex align-items-center justify-content-center" style="width: 80px; height: 80px;">
                        <span class="fs-3 fw-bold">4</span>
                    </div>
                </div>
                <h5>Tindak Lanjut</h5>
                <p>Dapatkan update status dan tindak lanjut pengaduan</p>
            </div>
        </div>
    </div>
</section>

<?php require_once '../app/views/inc/footer.php'; ?>
