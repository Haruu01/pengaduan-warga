<?php require_once '../app/views/inc/header.php'; ?>

<div class="container mt-4">
    <div class="row">
        <div class="col-md-8 mx-auto">
            <div class="card">
                <div class="card-header">
                    <h4 class="mb-0">
                        <i class="fas fa-plus me-2"></i>Buat Pengaduan Baru
                    </h4>
                </div>
                <div class="card-body">
                    <form action="<?php echo BASE_URL; ?>complaints/create" method="post" enctype="multipart/form-data">
                        <div class="mb-3">
                            <label for="category_id" class="form-label">Kategori <span class="text-danger">*</span></label>
                            <select name="category_id" class="form-select <?php echo (!empty($data['category_id_err'])) ? 'is-invalid' : ''; ?>">
                                <option value="">Pilih Kategori</option>
                                <?php foreach ($data['categories'] as $category) : ?>
                                    <option value="<?php echo $category->id; ?>" <?php echo ($data['category_id'] == $category->id) ? 'selected' : ''; ?>>
                                        <?php echo $category->name; ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <span class="invalid-feedback"><?php echo $data['category_id_err']; ?></span>
                        </div>
                        
                        <div class="mb-3">
                            <label for="title" class="form-label">Judul Pengaduan <span class="text-danger">*</span></label>
                            <input type="text" name="title" class="form-control <?php echo (!empty($data['title_err'])) ? 'is-invalid' : ''; ?>" value="<?php echo $data['title']; ?>" placeholder="Masukkan judul pengaduan">
                            <span class="invalid-feedback"><?php echo $data['title_err']; ?></span>
                        </div>
                        
                        <div class="mb-3">
                            <label for="description" class="form-label">Deskripsi <span class="text-danger">*</span></label>
                            <textarea name="description" class="form-control <?php echo (!empty($data['description_err'])) ? 'is-invalid' : ''; ?>" rows="5" placeholder="Jelaskan detail pengaduan Anda"><?php echo $data['description']; ?></textarea>
                            <span class="invalid-feedback"><?php echo $data['description_err']; ?></span>
                        </div>
                        
                        <div class="mb-3">
                            <label for="location" class="form-label">Lokasi <span class="text-danger">*</span></label>
                            <input type="text" name="location" class="form-control <?php echo (!empty($data['location_err'])) ? 'is-invalid' : ''; ?>" value="<?php echo $data['location']; ?>" placeholder="Masukkan lokasi kejadian">
                            <span class="invalid-feedback"><?php echo $data['location_err']; ?></span>
                        </div>
                        
                        <div class="mb-3">
                            <label for="photo" class="form-label">Foto Pendukung</label>
                            <input type="file" name="photo" class="form-control <?php echo (!empty($data['photo_err'])) ? 'is-invalid' : ''; ?>" accept="image/*" onchange="previewImage(this)">
                            <span class="invalid-feedback"><?php echo $data['photo_err']; ?></span>
                            <small class="form-text text-muted">Format: JPG, JPEG, PNG, GIF. Maksimal 5MB</small>
                            <div id="imagePreview" class="mt-2"></div>
                        </div>
                        
                        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                            <a href="<?php echo BASE_URL; ?>dashboard" class="btn btn-secondary me-md-2">
                                <i class="fas fa-arrow-left me-2"></i>Kembali
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-paper-plane me-2"></i>Kirim Pengaduan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once '../app/views/inc/footer.php'; ?>
