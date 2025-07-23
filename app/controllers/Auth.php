<?php
class Auth extends Controller {
    protected $userModel;

    public function __construct() {
        $this->userModel = $this->model('User');
    }
    
    public function login() {
        // Check if user is already logged in
        if ($this->isLoggedIn()) {
            $this->redirect('dashboard');
        }
        
        // Check for POST
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Process form

            // Sanitize POST data
            $_POST = sanitizePost();
            
            // Init data
            $data = [
                'email' => trim($_POST['email']),
                'password' => trim($_POST['password']),
                'email_err' => '',
                'password_err' => ''
            ];
            
            // Validate Email
            if (empty($data['email'])) {
                $data['email_err'] = 'Silakan masukkan email';
            }
            
            // Validate Password
            if (empty($data['password'])) {
                $data['password_err'] = 'Silakan masukkan password';
            }
            
            // Check for user/email
            if ($this->userModel->findUserByEmail($data['email'])) {
                // User found
            } else {
                // User not found
                $data['email_err'] = 'Email tidak ditemukan';
            }
            
            // Make sure errors are empty
            if (empty($data['email_err']) && empty($data['password_err'])) {
                // Validated
                // Check and set logged in user
                $loggedInUser = $this->userModel->login($data['email'], $data['password']);
                
                if ($loggedInUser) {
                    // Create Session
                    $this->createUserSession($loggedInUser);
                } else {
                    $data['password_err'] = 'Password salah';
                    
                    $this->view('auth/login', $data);
                }
            } else {
                // Load view with errors
                $this->view('auth/login', $data);
            }
            
        } else {
            // Init data
            $data = [
                'email' => '',
                'password' => '',
                'email_err' => '',
                'password_err' => ''
            ];
            
            // Load view
            $this->view('auth/login', $data);
        }
    }
    
    public function register() {
        // Check if user is already logged in
        if ($this->isLoggedIn()) {
            $this->redirect('dashboard');
        }
        
        // Check for POST
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Process form

            // Sanitize POST data
            $_POST = sanitizePost();
            
            // Init data
            $data = [
                'name' => trim($_POST['name']),
                'email' => trim($_POST['email']),
                'password' => trim($_POST['password']),
                'confirm_password' => trim($_POST['confirm_password']),
                'phone' => trim($_POST['phone']),
                'address' => trim($_POST['address']),
                'name_err' => '',
                'email_err' => '',
                'password_err' => '',
                'confirm_password_err' => '',
                'phone_err' => '',
                'address_err' => ''
            ];
            
            // Validate Email
            if (empty($data['email'])) {
                $data['email_err'] = 'Silakan masukkan email';
            } elseif (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
                $data['email_err'] = 'Format email tidak valid';
            } elseif ($this->userModel->emailExists($data['email'])) {
                $data['email_err'] = 'Email sudah terdaftar';
            }
            
            // Validate Name
            if (empty($data['name'])) {
                $data['name_err'] = 'Silakan masukkan nama';
            }
            
            // Validate Password
            if (empty($data['password'])) {
                $data['password_err'] = 'Silakan masukkan password';
            } elseif (strlen($data['password']) < 6) {
                $data['password_err'] = 'Password minimal 6 karakter';
            }
            
            // Validate Confirm Password
            if (empty($data['confirm_password'])) {
                $data['confirm_password_err'] = 'Silakan konfirmasi password';
            } else {
                if ($data['password'] != $data['confirm_password']) {
                    $data['confirm_password_err'] = 'Password tidak sama';
                }
            }
            
            // Make sure errors are empty
            if (empty($data['email_err']) && empty($data['name_err']) && empty($data['password_err']) && empty($data['confirm_password_err'])) {
                // Validated
                
                // Hash Password
                $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
                
                // Register User
                if ($this->userModel->register($data)) {
                    $this->flash('register_success', 'Registrasi berhasil! Silakan login.');
                    $this->redirect('auth/login');
                } else {
                    die('Something went wrong');
                }
                
            } else {
                // Load view with errors
                $this->view('auth/register', $data);
            }
            
        } else {
            // Init data
            $data = [
                'name' => '',
                'email' => '',
                'password' => '',
                'confirm_password' => '',
                'phone' => '',
                'address' => '',
                'name_err' => '',
                'email_err' => '',
                'password_err' => '',
                'confirm_password_err' => '',
                'phone_err' => '',
                'address_err' => ''
            ];
            
            // Load view
            $this->view('auth/register', $data);
        }
    }
    
    public function createUserSession($user) {
        $_SESSION['user_id'] = $user->id;
        $_SESSION['user_email'] = $user->email;
        $_SESSION['user_name'] = $user->name;
        $_SESSION['user_role'] = $user->role;
        
        if ($user->role == 'admin') {
            $this->redirect('admin');
        } else {
            $this->redirect('dashboard');
        }
    }
    
    public function logout() {
        unset($_SESSION['user_id']);
        unset($_SESSION['user_email']);
        unset($_SESSION['user_name']);
        unset($_SESSION['user_role']);
        session_destroy();
        $this->redirect('home');
    }
}
?>
