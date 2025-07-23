<?php
class Controller {
    // Load model
    public function model($model) {
        require_once '../app/models/' . $model . '.php';
        return new $model();
    }
    
    // Load view
    public function view($view, $data = []) {
        require_once '../app/views/' . $view . '.php';
    }
    
    // Redirect
    public function redirect($url) {
        header('location: ' . BASE_URL . $url);
        exit;
    }
    
    // Flash message
    public function flash($name = '', $message = '', $class = 'alert-success') {
        if (!empty($name)) {
            if (!empty($message) && empty($_SESSION[$name])) {
                if (!empty($_SESSION[$name])) {
                    unset($_SESSION[$name]);
                }
                if (!empty($_SESSION[$name . '_class'])) {
                    unset($_SESSION[$name . '_class']);
                }
                $_SESSION[$name] = $message;
                $_SESSION[$name . '_class'] = $class;
            } elseif (empty($message) && !empty($_SESSION[$name])) {
                $class = !empty($_SESSION[$name . '_class']) ? $_SESSION[$name . '_class'] : '';
                echo '<div class="alert ' . $class . ' alert-dismissible fade show" role="alert">';
                echo $_SESSION[$name];
                echo '<button type="button" class="btn-close" data-bs-dismiss="alert"></button>';
                echo '</div>';
                unset($_SESSION[$name]);
                unset($_SESSION[$name . '_class']);
            }
        }
    }
    
    // Check if user is logged in
    public function isLoggedIn() {
        if (isset($_SESSION['user_id'])) {
            return true;
        } else {
            return false;
        }
    }
    
    // Check if user is admin
    public function isAdmin() {
        if (isset($_SESSION['user_role']) && $_SESSION['user_role'] == 'admin') {
            return true;
        } else {
            return false;
        }
    }
    
    // Get current user
    public function getCurrentUser() {
        if ($this->isLoggedIn()) {
            return [
                'id' => $_SESSION['user_id'],
                'name' => $_SESSION['user_name'],
                'email' => $_SESSION['user_email'],
                'role' => $_SESSION['user_role']
            ];
        }
        return null;
    }
}
?>
