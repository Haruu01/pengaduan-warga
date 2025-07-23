<?php
class Dashboard extends Controller {
    protected $complaintModel;
    protected $userModel;

    public function __construct() {
        if (!$this->isLoggedIn()) {
            $this->redirect('auth/login');
        }

        $this->complaintModel = $this->model('Complaint');
        $this->userModel = $this->model('User');
    }
    
    public function index() {
        // Get user's complaints
        $complaints = $this->complaintModel->getComplaintsByUser($_SESSION['user_id']);
        
        // Get user statistics
        $stats = [
            'total' => count($complaints),
            'pending' => count(array_filter($complaints, function($c) { return $c->status == 'pending'; })),
            'process' => count(array_filter($complaints, function($c) { return $c->status == 'process'; })),
            'completed' => count(array_filter($complaints, function($c) { return $c->status == 'completed'; })),
            'rejected' => count(array_filter($complaints, function($c) { return $c->status == 'rejected'; }))
        ];
        
        $data = [
            'title' => 'Dashboard - ' . APP_NAME,
            'complaints' => $complaints,
            'stats' => $stats
        ];
        
        $this->view('dashboard/index', $data);
    }
    
    public function profile() {
        $user = $this->userModel->getUserById($_SESSION['user_id']);

        // Get user statistics
        $complaints = $this->complaintModel->getComplaintsByUser($_SESSION['user_id']);
        $stats = [
            'total' => count($complaints),
            'pending' => count(array_filter($complaints, function($c) { return $c->status == 'pending'; })),
            'process' => count(array_filter($complaints, function($c) { return $c->status == 'process'; })),
            'completed' => count(array_filter($complaints, function($c) { return $c->status == 'completed'; })),
            'rejected' => count(array_filter($complaints, function($c) { return $c->status == 'rejected'; }))
        ];

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Process form
            $_POST = sanitizePost();

            $data = [
                'id' => $_SESSION['user_id'],
                'name' => trim($_POST['name']),
                'phone' => trim($_POST['phone']),
                'address' => trim($_POST['address']),
                'name_err' => '',
                'phone_err' => '',
                'address_err' => ''
            ];

            // Validate Name
            if (empty($data['name'])) {
                $data['name_err'] = 'Silakan masukkan nama';
            }

            // Make sure errors are empty
            if (empty($data['name_err']) && empty($data['phone_err']) && empty($data['address_err'])) {
                // Update profile
                if ($this->userModel->updateProfile($data)) {
                    // Update session
                    $_SESSION['user_name'] = $data['name'];
                    $this->flash('profile_message', 'Profil berhasil diperbarui');
                    $this->redirect('dashboard/profile');
                } else {
                    die('Something went wrong');
                }
            } else {
                // Load view with errors
                $data['user'] = $user;
                $data['stats'] = $stats;
                $this->view('dashboard/profile', $data);
            }
        } else {
            $data = [
                'title' => 'Profil - ' . APP_NAME,
                'user' => $user,
                'stats' => $stats,
                'name_err' => '',
                'phone_err' => '',
                'address_err' => ''
            ];

            $this->view('dashboard/profile', $data);
        }
    }

    public function changePassword() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $_POST = sanitizePost();

            $data = [
                'current_password' => trim($_POST['current_password']),
                'new_password' => trim($_POST['new_password']),
                'confirm_password' => trim($_POST['confirm_password']),
                'current_password_err' => '',
                'new_password_err' => '',
                'confirm_password_err' => ''
            ];

            // Get current user
            $user = $this->userModel->getUserById($_SESSION['user_id']);

            // Validate current password
            if (empty($data['current_password'])) {
                $data['current_password_err'] = 'Silakan masukkan password saat ini';
            } elseif (!password_verify($data['current_password'], $user->password)) {
                $data['current_password_err'] = 'Password saat ini salah';
            }

            // Validate new password
            if (empty($data['new_password'])) {
                $data['new_password_err'] = 'Silakan masukkan password baru';
            } elseif (strlen($data['new_password']) < 6) {
                $data['new_password_err'] = 'Password minimal 6 karakter';
            }

            // Validate confirm password
            if (empty($data['confirm_password'])) {
                $data['confirm_password_err'] = 'Silakan konfirmasi password baru';
            } elseif ($data['new_password'] != $data['confirm_password']) {
                $data['confirm_password_err'] = 'Password tidak sama';
            }

            // Make sure errors are empty
            if (empty($data['current_password_err']) && empty($data['new_password_err']) && empty($data['confirm_password_err'])) {
                // Hash new password
                $hashedPassword = password_hash($data['new_password'], PASSWORD_DEFAULT);

                // Update password
                if ($this->userModel->changePassword($_SESSION['user_id'], $hashedPassword)) {
                    $this->flash('profile_message', 'Password berhasil diubah');
                    $this->redirect('dashboard/profile');
                } else {
                    die('Something went wrong');
                }
            } else {
                $this->flash('profile_message', 'Gagal mengubah password. Periksa kembali input Anda.', 'alert-danger');
                $this->redirect('dashboard/profile');
            }
        } else {
            $this->redirect('dashboard/profile');
        }
    }
}
?>
