<?php require_once '../app/views/inc/header.php'; ?>

<div class="container mt-4">
    <div class="row">
        <div class="col-md-12">
            <h2>Dashboard</h2>
            <p class="text-muted">Selamat datang, <?php echo $_SESSION['user_name']; ?>!</p>
        </div>
    </div>
    
    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h4><?php echo $data['stats']['total']; ?></h4>
                            <p class="mb-0">Total Pengaduan</p>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-clipboard-list fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card bg-warning text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h4><?php echo $data['stats']['pending']; ?></h4>
                            <p class="mb-0">Menunggu</p>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-clock fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card bg-info text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h4><?php echo $data['stats']['process']; ?></h4>
                            <p class="mb-0">Diproses</p>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-cog fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h4><?php echo $data['stats']['completed']; ?></h4>
                            <p class="mb-0">Selesai</p>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-check-circle fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Quick Actions -->
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Aksi Cepat</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <a href="<?php echo BASE_URL; ?>complaints/create" class="btn btn-primary btn-lg w-100">
                                <i class="fas fa-plus me-2"></i>Buat Pengaduan Baru
                            </a>
                        </div>
                        <div class="col-md-4 mb-3">
                            <a href="<?php echo BASE_URL; ?>complaints" class="btn btn-outline-primary btn-lg w-100">
                                <i class="fas fa-list me-2"></i>Lihat Semua Pengaduan
                            </a>
                        </div>
                        <div class="col-md-4 mb-3">
                            <a href="<?php echo BASE_URL; ?>dashboard/profile" class="btn btn-outline-secondary btn-lg w-100">
                                <i class="fas fa-user-edit me-2"></i>Edit Profil
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Recent Complaints -->
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Pengaduan Terbaru</h5>
                    <a href="<?php echo BASE_URL; ?>complaints" class="btn btn-sm btn-outline-primary">Lihat Semua</a>
                </div>
                <div class="card-body">
                    <?php if (empty($data['complaints'])) : ?>
                        <div class="text-center py-4">
                            <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                            <p class="text-muted">Belum ada pengaduan</p>
                            <a href="<?php echo BASE_URL; ?>complaints/create" class="btn btn-primary">
                                <i class="fas fa-plus me-2"></i>Buat Pengaduan Pertama
                            </a>
                        </div>
                    <?php else : ?>
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Judul</th>
                                        <th>Kategori</th>
                                        <th>Status</th>
                                        <th>Tanggal</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach (array_slice($data['complaints'], 0, 5) as $complaint) : ?>
                                        <tr>
                                            <td><?php echo $complaint->title; ?></td>
                                            <td><?php echo $complaint->category_name; ?></td>
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
                                            <td><?php echo date('d/m/Y', strtotime($complaint->created_at)); ?></td>
                                            <td>
                                                <a href="<?php echo BASE_URL; ?>complaints/show/<?php echo $complaint->id; ?>" class="btn btn-sm btn-outline-primary">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once '../app/views/inc/footer.php'; ?>
