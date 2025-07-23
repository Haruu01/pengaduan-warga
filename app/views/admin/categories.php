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
                <h1 class="h2">Kelola Kategori</h1>
                <a href="<?php echo BASE_URL; ?>admin/addCategory" class="btn btn-primary">
                    <i class="fas fa-plus me-2"></i>Tambah Kategori
                </a>
            </div>
            
            <?php flash('category_message'); ?>
            
            <?php if (empty($data['categories'])) : ?>
                <div class="card">
                    <div class="card-body text-center py-5">
                        <i class="fas fa-folder-open fa-4x text-muted mb-4"></i>
                        <h4 class="text-muted">Belum Ada Kategori</h4>
                        <p class="text-muted mb-4">Belum ada kategori yang dibuat. Tambahkan kategori pertama untuk mengorganisir pengaduan.</p>
                        <a href="<?php echo BASE_URL; ?>admin/addCategory" class="btn btn-primary">
                            <i class="fas fa-plus me-2"></i>Tambah Kategori Pertama
                        </a>
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
                                        <th>Nama Kategori</th>
                                        <th>Deskripsi</th>
                                        <th>Tanggal Dibuat</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($data['categories'] as $category) : ?>
                                        <tr>
                                            <td><?php echo $category->id; ?></td>
                                            <td>
                                                <strong><?php echo $category->name; ?></strong>
                                            </td>
                                            <td><?php echo substr($category->description, 0, 100) . '...'; ?></td>
                                            <td><?php echo date('d/m/Y', strtotime($category->created_at)); ?></td>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    <a href="<?php echo BASE_URL; ?>admin/editCategory/<?php echo $category->id; ?>" 
                                                       class="btn btn-sm btn-outline-primary" 
                                                       data-bs-toggle="tooltip" 
                                                       title="Edit Kategori">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    <a href="<?php echo BASE_URL; ?>admin/deleteCategory/<?php echo $category->id; ?>" 
                                                       class="btn btn-sm btn-outline-danger" 
                                                       data-bs-toggle="tooltip" 
                                                       title="Hapus Kategori"
                                                       onclick="return confirmDelete('Apakah Anda yakin ingin menghapus kategori ini?')">
                                                        <i class="fas fa-trash"></i>
                                                    </a>
                                                </div>
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
