<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Halaman Tidak Ditemukan - <?php echo APP_NAME; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="bg-light">
    <div class="container">
        <div class="row justify-content-center align-items-center min-vh-100">
            <div class="col-md-6 text-center">
                <div class="card shadow">
                    <div class="card-body py-5">
                        <i class="fas fa-exclamation-triangle fa-5x text-warning mb-4"></i>
                        <h1 class="display-4 text-primary">404</h1>
                        <h2 class="mb-3">Halaman Tidak Ditemukan</h2>
                        <p class="lead text-muted mb-4">
                            Maaf, halaman yang Anda cari tidak dapat ditemukan. 
                            Mungkin halaman telah dipindahkan atau URL yang Anda masukkan salah.
                        </p>
                        <div class="d-grid gap-2 d-md-flex justify-content-md-center">
                            <a href="<?php echo BASE_URL; ?>" class="btn btn-primary me-md-2">
                                <i class="fas fa-home me-2"></i>Kembali ke Beranda
                            </a>
                            <button onclick="history.back()" class="btn btn-outline-secondary">
                                <i class="fas fa-arrow-left me-2"></i>Halaman Sebelumnya
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
