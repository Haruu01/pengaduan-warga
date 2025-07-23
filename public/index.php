<?php
try {
    // Load configuration first
    require_once '../app/config/config.php';

    // Error reporting for debugging (remove in production)
    if (defined('DEBUG') && DEBUG) {
        error_reporting(E_ALL);
        ini_set('display_errors', 1);
    }

    require_once '../app/init.php';
    $app = new App;
} catch (Exception $e) {
    // Log the error
    if (class_exists('Logger')) {
        Logger::error('Application Error: ' . $e->getMessage(), [
            'file' => $e->getFile(),
            'line' => $e->getLine(),
            'trace' => $e->getTraceAsString()
        ]);
    }

    // Show user-friendly error
    if (defined('DEBUG') && DEBUG) {
        echo '<h1>Application Error</h1>';
        echo '<p><strong>Error:</strong> ' . htmlspecialchars($e->getMessage()) . '</p>';
        echo '<p><strong>File:</strong> ' . htmlspecialchars($e->getFile()) . '</p>';
        echo '<p><strong>Line:</strong> ' . $e->getLine() . '</p>';
        echo '<pre>' . htmlspecialchars($e->getTraceAsString()) . '</pre>';
    } else {
        // Production error page
        http_response_code(500);
        if (file_exists('../app/views/errors/500.php')) {
            require_once '../app/views/errors/500.php';
        } else {
            echo '<h1>Terjadi kesalahan sistem</h1><p>Silakan coba lagi nanti.</p>';
        }
    }
}
?>
