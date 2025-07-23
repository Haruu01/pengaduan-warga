<?php require_once '../app/views/inc/header.php'; ?>

<div class="container mt-4">
    <div class="row">
        <div class="col-md-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2>Pengaduan Saya</h2>
                <a href="<?php echo BASE_URL; ?>complaints/create" class="btn btn-primary">
                    <i class="fas fa-plus me-2"></i>Buat Pengaduan
                </a>
            </div>
            
            <?php flash('complaint_message'); ?>
            
            <?php if (empty($data['complaints'])) : ?>
                <div class="card">
                    <div class="card-body text-center py-5">
                        <i class="fas fa-inbox fa-4x text-muted mb-4"></i>
                        <h4 class="text-muted">Belum Ada Pengaduan</h4>
                        <p class="text-muted mb-4">Anda belum membuat pengaduan apapun. Mulai buat pengaduan pertama Anda.</p>
                        <a href="<?php echo BASE_URL; ?>complaints/create" class="btn btn-primary">
                            <i class="fas fa-plus me-2"></i>Buat Pengaduan Pertama
                        </a>
                    </div>
                </div>
            <?php else : ?>
                <div class="row">
                    <?php foreach ($data['complaints'] as $complaint) : ?>
                        <div class="col-md-6 col-lg-4 mb-4">
                            <div class="card complaint-card h-100">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-start mb-2">
                                        <h6 class="card-title mb-0"><?php echo $complaint->title; ?></h6>
                                        <?php
                                        $statusClass = '';
                                        $statusText = '';
                                        switch ($complaint->status) {
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
                                        <span class="badge bg-<?php echo $statusClass; ?>"><?php echo $statusText; ?></span>
                                    </div>
                                    
                                    <p class="card-text text-muted small mb-2">
                                        <i class="fas fa-folder me-1"></i><?php echo $complaint->category_name; ?>
                                    </p>
                                    
                                    <p class="card-text"><?php echo substr($complaint->description, 0, 100) . '...'; ?></p>
                                    
                                    <p class="card-text">
                                        <small class="text-muted">
                                            <i class="fas fa-map-marker-alt me-1"></i><?php echo $complaint->location; ?>
                                        </small>
                                    </p>
                                    
                                    <p class="card-text">
                                        <small class="text-muted">
                                            <i class="fas fa-calendar me-1"></i><?php echo date('d/m/Y H:i', strtotime($complaint->created_at)); ?>
                                        </small>
                                    </p>
                                </div>
                                <div class="card-footer bg-transparent">
                                    <div class="d-flex justify-content-between">
                                        <a href="<?php echo BASE_URL; ?>complaints/show/<?php echo $complaint->id; ?>" class="btn btn-sm btn-outline-primary">
                                            <i class="fas fa-eye me-1"></i>Detail
                                        </a>
                                        
                                        <?php if ($complaint->status == 'pending') : ?>
                                            <a href="<?php echo BASE_URL; ?>complaints/delete/<?php echo $complaint->id; ?>" 
                                               class="btn btn-sm btn-outline-danger" 
                                               onclick="return confirmDelete('Apakah Anda yakin ingin menghapus pengaduan ini?')">
                                                <i class="fas fa-trash me-1"></i>Hapus
                                            </a>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php require_once '../app/views/inc/footer.php'; ?>
