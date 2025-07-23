<?php
/**
 * Setup Script for Pengaduan Warga System
 * Run this script once to setup the database and initial configuration
 */

// Include configuration
require_once 'app/config/config.php';

// Database connection
try {
    $pdo = new PDO('mysql:host=' . DB_HOST, DB_USER, DB_PASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "✓ Database connection successful\n";
} catch (PDOException $e) {
    die("✗ Database connection failed: " . $e->getMessage() . "\n");
}

// Create database if not exists
try {
    $pdo->exec("CREATE DATABASE IF NOT EXISTS " . DB_NAME . " CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
    echo "✓ Database '" . DB_NAME . "' created/verified\n";
} catch (PDOException $e) {
    die("✗ Failed to create database: " . $e->getMessage() . "\n");
}

// Use the database
$pdo->exec("USE " . DB_NAME);

// Read and execute SQL file
$sqlFile = 'database/pengaduan_warga.sql';
if (!file_exists($sqlFile)) {
    die("✗ SQL file not found: $sqlFile\n");
}

$sql = file_get_contents($sqlFile);
$statements = explode(';', $sql);

foreach ($statements as $statement) {
    $statement = trim($statement);
    if (!empty($statement)) {
        try {
            $pdo->exec($statement);
        } catch (PDOException $e) {
            // Ignore table exists errors
            if (strpos($e->getMessage(), 'already exists') === false) {
                echo "Warning: " . $e->getMessage() . "\n";
            }
        }
    }
}

echo "✓ Database tables created/updated\n";

// Check if admin user exists
$stmt = $pdo->prepare("SELECT COUNT(*) FROM users WHERE role = 'admin'");
$stmt->execute();
$adminCount = $stmt->fetchColumn();

if ($adminCount == 0) {
    // Create default admin user
    $adminPassword = password_hash('admin123', PASSWORD_DEFAULT);
    $stmt = $pdo->prepare("INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, ?)");
    $stmt->execute(['Administrator', 'admin@pengaduan.com', $adminPassword, 'admin']);
    echo "✓ Default admin user created (email: admin@pengaduan.com, password: admin123)\n";
} else {
    echo "✓ Admin user already exists\n";
}

// Create necessary directories
$directories = [
    'public/uploads',
    'logs'
];

foreach ($directories as $dir) {
    if (!is_dir($dir)) {
        mkdir($dir, 0755, true);
        echo "✓ Directory created: $dir\n";
    } else {
        echo "✓ Directory exists: $dir\n";
    }
}

// Set permissions
if (is_dir('public/uploads')) {
    chmod('public/uploads', 0755);
    echo "✓ Upload directory permissions set\n";
}

if (is_dir('logs')) {
    chmod('logs', 0755);
    echo "✓ Logs directory permissions set\n";
}

// Test database connection with the application
require_once 'app/config/database.php';
try {
    $db = new Database();
    echo "✓ Application database connection test successful\n";
} catch (Exception $e) {
    echo "✗ Application database connection test failed: " . $e->getMessage() . "\n";
}

echo "\n";
echo "=== SETUP COMPLETE ===\n";
echo "Your Pengaduan Warga system is ready!\n\n";
echo "Access your application at: " . BASE_URL . "\n";
echo "Admin login:\n";
echo "  Email: admin@pengaduan.com\n";
echo "  Password: admin123\n\n";
echo "Please change the admin password after first login!\n";
echo "\n";
echo "Next steps:\n";
echo "1. Access the application in your browser\n";
echo "2. Login as admin and change the password\n";
echo "3. Add categories for complaints\n";
echo "4. Test the system by creating a user account\n";
echo "5. Set DEBUG to false in app/config/config.php for production\n";
?>
