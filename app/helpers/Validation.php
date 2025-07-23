<?php
class Validation {
    
    // Validate email format
    public static function email($email) {
        return filter_var($email, FILTER_VALIDATE_EMAIL);
    }
    
    // Validate phone number (Indonesian format)
    public static function phone($phone) {
        // Remove all non-numeric characters
        $phone = preg_replace('/[^0-9]/', '', $phone);
        
        // Check if it starts with 08, +62, or 62
        if (preg_match('/^(08|62|\\+62)/', $phone)) {
            return true;
        }
        
        return false;
    }
    
    // Validate password strength
    public static function password($password) {
        $errors = [];
        
        if (strlen($password) < 6) {
            $errors[] = 'Password minimal 6 karakter';
        }
        
        if (!preg_match('/[A-Za-z]/', $password)) {
            $errors[] = 'Password harus mengandung huruf';
        }
        
        return empty($errors) ? true : $errors;
    }
    
    // Validate file upload
    public static function fileUpload($file, $allowedTypes = ['jpg', 'jpeg', 'png', 'gif'], $maxSize = 5242880) {
        $errors = [];
        
        if ($file['error'] !== UPLOAD_ERR_OK) {
            switch ($file['error']) {
                case UPLOAD_ERR_INI_SIZE:
                case UPLOAD_ERR_FORM_SIZE:
                    $errors[] = 'File terlalu besar';
                    break;
                case UPLOAD_ERR_PARTIAL:
                    $errors[] = 'File tidak terupload sempurna';
                    break;
                case UPLOAD_ERR_NO_FILE:
                    $errors[] = 'Tidak ada file yang dipilih';
                    break;
                default:
                    $errors[] = 'Terjadi kesalahan saat upload';
                    break;
            }
            return $errors;
        }
        
        // Check file size
        if ($file['size'] > $maxSize) {
            $errors[] = 'File terlalu besar. Maksimal ' . self::formatBytes($maxSize);
        }
        
        // Check file type
        $fileExtension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        if (!in_array($fileExtension, $allowedTypes)) {
            $errors[] = 'Format file tidak didukung. Gunakan: ' . implode(', ', $allowedTypes);
        }
        
        // Check if it's actually an image
        if (in_array($fileExtension, ['jpg', 'jpeg', 'png', 'gif'])) {
            $imageInfo = getimagesize($file['tmp_name']);
            if ($imageInfo === false) {
                $errors[] = 'File bukan gambar yang valid';
            }
        }
        
        return empty($errors) ? true : $errors;
    }
    
    // Sanitize input (PHP 8.1+ compatible)
    public static function sanitize($input) {
        if (is_array($input)) {
            return array_map([self::class, 'sanitize'], $input);
        }

        if (is_string($input)) {
            return htmlspecialchars(strip_tags(trim($input)), ENT_QUOTES, 'UTF-8');
        }

        return $input;
    }
    
    // Validate required field
    public static function required($value, $fieldName = 'Field') {
        if (empty(trim($value))) {
            return $fieldName . ' wajib diisi';
        }
        return true;
    }
    
    // Validate minimum length
    public static function minLength($value, $min, $fieldName = 'Field') {
        if (strlen(trim($value)) < $min) {
            return $fieldName . ' minimal ' . $min . ' karakter';
        }
        return true;
    }
    
    // Validate maximum length
    public static function maxLength($value, $max, $fieldName = 'Field') {
        if (strlen(trim($value)) > $max) {
            return $fieldName . ' maksimal ' . $max . ' karakter';
        }
        return true;
    }
    
    // Format bytes to human readable
    private static function formatBytes($bytes, $precision = 2) {
        $units = array('B', 'KB', 'MB', 'GB', 'TB');
        
        for ($i = 0; $bytes > 1024; $i++) {
            $bytes /= 1024;
        }
        
        return round($bytes, $precision) . ' ' . $units[$i];
    }
    
    // Validate CSRF token
    public static function csrfToken($token) {
        if (!isset($_SESSION['csrf_token']) || $token !== $_SESSION['csrf_token']) {
            return false;
        }
        return true;
    }
    
    // Generate CSRF token
    public static function generateCsrfToken() {
        if (!isset($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
        return $_SESSION['csrf_token'];
    }
}
?>
