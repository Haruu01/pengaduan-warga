<?php
// Debug script to check basic functionality
echo "<h1>Debug Information</h1>";

// Check PHP version
echo "<h2>PHP Version</h2>";
echo "PHP Version: " . PHP_VERSION . "<br>";

// Check if required extensions are loaded
echo "<h2>Required Extensions</h2>";
$extensions = ['pdo', 'pdo_mysql', 'gd', 'fileinfo', 'session'];
foreach ($extensions as $ext) {
    echo $ext . ": " . (extension_loaded($ext) ? "✓ Loaded" : "✗ Not loaded") . "<br>";
}

// Check file structure
echo "<h2>File Structure</h2>";
$files = [
    'app/init.php',
    'app/core/App.php',
    'app/core/Controller.php',
    'app/config/config.php',
    'app/config/database.php',
    'app/controllers/Home.php',
    'public/index.php'
];

foreach ($files as $file) {
    echo $file . ": " . (file_exists($file) ? "✓ Exists" : "✗ Missing") . "<br>";
}

// Check directories
echo "<h2>Directories</h2>";
$dirs = [
    'app',
    'app/controllers',
    'app/models',
    'app/views',
    'public',
    'public/uploads',
    'logs'
];

foreach ($dirs as $dir) {
    echo $dir . ": " . (is_dir($dir) ? "✓ Exists" : "✗ Missing") . "<br>";
}

// Test configuration loading
echo "<h2>Configuration Test</h2>";
try {
    require_once 'app/config/config.php';
    echo "BASE_URL: " . (defined('BASE_URL') ? BASE_URL : 'Not defined') . "<br>";
    echo "APP_NAME: " . (defined('APP_NAME') ? APP_NAME : 'Not defined') . "<br>";
    echo "DB_HOST: " . (defined('DB_HOST') ? DB_HOST : 'Not defined') . "<br>";
    echo "DB_NAME: " . (defined('DB_NAME') ? DB_NAME : 'Not defined') . "<br>";
} catch (Exception $e) {
    echo "Configuration error: " . $e->getMessage() . "<br>";
}

// Test database connection
echo "<h2>Database Connection Test</h2>";
try {
    require_once 'app/config/database.php';
    $db = new Database();
    echo "✓ Database connection successful<br>";
} catch (Exception $e) {
    echo "✗ Database connection failed: " . $e->getMessage() . "<br>";
}

// Test session
echo "<h2>Session Test</h2>";
session_start();
echo "Session status: " . (session_status() === PHP_SESSION_ACTIVE ? "✓ Active" : "✗ Not active") . "<br>";
echo "Session ID: " . session_id() . "<br>";

// Test URL parsing
echo "<h2>URL Parsing Test</h2>";
echo "REQUEST_URI: " . ($_SERVER['REQUEST_URI'] ?? 'Not set') . "<br>";
echo "GET url: " . ($_GET['url'] ?? 'Not set') . "<br>";

// Test .htaccess
echo "<h2>.htaccess Test</h2>";
echo "public/.htaccess: " . (file_exists('public/.htaccess') ? "✓ Exists" : "✗ Missing") . "<br>";

if (file_exists('public/.htaccess')) {
    echo "Content:<br><pre>" . htmlspecialchars(file_get_contents('public/.htaccess')) . "</pre>";
}

echo "<h2>Next Steps</h2>";
echo "1. Make sure you access the application through: <a href='" . (defined('BASE_URL') ? BASE_URL : 'http://localhost/Pengaduan_warga/public/') . "'>" . (defined('BASE_URL') ? BASE_URL : 'http://localhost/Pengaduan_warga/public/') . "</a><br>";
echo "2. If you see errors, run setup.php first<br>";
echo "3. Check Apache mod_rewrite is enabled<br>";
?>
