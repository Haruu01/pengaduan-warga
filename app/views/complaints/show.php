<?php require_once '../app/views/inc/header.php'; ?>

<div class="container mt-4">
    <div class="row">
        <div class="col-md-8 mx-auto">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">Detail Pengaduan</h4>
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
                                <img src="<?php echo BASE_URL; ?>uploads/<?php echo $data['complaint']->photo; ?>"
                                     class="img-fluid rounded" 
                                     style="max-height: 300px;" 
                                     alt="Foto Pengaduan">
                            </div>
                        </div>
                    <?php endif; ?>
                    
                    <?php if (!empty($data['complaint']->admin_response)) : ?>
                        <div class="row mb-3">
                            <div class="col-sm-3"><strong>Tanggapan Admin:</strong></div>
                            <div class="col-sm-9">
                                <div class="alert alert-info">
                                    <?php echo nl2br($data['complaint']->admin_response); ?>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
                <div class="card-footer">
                    <div class="d-flex justify-content-between">
                        <a href="<?php echo BASE_URL; ?>complaints" class="btn btn-secondary">
                            <i class="fas fa-arrow-left me-2"></i>Kembali
                        </a>
                        
                        <?php if ($data['complaint']->status == 'pending') : ?>
                            <a href="<?php echo BASE_URL; ?>complaints/delete/<?php echo $data['complaint']->id; ?>" 
                               class="btn btn-danger" 
                               onclick="return confirmDelete('Apakah Anda yakin ingin menghapus pengaduan ini?')">
                                <i class="fas fa-trash me-2"></i>Hapus Pengaduan
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once '../app/views/inc/footer.php'; ?>
