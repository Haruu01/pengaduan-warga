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
                <h1 class="h2">Kelola Pengaduan</h1>
            </div>
            
            <?php flash('complaint_message'); ?>
            
            <?php if (empty($data['complaints'])) : ?>
                <div class="card">
                    <div class="card-body text-center py-5">
                        <i class="fas fa-inbox fa-4x text-muted mb-4"></i>
                        <h4 class="text-muted">Belum Ada Pengaduan</h4>
                        <p class="text-muted">Belum ada pengaduan yang masuk ke sistem.</p>
                    </div>
                </div>
            <?php else : ?>
                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead class="table-dark">
                                    <tr>
                                        <th>ID</th>
                                        <th>Tanggal</th>
                                        <th>Pelapor</th>
                                        <th>Kategori</th>
                                        <th>Judul</th>
                                        <th>Lokasi</th>
                                        <th>Foto</th>
                                        <th>Status</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($data['complaints'] as $complaint) : ?>
                                        <tr>
                                            <td>#<?php echo str_pad($complaint->id, 6, '0', STR_PAD_LEFT); ?></td>
                                            <td><?php echo date('d/m/Y H:i', strtotime($complaint->created_at)); ?></td>
                                            <td>
                                                <div>
                                                    <strong><?php echo $complaint->user_name; ?></strong><br>
                                                    <small class="text-muted"><?php echo $complaint->user_email; ?></small>
                                                </div>
                                            </td>
                                            <td><?php echo $complaint->category_name; ?></td>
                                            <td><?php echo substr($complaint->title, 0, 30) . '...'; ?></td>
                                            <td><?php echo substr($complaint->location, 0, 20) . '...'; ?></td>
                                            <td>
                                                <?php if (!empty($complaint->photo)) : ?>
                                                    <button type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#photoModal<?php echo $complaint->id; ?>">
                                                        <i class="fas fa-image"></i> Lihat Foto
                                                    </button>

                                                    <!-- Photo Modal -->
                                                    <div class="modal fade" id="photoModal<?php echo $complaint->id; ?>" tabindex="-1">
                                                        <div class="modal-dialog modal-lg">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <h5 class="modal-title">Foto Pengaduan #<?php echo str_pad($complaint->id, 6, '0', STR_PAD_LEFT); ?></h5>
                                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                                </div>
                                                                <div class="modal-body text-center">
                                                                    <img src="<?php echo BASE_URL; ?>public/uploads/<?php echo $complaint->photo; ?>"
                                                                         class="img-fluid rounded"
                                                                         alt="Foto Pengaduan"
                                                                         style="max-height: 500px;"
                                                                         onerror="this.src='<?php echo BASE_URL; ?>public/images/no-image.svg'">
                                                                    <div class="mt-3">
                                                                        <p><strong>Judul:</strong> <?php echo htmlspecialchars($complaint->title); ?></p>
                                                                        <p><strong>Lokasi:</strong> <?php echo htmlspecialchars($complaint->location); ?></p>
                                                                        <p><strong>Deskripsi:</strong></p>
                                                                        <p class="text-start"><?php echo nl2br(htmlspecialchars($complaint->description)); ?></p>
                                                                    </div>
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <a href="<?php echo BASE_URL; ?>public/uploads/<?php echo $complaint->photo; ?>"
                                                                       class="btn btn-primary"
                                                                       target="_blank">
                                                                        <i class="fas fa-download"></i> Download Foto
                                                                    </a>
                                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                <?php else : ?>
                                                    <span class="text-muted">
                                                        <i class="fas fa-image-slash"></i> Tidak ada foto
                                                    </span>
                                                <?php endif; ?>
                                            </td>
                                            <td>
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
                                            </td>
                                            <td>
                                                <a href="<?php echo BASE_URL; ?>admin/complaint/<?php echo $complaint->id; ?>" 
                                                   class="btn btn-sm btn-outline-primary" 
                                                   data-bs-toggle="tooltip" 
                                                   title="Lihat Detail">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php require_once '../app/views/inc/footer.php'; ?>
