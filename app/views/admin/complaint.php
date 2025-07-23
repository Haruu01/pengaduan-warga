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
                        <a class="nav-link active" href="<?php echo BASE_URL; ?>admin/complaints">
                            <i class="fas fa-clipboard-list me-2"></i>Kelola Pengaduan
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo BASE_URL; ?>admin/categories">
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
                <h1 class="h2">Detail Pengaduan</h1>
                <a href="<?php echo BASE_URL; ?>admin/complaints" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left me-2"></i>Kembali
                </a>
            </div>
            
            <?php flash('complaint_message'); ?>
            
            <div class="row">
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5 class="mb-0">Informasi Pengaduan</h5>
                            <?php
                            $statusClass = '';
                            $statusText = '';
                            switch ($data['complaint']->status) {
                                case 'pending':
                                    $statusClass = 'warning';
                                    $statusText = 'Menunggu';
                                    break;
                                case 'process':
                                    $statusClass = 'info';
                                    $statusText = 'Diproses';
                                    break;
                                case 'completed':
                                    $statusClass = 'success';
                                    $statusText = 'Selesai';
                                    break;
                                case 'rejected':
                                    $statusClass = 'danger';
                                    $statusText = 'Ditolak';
                                    break;
                            }
                            ?>
                            <span class="badge bg-<?php echo $statusClass; ?> fs-6"><?php echo $statusText; ?></span>
                        </div>
                        <div class="card-body">
                            <div class="row mb-3">
                                <div class="col-sm-3"><strong>ID Pengaduan:</strong></div>
                                <div class="col-sm-9">#<?php echo str_pad($data['complaint']->id, 6, '0', STR_PAD_LEFT); ?></div>
                            </div>
                            
                            <div class="row mb-3">
                                <div class="col-sm-3"><strong>Kategori:</strong></div>
                                <div class="col-sm-9"><?php echo $data['complaint']->category_name; ?></div>
                            </div>
                            
                            <div class="row mb-3">
                                <div class="col-sm-3"><strong>Judul:</strong></div>
                                <div class="col-sm-9"><?php echo $data['complaint']->title; ?></div>
                            </div>
                            
                            <div class="row mb-3">
                                <div class="col-sm-3"><strong>Deskripsi:</strong></div>
                                <div class="col-sm-9"><?php echo nl2br($data['complaint']->description); ?></div>
                            </div>
                            
                            <div class="row mb-3">
                                <div class="col-sm-3"><strong>Lokasi:</strong></div>
                                <div class="col-sm-9"><?php echo $data['complaint']->location; ?></div>
                            </div>
                            
                            <div class="row mb-3">
                                <div class="col-sm-3"><strong>Tanggal Dibuat:</strong></div>
                                <div class="col-sm-9"><?php echo date('d/m/Y H:i:s', strtotime($data['complaint']->created_at)); ?></div>
                            </div>
                            
                            <div class="row mb-3">
                                <div class="col-sm-3"><strong>Terakhir Update:</strong></div>
                                <div class="col-sm-9"><?php echo date('d/m/Y H:i:s', strtotime($data['complaint']->updated_at)); ?></div>
                            </div>
                            
                            <?php if (!empty($data['complaint']->photo)) : ?>
                                <div class="row mb-3">
                                    <div class="col-sm-3"><strong>Foto:</strong></div>
                                    <div class="col-sm-9">
                                        <img src="<?php echo BASE_URL; ?>public/uploads/<?php echo $data['complaint']->photo; ?>"
                                             class="img-fluid rounded"
                                             style="max-height: 300px;"
                                             alt="Foto Pengaduan"
                                             onerror="this.src='<?php echo BASE_URL; ?>public/images/no-image.svg'"
                                             onclick="openImageModal(this.src)">
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-4">
                    <!-- Pelapor Info -->
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5 class="mb-0">Informasi Pelapor</h5>
                        </div>
                        <div class="card-body">
                            <p><strong>Nama:</strong><br><?php echo $data['complaint']->user_name; ?></p>
                            <p><strong>Email:</strong><br><?php echo $data['complaint']->user_email; ?></p>
                            <?php if (!empty($data['complaint']->user_phone)) : ?>
                                <p><strong>Telepon:</strong><br><?php echo $data['complaint']->user_phone; ?></p>
                            <?php endif; ?>
                        </div>
                    </div>
                    
                    <!-- Update Status -->
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0">Update Status</h5>
                        </div>
                        <div class="card-body">
                            <form action="<?php echo BASE_URL; ?>admin/complaint/<?php echo $data['complaint']->id; ?>" method="post">
                                <div class="mb-3">
                                    <label for="status" class="form-label">Status</label>
                                    <select name="status" class="form-select" required>
                                        <option value="pending" <?php echo ($data['complaint']->status == 'pending') ? 'selected' : ''; ?>>Menunggu</option>
                                        <option value="process" <?php echo ($data['complaint']->status == 'process') ? 'selected' : ''; ?>>Diproses</option>
                                        <option value="completed" <?php echo ($data['complaint']->status == 'completed') ? 'selected' : ''; ?>>Selesai</option>
                                        <option value="rejected" <?php echo ($data['complaint']->status == 'rejected') ? 'selected' : ''; ?>>Ditolak</option>
                                    </select>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="response" class="form-label">Tanggapan Admin</label>
                                    <textarea name="response" class="form-control" rows="4" placeholder="Berikan tanggapan untuk pengaduan ini..."><?php echo $data['complaint']->admin_response; ?></textarea>
                                </div>
                                
                                <div class="d-grid">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-save me-2"></i>Update Status
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Image Modal -->
<div class="modal fade" id="imageModal" tabindex="-1">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Foto Pengaduan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center">
                <img id="modalImage" src="" class="img-fluid" alt="Foto Pengaduan">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

<script>
function openImageModal(imageSrc) {
    document.getElementById('modalImage').src = imageSrc;
    var imageModal = new bootstrap.Modal(document.getElementById('imageModal'));
    imageModal.show();
}
</script>

<?php require_once '../app/views/inc/footer.php'; ?>
