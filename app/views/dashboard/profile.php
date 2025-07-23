<?php require_once '../app/views/inc/header.php'; ?>

<div class="container mt-4">
    <div class="row">
        <div class="col-md-8 mx-auto">
            <div class="card">
                <div class="card-header">
                    <h4 class="mb-0">
                        <i class="fas fa-user-edit me-2"></i>Edit Profil
                    </h4>
                </div>
                <div class="card-body">
                    <?php flash('profile_message'); ?>
                    
                    <form action="<?php echo BASE_URL; ?>dashboard/profile" method="post">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="name" class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                                    <input type="text" name="name" class="form-control <?php echo (!empty($data['name_err'])) ? 'is-invalid' : ''; ?>" value="<?php echo isset($data['name']) ? $data['name'] : $data['user']->name; ?>">
                                    <span class="invalid-feedback"><?php echo $data['name_err']; ?></span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="email" class="form-label">Email</label>
                                    <input type="email" class="form-control" value="<?php echo $data['user']->email; ?>" readonly>
                                    <small class="form-text text-muted">Email tidak dapat diubah</small>
                                </div>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="phone" class="form-label">Nomor Telepon</label>
                            <input type="text" name="phone" class="form-control <?php echo (!empty($data['phone_err'])) ? 'is-invalid' : ''; ?>" value="<?php echo isset($data['phone']) ? $data['phone'] : $data['user']->phone; ?>" placeholder="Contoh: 08123456789">
                            <span class="invalid-feedback"><?php echo $data['phone_err']; ?></span>
                        </div>
                        
                        <div class="mb-3">
                            <label for="address" class="form-label">Alamat</label>
                            <textarea name="address" class="form-control <?php echo (!empty($data['address_err'])) ? 'is-invalid' : ''; ?>" rows="3" placeholder="Masukkan alamat lengkap"><?php echo isset($data['address']) ? $data['address'] : $data['user']->address; ?></textarea>
                            <span class="invalid-feedback"><?php echo $data['address_err']; ?></span>
                        </div>
                        
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label">Tanggal Bergabung</label>
                                <input type="text" class="form-control" value="<?php echo date('d/m/Y', strtotime($data['user']->created_at)); ?>" readonly>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Role</label>
                                <input type="text" class="form-control" value="<?php echo ucfirst($data['user']->role); ?>" readonly>
                            </div>
                        </div>
                        
                        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                            <a href="<?php echo BASE_URL; ?>dashboard" class="btn btn-secondary me-md-2">
                                <i class="fas fa-arrow-left me-2"></i>Kembali
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-2"></i>Simpan Perubahan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
            
            <!-- Change Password Section -->
            <div class="card mt-4">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-key me-2"></i>Ubah Password
                    </h5>
                </div>
                <div class="card-body">
                    <form action="<?php echo BASE_URL; ?>dashboard/changePassword" method="post">
                        <div class="mb-3">
                            <label for="current_password" class="form-label">Password Saat Ini <span class="text-danger">*</span></label>
                            <input type="password" name="current_password" class="form-control" required>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="new_password" class="form-label">Password Baru <span class="text-danger">*</span></label>
                                    <input type="password" name="new_password" class="form-control" required>
                                    <small class="form-text text-muted">Minimal 6 karakter</small>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="confirm_password" class="form-label">Konfirmasi Password Baru <span class="text-danger">*</span></label>
                                    <input type="password" name="confirm_password" class="form-control" required>
                                </div>
                            </div>
                        </div>
                        
                        <div class="d-grid">
                            <button type="submit" class="btn btn-warning">
                                <i class="fas fa-key me-2"></i>Ubah Password
                            </button>
                        </div>
                    </form>
                </div>
            </div>
            
            <!-- Account Statistics -->
            <div class="card mt-4">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-chart-pie me-2"></i>Statistik Akun
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-md-3">
                            <div class="border-end">
                                <h4 class="text-primary"><?php echo isset($data['stats']) ? $data['stats']['total'] : 0; ?></h4>
                                <p class="text-muted mb-0">Total Pengaduan</p>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="border-end">
                                <h4 class="text-warning"><?php echo isset($data['stats']) ? $data['stats']['pending'] : 0; ?></h4>
                                <p class="text-muted mb-0">Menunggu</p>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="border-end">
                                <h4 class="text-info"><?php echo isset($data['stats']) ? $data['stats']['process'] : 0; ?></h4>
                                <p class="text-muted mb-0">Diproses</p>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <h4 class="text-success"><?php echo isset($data['stats']) ? $data['stats']['completed'] : 0; ?></h4>
                            <p class="text-muted mb-0">Selesai</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once '../app/views/inc/footer.php'; ?>
