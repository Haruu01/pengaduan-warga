<?php require_once '../app/views/inc/header.php'; ?>

<div class="container-fluid mt-4">
    <div class="row">
        <!-- Sidebar -->
        <div class="col-md-3 col-lg-2 admin-sidebar">
            <div class="position-sticky pt-3">
                <ul class="nav flex-column">
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo BASE_URL; ?>admin">
                            <i class="fas fa-tachometer-alt me-2"></i>Dashboard
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo BASE_URL; ?>admin/complaints">
                            <i class="fas fa-clipboard-list me-2"></i>Kelola Pengaduan
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="<?php echo BASE_URL; ?>admin/categories">
                            <i class="fas fa-folder me-2"></i>Kelola Kategori
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo BASE_URL; ?>admin/reports">
                            <i class="fas fa-chart-bar me-2"></i>Laporan
                        </a>
                    </li>
                </ul>
            </div>
        </div>
        
        <!-- Main Content -->
        <div class="col-md-9 col-lg-10">
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                <h1 class="h2">Tambah Kategori</h1>
                <a href="<?php echo BASE_URL; ?>admin/categories" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left me-2"></i>Kembali
                </a>
            </div>
            
            <div class="row">
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0">Form Tambah Kategori</h5>
                        </div>
                        <div class="card-body">
                            <form action="<?php echo BASE_URL; ?>admin/addCategory" method="post">
                                <div class="mb-3">
                                    <label for="name" class="form-label">Nama Kategori <span class="text-danger">*</span></label>
                                    <input type="text" name="name" class="form-control <?php echo (!empty($data['name_err'])) ? 'is-invalid' : ''; ?>" value="<?php echo $data['name']; ?>" placeholder="Masukkan nama kategori">
                                    <span class="invalid-feedback"><?php echo $data['name_err']; ?></span>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="description" class="form-label">Deskripsi</label>
                                    <textarea name="description" class="form-control <?php echo (!empty($data['description_err'])) ? 'is-invalid' : ''; ?>" rows="4" placeholder="Masukkan deskripsi kategori"><?php echo $data['description']; ?></textarea>
                                    <span class="invalid-feedback"><?php echo $data['description_err']; ?></span>
                                    <small class="form-text text-muted">Deskripsi akan membantu warga memilih kategori yang tepat</small>
                                </div>
                                
                                <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                                    <a href="<?php echo BASE_URL; ?>admin/categories" class="btn btn-secondary me-md-2">
                                        <i class="fas fa-times me-2"></i>Batal
                                    </a>
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-save me-2"></i>Simpan Kategori
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-header">
                            <h6 class="mb-0">Tips Membuat Kategori</h6>
                        </div>
                        <div class="card-body">
                            <ul class="list-unstyled">
                                <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Gunakan nama yang jelas dan mudah dipahami</li>
                                <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Buat deskripsi yang informatif</li>
                                <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Hindari kategori yang terlalu spesifik</li>
                                <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Pertimbangkan kebutuhan warga</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once '../app/views/inc/footer.php'; ?>
