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
                        <a class="nav-link" href="<?php echo BASE_URL; ?>admin/categories">
                            <i class="fas fa-folder me-2"></i>Kelola Kategori
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="<?php echo BASE_URL; ?>admin/reports">
                            <i class="fas fa-chart-bar me-2"></i>Laporan
                        </a>
                    </li>
                </ul>
            </div>
        </div>
        
        <!-- Main Content -->
        <div class="col-md-9 col-lg-10">
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                <h1 class="h2">Laporan Pengaduan</h1>
                <div class="btn-toolbar mb-2 mb-md-0 no-print">
                    <button type="button" class="btn btn-outline-secondary me-2" onclick="printReport()">
                        <i class="fas fa-print me-2"></i>Print Laporan
                    </button>
                    <a href="<?php echo BASE_URL; ?>admin/exportExcel?<?php echo http_build_query($_GET); ?>" class="btn btn-success">
                        <i class="fas fa-file-excel me-2"></i>Export Excel
                    </a>
                </div>
            </div>
            
            <!-- Filter Form -->
            <div class="card mb-4 no-print">
                <div class="card-header">
                    <h5 class="mb-0">Filter Laporan</h5>
                </div>
                <div class="card-body">
                    <form method="GET" action="<?php echo BASE_URL; ?>admin/reports">
                        <div class="row">
                            <div class="col-md-3 mb-3">
                                <label for="status" class="form-label">Status</label>
                                <select name="status" class="form-select">
                                    <option value="">Semua Status</option>
                                    <option value="pending" <?php echo (isset($data['filters']['status']) && $data['filters']['status'] == 'pending') ? 'selected' : ''; ?>>Menunggu</option>
                                    <option value="process" <?php echo (isset($data['filters']['status']) && $data['filters']['status'] == 'process') ? 'selected' : ''; ?>>Diproses</option>
                                    <option value="completed" <?php echo (isset($data['filters']['status']) && $data['filters']['status'] == 'completed') ? 'selected' : ''; ?>>Selesai</option>
                                    <option value="rejected" <?php echo (isset($data['filters']['status']) && $data['filters']['status'] == 'rejected') ? 'selected' : ''; ?>>Ditolak</option>
                                </select>
                            </div>
                            
                            <div class="col-md-3 mb-3">
                                <label for="category" class="form-label">Kategori</label>
                                <select name="category" class="form-select">
                                    <option value="">Semua Kategori</option>
                                    <?php foreach ($data['categories'] as $category) : ?>
                                        <option value="<?php echo $category->id; ?>" <?php echo (isset($data['filters']['category']) && $data['filters']['category'] == $category->id) ? 'selected' : ''; ?>>
                                            <?php echo $category->name; ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            
                            <div class="col-md-3 mb-3">
                                <label for="date_from" class="form-label">Dari Tanggal</label>
                                <input type="date" name="date_from" class="form-control" value="<?php echo isset($data['filters']['date_from']) ? $data['filters']['date_from'] : ''; ?>">
                            </div>
                            
                            <div class="col-md-3 mb-3">
                                <label for="date_to" class="form-label">Sampai Tanggal</label>
                                <input type="date" name="date_to" class="form-control" value="<?php echo isset($data['filters']['date_to']) ? $data['filters']['date_to'] : ''; ?>">
                            </div>
                        </div>
                        
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-search me-2"></i>Filter
                            </button>
                            <a href="<?php echo BASE_URL; ?>admin/reports" class="btn btn-outline-secondary">
                                <i class="fas fa-refresh me-2"></i>Reset
                            </a>
                        </div>
                    </form>
                </div>
            </div>
            
            <!-- Report Header -->
            <div class="text-center mb-4">
                <h3><?php echo APP_NAME; ?></h3>
                <h4>Laporan Pengaduan Warga</h4>
                <p class="text-muted">
                    Periode: 
                    <?php if (isset($data['filters']['date_from']) && isset($data['filters']['date_to'])) : ?>
                        <?php echo date('d/m/Y', strtotime($data['filters']['date_from'])); ?> - <?php echo date('d/m/Y', strtotime($data['filters']['date_to'])); ?>
                    <?php else : ?>
                        Semua Data
                    <?php endif; ?>
                </p>
                <p class="text-muted">Dicetak pada: <?php echo date('d/m/Y H:i:s'); ?></p>
            </div>
            
            <!-- Report Table -->
            <div class="card">
                <div class="card-body">
                    <?php if (empty($data['complaints'])) : ?>
                        <div class="text-center py-4">
                            <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                            <p class="text-muted">Tidak ada data pengaduan sesuai filter</p>
                        </div>
                    <?php else : ?>
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped">
                                <thead class="table-dark">
                                    <tr>
                                        <th>No</th>
                                        <th>ID</th>
                                        <th>Tanggal</th>
                                        <th>Pelapor</th>
                                        <th>Email</th>
                                        <th>Kategori</th>
                                        <th>Judul</th>
                                        <th>Lokasi</th>
                                        <th>Status</th>
                                        <th>Deskripsi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $no = 1; ?>
                                    <?php foreach ($data['complaints'] as $complaint) : ?>
                                        <tr>
                                            <td><?php echo $no++; ?></td>
                                            <td>#<?php echo str_pad($complaint->id, 6, '0', STR_PAD_LEFT); ?></td>
                                            <td><?php echo date('d/m/Y', strtotime($complaint->created_at)); ?></td>
                                            <td><?php echo $complaint->user_name; ?></td>
                                            <td><?php echo $complaint->user_email; ?></td>
                                            <td><?php echo $complaint->category_name; ?></td>
                                            <td><?php echo $complaint->title; ?></td>
                                            <td><?php echo $complaint->location; ?></td>
                                            <td>
                                                <?php
                                                switch ($complaint->status) {
                                                    case 'pending':
                                                        echo 'Menunggu';
                                                        break;
                                                    case 'process':
                                                        echo 'Diproses';
                                                        break;
                                                    case 'completed':
                                                        echo 'Selesai';
                                                        break;
                                                    case 'rejected':
                                                        echo 'Ditolak';
                                                        break;
                                                }
                                                ?>
                                            </td>
                                            <td><?php echo substr($complaint->description, 0, 100) . '...'; ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                        
                        <!-- Summary -->
                        <div class="mt-4">
                            <h5>Ringkasan:</h5>
                            <p>Total Pengaduan: <strong><?php echo count($data['complaints']); ?></strong></p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once '../app/views/inc/footer.php'; ?>
