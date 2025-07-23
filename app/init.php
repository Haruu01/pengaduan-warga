<?php
// Load config first (might already be loaded)
if (!defined('BASE_URL')) {
    require_once 'config/config.php';
}

// Load core libraries
require_once 'core/App.php';
require_once 'core/Controller.php';
require_once 'config/database.php';

// Load helpers
require_once 'helpers/Functions.php';
require_once 'helpers/UrlHelper.php';
require_once 'helpers/Validation.php';
require_once 'helpers/Logger.php';

// Start session
session_name(SESSION_NAME);
session_start();

// Set error handler
set_error_handler(function($severity, $message, $file, $line) {
    if (!(error_reporting() & $severity)) {
        return false;
    }

    Logger::error("PHP Error: {$message}", [
        'file' => $file,
        'line' => $line,
        'severity' => $severity
    ]);

    return false;
});

// Set exception handler
set_exception_handler(function($exception) {
    Logger::error("Uncaught Exception: " . $exception->getMessage(), [
        'file' => $exception->getFile(),
        'line' => $exception->getLine(),
        'trace' => $exception->getTraceAsString()
    ]);

    // Show user-friendly error page in production
    if (!defined('DEBUG') || !DEBUG) {
        header('HTTP/1.1 500 Internal Server Error');
        echo '<h1>Terjadi kesalahan sistem</h1><p>Silakan coba lagi nanti.</p>';
        exit;
    }
});
?>
