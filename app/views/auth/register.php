<?php require_once '../app/views/inc/header.php'; ?>

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h4 class="text-center mb-0">
                        <i class="fas fa-user-plus me-2"></i>Daftar Akun Baru
                    </h4>
                </div>
                <div class="card-body">
                    <form action="<?php echo BASE_URL; ?>auth/register" method="post">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="name" class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                                    <input type="text" name="name" class="form-control <?php echo (!empty($data['name_err'])) ? 'is-invalid' : ''; ?>" value="<?php echo $data['name']; ?>">
                                    <span class="invalid-feedback"><?php echo $data['name_err']; ?></span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                                    <input type="email" name="email" class="form-control <?php echo (!empty($data['email_err'])) ? 'is-invalid' : ''; ?>" value="<?php echo $data['email']; ?>">
                                    <span class="invalid-feedback"><?php echo $data['email_err']; ?></span>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="password" class="form-label">Password <span class="text-danger">*</span></label>
                                    <input type="password" name="password" class="form-control <?php echo (!empty($data['password_err'])) ? 'is-invalid' : ''; ?>" value="<?php echo $data['password']; ?>">
                                    <span class="invalid-feedback"><?php echo $data['password_err']; ?></span>
                                    <small class="form-text text-muted">Minimal 6 karakter</small>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="confirm_password" class="form-label">Konfirmasi Password <span class="text-danger">*</span></label>
                                    <input type="password" name="confirm_password" class="form-control <?php echo (!empty($data['confirm_password_err'])) ? 'is-invalid' : ''; ?>" value="<?php echo $data['confirm_password']; ?>">
                                    <span class="invalid-feedback"><?php echo $data['confirm_password_err']; ?></span>
                                </div>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="phone" class="form-label">Nomor Telepon</label>
                            <input type="text" name="phone" class="form-control <?php echo (!empty($data['phone_err'])) ? 'is-invalid' : ''; ?>" value="<?php echo $data['phone']; ?>">
                            <span class="invalid-feedback"><?php echo $data['phone_err']; ?></span>
                        </div>
                        
                        <div class="mb-3">
                            <label for="address" class="form-label">Alamat</label>
                            <textarea name="address" class="form-control <?php echo (!empty($data['address_err'])) ? 'is-invalid' : ''; ?>" rows="3"><?php echo $data['address']; ?></textarea>
                            <span class="invalid-feedback"><?php echo $data['address_err']; ?></span>
                        </div>
                        
                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-user-plus me-2"></i>Daftar
                            </button>
                        </div>
                    </form>
                    
                    <div class="text-center mt-3">
                        <p>Sudah punya akun? <a href="<?php echo BASE_URL; ?>auth/login">Login di sini</a></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once '../app/views/inc/footer.php'; ?>
