<?php
class AdminAuth extends Controller {
    protected $userModel;
    
    // Get admin PIN from config
    private function getAdminPin() {
        return defined('ADMIN_PIN') ? ADMIN_PIN : '2024';
    }
    
    public function __construct() {
        $this->userModel = $this->model('User');
    }
    
    public function index() {
        // Redirect to pin if not verified
        if (!$this->isPinVerified()) {
            $this->redirect('adminauth/pin');
        }
        
        // Show admin login form
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Process admin login
            $_POST = sanitizePost();
            
            $data = [
                'email' => trim($_POST['email']),
                'password' => trim($_POST['password']),
                'email_err' => '',
                'password_err' => ''
            ];
            
            // Validate Email
            if (empty($data['email'])) {
                $data['email_err'] = 'Silakan masukkan email admin';
            }
            
            // Validate Password
            if (empty($data['password'])) {
                $data['password_err'] = 'Silakan masukkan password';
            }
            
            // Make sure errors are empty
            if (empty($data['email_err']) && empty($data['password_err'])) {
                // Check login
                $loggedInUser = $this->userModel->login($data['email'], $data['password']);
                
                if ($loggedInUser) {
                    // Check if user is admin
                    if ($loggedInUser->role === 'admin') {
                        // Create session
                        $this->createUserSession($loggedInUser);
                        
                        // Clear PIN session
                        unset($_SESSION['admin_pin_verified']);
                        
                        // Log admin login
                        Logger::activity('Admin Login via PIN', $loggedInUser->id, [
                            'email' => $loggedInUser->email,
                            'login_method' => 'PIN + Password'
                        ]);
                        
                        $this->redirect('admin');
                    } else {
                        $data['password_err'] = 'Akses ditolak. Bukan akun admin.';
                    }
                } else {
                    $data['password_err'] = 'Email atau password salah';
                }
            }
            
            // Load view with errors
            $this->view('adminauth/login', $data);
        } else {
            // Init data
            $data = [
                'title' => 'Admin Login - ' . APP_NAME,
                'email' => '',
                'password' => '',
                'email_err' => '',
                'password_err' => ''
            ];
            
            $this->view('adminauth/login', $data);
        }
    }
    
    public function pin() {
        // Check if already verified
        if ($this->isPinVerified()) {
            $this->redirect('adminauth');
        }
        
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $_POST = sanitizePost();
            
            $data = [
                'pin' => trim($_POST['pin']),
                'pin_err' => ''
            ];
            
            // Validate PIN
            if (empty($data['pin'])) {
                $data['pin_err'] = 'Silakan masukkan PIN admin';
            } elseif ($data['pin'] !== $this->getAdminPin()) {
                $data['pin_err'] = 'PIN admin salah';
                
                // Log failed PIN attempt
                Logger::security('Failed Admin PIN Attempt', [
                    'attempted_pin' => $data['pin']
                ]);
            }
            
            // If PIN is correct
            if (empty($data['pin_err'])) {
                // Set PIN verification session
                $_SESSION['admin_pin_verified'] = true;
                $_SESSION['admin_pin_time'] = time();
                
                // Log successful PIN verification
                Logger::security('Admin PIN Verified');
                
                $this->redirect('adminauth');
            }
            
            // Load view with errors
            $this->view('adminauth/pin', $data);
        } else {
            // Init data
            $data = [
                'title' => 'Admin PIN - ' . APP_NAME,
                'pin' => '',
                'pin_err' => ''
            ];
            
            $this->view('adminauth/pin', $data);
        }
    }
    
    public function logout() {
        // Log admin logout
        if (isset($_SESSION['user_id'])) {
            Logger::activity('Admin Logout', $_SESSION['user_id']);
        }

        // Clear all admin sessions
        unset($_SESSION['user_id']);
        unset($_SESSION['user_email']);
        unset($_SESSION['user_name']);
        unset($_SESSION['user_role']);
        unset($_SESSION['admin_pin_verified']);
        unset($_SESSION['admin_pin_time']);

        // Destroy session
        session_destroy();

        // Redirect with success message
        flash('logout_success', 'Anda telah berhasil logout dari admin panel');
        $this->redirect('home');
    }
    
    // Check if PIN is verified and still valid (1 hour)
    private function isPinVerified() {
        if (!isset($_SESSION['admin_pin_verified']) || !$_SESSION['admin_pin_verified']) {
            return false;
        }
        
        // Check if PIN verification is still valid (1 hour)
        if (isset($_SESSION['admin_pin_time'])) {
            $pinAge = time() - $_SESSION['admin_pin_time'];
            if ($pinAge > 3600) { // 1 hour
                unset($_SESSION['admin_pin_verified']);
                unset($_SESSION['admin_pin_time']);
                return false;
            }
        }
        
        return true;
    }
    
    // Create user session
    public function createUserSession($user) {
        $_SESSION['user_id'] = $user->id;
        $_SESSION['user_email'] = $user->email;
        $_SESSION['user_name'] = $user->name;
        $_SESSION['user_role'] = $user->role;
    }
}
?>
