<?php
class UrlHelper {
    
    // Get asset URL (CSS, JS, images)
    public static function asset($path) {
        // Remove leading slash if exists
        $path = ltrim($path, '/');
        
        // If BASE_URL already includes /public/, use it directly
        if (strpos(BASE_URL, '/public/') !== false) {
            return BASE_URL . $path;
        } else {
            return BASE_URL . 'public/' . $path;
        }
    }
    
    // Get upload URL
    public static function upload($filename) {
        return self::asset('uploads/' . $filename);
    }
    
    // Get route URL
    public static function route($route) {
        // Remove leading slash if exists
        $route = ltrim($route, '/');
        
        return BASE_URL . $route;
    }
    
    // Get current URL
    public static function current() {
        return $_SERVER['REQUEST_URI'] ?? '';
    }
    
    // Check if current route matches
    public static function isActive($route) {
        $current = self::current();
        return strpos($current, $route) !== false;
    }
}
?>
