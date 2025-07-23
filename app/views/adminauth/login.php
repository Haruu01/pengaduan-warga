<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $data['title']; ?></title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .admin-login-container {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
            padding: 40px;
            max-width: 450px;
            width: 100%;
        }
        
        .admin-icon {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            width: 80px;
            height: 80px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 30px;
            font-size: 2rem;
        }
        
        .form-control {
            border-radius: 15px;
            padding: 15px 20px;
            border: 2px solid #e9ecef;
            transition: all 0.3s ease;
        }
        
        .form-control:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
        }
        
        .btn-admin {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            border-radius: 15px;
            padding: 15px;
            font-size: 1.1rem;
            font-weight: 600;
            color: white;
            transition: all 0.3s ease;
        }
        
        .btn-admin:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(102, 126, 234, 0.3);
            color: white;
        }
        
        .pin-verified {
            background: rgba(40, 167, 69, 0.1);
            border: 1px solid rgba(40, 167, 69, 0.3);
            border-radius: 10px;
            padding: 15px;
            margin-bottom: 20px;
            color: #155724;
            text-align: center;
        }
        
        .back-link {
            position: absolute;
            top: 20px;
            left: 20px;
            color: white;
            text-decoration: none;
            font-size: 1.1rem;
            transition: all 0.3s ease;
        }
        
        .back-link:hover {
            color: rgba(255, 255, 255, 0.8);
            text-decoration: none;
        }
        
        .input-group-text {
            background: transparent;
            border: 2px solid #e9ecef;
            border-right: none;
            border-radius: 15px 0 0 15px;
        }
        
        .input-group .form-control {
            border-left: none;
            border-radius: 0 15px 15px 0;
        }
        
        .input-group:focus-within .input-group-text {
            border-color: #667eea;
        }
    </style>
</head>
<body>
    <a href="<?php echo BASE_URL; ?>" class="back-link">
        <i class="fas fa-arrow-left me-2"></i>Kembali ke Beranda
    </a>
    
    <div class="admin-login-container">
        <div class="text-center">
            <div class="admin-icon">
                <i class="fas fa-user-shield"></i>
            </div>
            
            <h2 class="mb-3">Admin Login</h2>
            <p class="text-muted mb-4">Masuk ke panel administrasi</p>
        </div>
        
        <div class="pin-verified">
            <i class="fas fa-check-circle me-2"></i>
            <strong>PIN Terverifikasi</strong> - Silakan login dengan akun admin
        </div>
        
        <form method="post">
            <div class="mb-3">
                <div class="input-group">
                    <span class="input-group-text">
                        <i class="fas fa-envelope"></i>
                    </span>
                    <input type="email" 
                           name="email" 
                           class="form-control <?php echo (!empty($data['email_err'])) ? 'is-invalid' : ''; ?>" 
                           value="<?php echo $data['email']; ?>" 
                           placeholder="Email Admin"
                           required>
                </div>
                <?php if (!empty($data['email_err'])): ?>
                    <div class="text-danger mt-2">
                        <small><i class="fas fa-exclamation-circle me-1"></i><?php echo $data['email_err']; ?></small>
                    </div>
                <?php endif; ?>
            </div>
            
            <div class="mb-4">
                <div class="input-group">
                    <span class="input-group-text">
                        <i class="fas fa-lock"></i>
                    </span>
                    <input type="password" 
                           name="password" 
                           class="form-control <?php echo (!empty($data['password_err'])) ? 'is-invalid' : ''; ?>" 
                           placeholder="Password Admin"
                           required>
                    <button type="button" class="btn btn-outline-secondary" onclick="togglePassword()">
                        <i class="fas fa-eye" id="toggleIcon"></i>
                    </button>
                </div>
                <?php if (!empty($data['password_err'])): ?>
                    <div class="text-danger mt-2">
                        <small><i class="fas fa-exclamation-circle me-1"></i><?php echo $data['password_err']; ?></small>
                    </div>
                <?php endif; ?>
            </div>
            
            <button type="submit" class="btn btn-admin w-100">
                <i class="fas fa-sign-in-alt me-2"></i>Masuk ke Admin Panel
            </button>
        </form>
        
        <div class="text-center mt-4">
            <small class="text-muted">
                <i class="fas fa-shield-alt me-1"></i>
                Area terbatas untuk administrator
            </small>
        </div>
        
        <div class="text-center mt-3">
            <a href="<?php echo BASE_URL; ?>index.php?url=auth/login" class="text-decoration-none">
                <small><i class="fas fa-users me-1"></i>Login sebagai Warga</small>
            </a>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        function togglePassword() {
            const passwordInput = document.querySelector('input[name="password"]');
            const toggleIcon = document.getElementById('toggleIcon');
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                toggleIcon.classList.remove('fa-eye');
                toggleIcon.classList.add('fa-eye-slash');
            } else {
                passwordInput.type = 'password';
                toggleIcon.classList.remove('fa-eye-slash');
                toggleIcon.classList.add('fa-eye');
            }
        }
        
        // Auto-focus on email input
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelector('input[name="email"]').focus();
        });
    </script>
</body>
</html>
