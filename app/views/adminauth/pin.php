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
        
        .pin-container {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
            padding: 40px;
            text-align: center;
            max-width: 400px;
            width: 100%;
        }
        
        .pin-icon {
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
        
        .pin-input {
            font-size: 2rem;
            text-align: center;
            letter-spacing: 1rem;
            border: 2px solid #e9ecef;
            border-radius: 15px;
            padding: 20px;
            margin: 20px 0;
            transition: all 0.3s ease;
        }
        
        .pin-input:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
        }
        
        .btn-pin {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            border-radius: 15px;
            padding: 15px 40px;
            font-size: 1.1rem;
            font-weight: 600;
            color: white;
            transition: all 0.3s ease;
        }
        
        .btn-pin:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(102, 126, 234, 0.3);
            color: white;
        }
        
        .security-notice {
            background: rgba(255, 193, 7, 0.1);
            border: 1px solid rgba(255, 193, 7, 0.3);
            border-radius: 10px;
            padding: 15px;
            margin-top: 20px;
            font-size: 0.9rem;
            color: #856404;
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
        
        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            25% { transform: translateX(-5px); }
            75% { transform: translateX(5px); }
        }
        
        .shake {
            animation: shake 0.5s ease-in-out;
        }
    </style>
</head>
<body>
    <a href="<?php echo BASE_URL; ?>" class="back-link">
        <i class="fas fa-arrow-left me-2"></i>Kembali ke Beranda
    </a>
    
    <div class="pin-container">
        <div class="pin-icon">
            <i class="fas fa-shield-alt"></i>
        </div>
        
        <h2 class="mb-3">Admin Access</h2>
        <p class="text-muted mb-4">Masukkan PIN admin untuk melanjutkan</p>
        
        <form method="post" id="pinForm">
            <div class="mb-3">
                <input type="password" 
                       name="pin" 
                       id="pinInput"
                       class="form-control pin-input <?php echo (!empty($data['pin_err'])) ? 'is-invalid' : ''; ?>" 
                       maxlength="4" 
                       placeholder="••••"
                       autocomplete="off"
                       autofocus>
                <div class="invalid-feedback text-center">
                    <?php echo $data['pin_err']; ?>
                </div>
            </div>
            
            <button type="submit" class="btn btn-pin w-100">
                <i class="fas fa-unlock me-2"></i>Verifikasi PIN
            </button>
        </form>
        
        <div class="security-notice">
            <i class="fas fa-info-circle me-2"></i>
            <strong>Keamanan:</strong> PIN ini diperlukan untuk mengakses area admin. 
            Verifikasi berlaku selama 1 jam.
        </div>
        
        <div class="mt-4">
            <small class="text-muted">
                <i class="fas fa-clock me-1"></i>
                Session akan berakhir otomatis setelah 1 jam
            </small>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const pinInput = document.getElementById('pinInput');
            const pinForm = document.getElementById('pinForm');
            const pinContainer = document.querySelector('.pin-container');
            
            // Auto-submit when 4 digits entered
            pinInput.addEventListener('input', function() {
                // Only allow numbers
                this.value = this.value.replace(/[^0-9]/g, '');
                
                // Auto-submit when 4 digits
                if (this.value.length === 4) {
                    setTimeout(() => {
                        pinForm.submit();
                    }, 500);
                }
            });
            
            // Add shake animation on error
            <?php if (!empty($data['pin_err'])): ?>
            pinContainer.classList.add('shake');
            setTimeout(() => {
                pinContainer.classList.remove('shake');
            }, 500);
            <?php endif; ?>
            
            // Focus on input
            pinInput.focus();
        });
    </script>
</body>
</html>
