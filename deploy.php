<?php
/**
 * Deployment Script for Pengaduan Warga System
 * Run this script to prepare the system for production deployment
 */

echo "=== PENGADUAN WARGA DEPLOYMENT SCRIPT ===\n\n";

// Check if running in CLI
if (php_sapi_name() !== 'cli') {
    die("This script must be run from command line\n");
}

// Check PHP version
if (version_compare(PHP_VERSION, '7.4.0') < 0) {
    die("PHP 7.4.0 or higher is required. Current version: " . PHP_VERSION . "\n");
}

echo "✓ PHP version check passed: " . PHP_VERSION . "\n";

// Check required extensions
$requiredExtensions = ['pdo', 'pdo_mysql', 'gd', 'fileinfo', 'session'];
$missingExtensions = [];

foreach ($requiredExtensions as $ext) {
    if (!extension_loaded($ext)) {
        $missingExtensions[] = $ext;
    }
}

if (!empty($missingExtensions)) {
    die("✗ Missing required PHP extensions: " . implode(', ', $missingExtensions) . "\n");
}

echo "✓ Required PHP extensions check passed\n";

// Create necessary directories
$directories = [
    'public/uploads',
    'logs',
    'cache',
    'tmp'
];

foreach ($directories as $dir) {
    if (!is_dir($dir)) {
        if (mkdir($dir, 0755, true)) {
            echo "✓ Created directory: $dir\n";
        } else {
            echo "✗ Failed to create directory: $dir\n";
        }
    } else {
        echo "✓ Directory exists: $dir\n";
    }
}

// Set proper permissions
$permissions = [
    'public/uploads' => 0755,
    'logs' => 0755,
    'cache' => 0755,
    'tmp' => 0755
];

foreach ($permissions as $path => $perm) {
    if (is_dir($path)) {
        if (chmod($path, $perm)) {
            echo "✓ Set permissions for $path: " . decoct($perm) . "\n";
        } else {
            echo "✗ Failed to set permissions for $path\n";
        }
    }
}

// Check configuration
if (!file_exists('app/config/config.php')) {
    echo "⚠ Configuration file not found. Creating from template...\n";
    
    if (file_exists('app/config/config.production.php')) {
        copy('app/config/config.production.php', 'app/config/config.php');
        echo "✓ Configuration file created from production template\n";
        echo "⚠ Please edit app/config/config.php with your production settings\n";
    } else {
        echo "✗ Production configuration template not found\n";
    }
} else {
    echo "✓ Configuration file exists\n";
}

// Security checks
echo "\n=== SECURITY CHECKS ===\n";

// Check if debug mode is disabled
require_once 'app/config/config.php';

if (defined('DEBUG') && DEBUG === false) {
    echo "✓ Debug mode is disabled\n";
} else {
    echo "⚠ Debug mode is enabled - should be disabled in production\n";
}

// Check .htaccess files
$htaccessFiles = [
    'public/.htaccess',
    'public/uploads/.htaccess',
    'logs/.htaccess'
];

foreach ($htaccessFiles as $file) {
    if (file_exists($file)) {
        echo "✓ Security file exists: $file\n";
    } else {
        echo "⚠ Security file missing: $file\n";
    }
}

// Check file permissions
$secureFiles = [
    'app/config/config.php' => 0644,
    'app/config/database.php' => 0644
];

foreach ($secureFiles as $file => $expectedPerm) {
    if (file_exists($file)) {
        $currentPerm = fileperms($file) & 0777;
        if ($currentPerm <= $expectedPerm) {
            echo "✓ File permissions OK: $file (" . decoct($currentPerm) . ")\n";
        } else {
            echo "⚠ File permissions too open: $file (" . decoct($currentPerm) . ")\n";
        }
    }
}

// Database connection test
echo "\n=== DATABASE CONNECTION TEST ===\n";

try {
    require_once 'app/config/database.php';
    $db = new Database();
    echo "✓ Database connection successful\n";
} catch (Exception $e) {
    echo "✗ Database connection failed: " . $e->getMessage() . "\n";
}

// Clean up development files
echo "\n=== CLEANUP ===\n";

$devFiles = [
    'setup.php',
    'test.php',
    'deploy.php'
];

$response = readline("Remove development files? (y/N): ");
if (strtolower($response) === 'y') {
    foreach ($devFiles as $file) {
        if (file_exists($file)) {
            if (unlink($file)) {
                echo "✓ Removed: $file\n";
            } else {
                echo "✗ Failed to remove: $file\n";
            }
        }
    }
} else {
    echo "⚠ Development files kept\n";
}

// Performance optimizations
echo "\n=== PERFORMANCE OPTIMIZATIONS ===\n";

// Check if OPcache is enabled
if (extension_loaded('opcache') && ini_get('opcache.enable')) {
    echo "✓ OPcache is enabled\n";
} else {
    echo "⚠ OPcache is not enabled - consider enabling for better performance\n";
}

// Generate deployment summary
echo "\n=== DEPLOYMENT SUMMARY ===\n";
echo "Application: " . APP_NAME . "\n";
echo "Base URL: " . BASE_URL . "\n";
echo "Debug Mode: " . (DEBUG ? 'Enabled' : 'Disabled') . "\n";
echo "PHP Version: " . PHP_VERSION . "\n";
echo "Deployment Date: " . date('Y-m-d H:i:s') . "\n";

echo "\n=== POST-DEPLOYMENT CHECKLIST ===\n";
echo "□ Update app/config/config.php with production settings\n";
echo "□ Set up SSL certificate and enable HTTPS\n";
echo "□ Configure web server (Apache/Nginx)\n";
echo "□ Set up database backups\n";
echo "□ Configure log rotation\n";
echo "□ Test all functionality\n";
echo "□ Set up monitoring\n";
echo "□ Change default admin password\n";
echo "□ Review security settings\n";
echo "□ Set up firewall rules\n";

echo "\n=== DEPLOYMENT COMPLETE ===\n";
echo "Your Pengaduan Warga system is ready for production!\n";
echo "Please complete the post-deployment checklist above.\n";
?>
