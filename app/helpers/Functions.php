<?php
/**
 * Global helper functions
 */

// Flash message function for views
function flash($name = '', $message = '', $class = 'alert-success') {
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
            $class = !empty($_SESSION[$name . '_class']) ? $_SESSION[$name . '_class'] : 'alert-success';
            echo '<div class="alert ' . $class . ' alert-dismissible fade show" role="alert">';
            echo $_SESSION[$name];
            echo '<button type="button" class="btn-close" data-bs-dismiss="alert"></button>';
            echo '</div>';
            unset($_SESSION[$name]);
            unset($_SESSION[$name . '_class']);
        }
    }
}

// Redirect function
function redirect($url) {
    header('location: ' . BASE_URL . $url);
    exit;
}

// Check if user is logged in
function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

// Check if user is admin
function isAdmin() {
    return isset($_SESSION['user_role']) && $_SESSION['user_role'] == 'admin';
}

// Get current user info
function getCurrentUser() {
    if (isLoggedIn()) {
        return [
            'id' => $_SESSION['user_id'],
            'name' => $_SESSION['user_name'],
            'email' => $_SESSION['user_email'],
            'role' => $_SESSION['user_role']
        ];
    }
    return null;
}

// Format date for display
function formatDate($date, $format = 'd/m/Y H:i') {
    return date($format, strtotime($date));
}

// Format file size
function formatFileSize($bytes, $precision = 2) {
    $units = array('B', 'KB', 'MB', 'GB', 'TB');
    
    for ($i = 0; $bytes > 1024; $i++) {
        $bytes /= 1024;
    }
    
    return round($bytes, $precision) . ' ' . $units[$i];
}

// Truncate text
function truncateText($text, $length = 100, $suffix = '...') {
    if (strlen($text) <= $length) {
        return $text;
    }
    
    return substr($text, 0, $length) . $suffix;
}

// Generate random string
function generateRandomString($length = 10) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    
    return $randomString;
}

// Clean filename for upload
function cleanFilename($filename) {
    // Remove special characters and spaces
    $filename = preg_replace('/[^A-Za-z0-9\-_\.]/', '_', $filename);
    
    // Remove multiple underscores
    $filename = preg_replace('/_+/', '_', $filename);
    
    // Remove leading/trailing underscores
    $filename = trim($filename, '_');
    
    return $filename;
}

// Get status badge HTML
function getStatusBadge($status) {
    $badges = [
        'pending' => '<span class="badge bg-warning">Menunggu</span>',
        'process' => '<span class="badge bg-info">Diproses</span>',
        'completed' => '<span class="badge bg-success">Selesai</span>',
        'rejected' => '<span class="badge bg-danger">Ditolak</span>'
    ];
    
    return isset($badges[$status]) ? $badges[$status] : '<span class="badge bg-secondary">Unknown</span>';
}

// Get status text
function getStatusText($status) {
    $texts = [
        'pending' => 'Menunggu',
        'process' => 'Diproses',
        'completed' => 'Selesai',
        'rejected' => 'Ditolak'
    ];
    
    return isset($texts[$status]) ? $texts[$status] : 'Unknown';
}

// Escape HTML
function e($string) {
    return htmlspecialchars($string, ENT_QUOTES, 'UTF-8');
}

// Check if string contains
function contains($haystack, $needle) {
    return strpos($haystack, $needle) !== false;
}

// Get client IP
function getClientIP() {
    $ipKeys = ['HTTP_CLIENT_IP', 'HTTP_X_FORWARDED_FOR', 'REMOTE_ADDR'];
    
    foreach ($ipKeys as $key) {
        if (array_key_exists($key, $_SERVER) === true) {
            foreach (explode(',', $_SERVER[$key]) as $ip) {
                $ip = trim($ip);
                if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE) !== false) {
                    return $ip;
                }
            }
        }
    }
    
    return $_SERVER['REMOTE_ADDR'] ?? 'Unknown';
}

// Generate CSRF token
function csrf_token() {
    if (!isset($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

// Verify CSRF token
function csrf_verify($token) {
    return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
}

// Sanitize input data (PHP 8.1+ compatible)
function sanitizeInput($data) {
    if (is_array($data)) {
        return array_map('sanitizeInput', $data);
    }

    if (is_string($data)) {
        return htmlspecialchars(strip_tags(trim($data)), ENT_QUOTES, 'UTF-8');
    }

    return $data;
}

// Sanitize POST data
function sanitizePost() {
    return sanitizeInput($_POST);
}

// Sanitize GET data
function sanitizeGet() {
    return sanitizeInput($_GET);
}

?>
