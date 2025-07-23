<?php
/**
 * Test Script for Pengaduan Warga System
 * Run this script to test basic functionality
 */

// Include the application
require_once 'app/init.php';

echo "=== PENGADUAN WARGA SYSTEM TESTS ===\n\n";

// Test 1: Database Connection
echo "1. Testing Database Connection...\n";
try {
    $db = new Database();
    echo "   ✓ Database connection successful\n";
} catch (Exception $e) {
    echo "   ✗ Database connection failed: " . $e->getMessage() . "\n";
    exit(1);
}

// Test 2: User Model
echo "\n2. Testing User Model...\n";
try {
    $userModel = new User();
    
    // Test finding user by email
    $adminExists = $userModel->findUserByEmail('admin@pengaduan.com');
    if ($adminExists) {
        echo "   ✓ Admin user found\n";
    } else {
        echo "   ✗ Admin user not found\n";
    }
    
    // Test getting all users
    $users = $userModel->getUsers();
    echo "   ✓ Found " . count($users) . " users in database\n";
    
} catch (Exception $e) {
    echo "   ✗ User model test failed: " . $e->getMessage() . "\n";
}

// Test 3: Category Model
echo "\n3. Testing Category Model...\n";
try {
    $categoryModel = new Category();
    
    // Test getting categories
    $categories = $categoryModel->getCategories();
    echo "   ✓ Found " . count($categories) . " categories in database\n";
    
    if (count($categories) > 0) {
        $firstCategory = $categories[0];
        $category = $categoryModel->getCategoryById($firstCategory->id);
        if ($category) {
            echo "   ✓ Category retrieval by ID successful\n";
        } else {
            echo "   ✗ Category retrieval by ID failed\n";
        }
    }
    
} catch (Exception $e) {
    echo "   ✗ Category model test failed: " . $e->getMessage() . "\n";
}

// Test 4: Complaint Model
echo "\n4. Testing Complaint Model...\n";
try {
    $complaintModel = new Complaint();
    
    // Test getting complaints
    $complaints = $complaintModel->getComplaints();
    echo "   ✓ Found " . count($complaints) . " complaints in database\n";
    
    // Test getting statistics
    $stats = $complaintModel->getStatistics();
    echo "   ✓ Statistics: Total=" . $stats['total'] . ", Pending=" . $stats['pending'] . 
         ", Process=" . $stats['process'] . ", Completed=" . $stats['completed'] . "\n";
    
} catch (Exception $e) {
    echo "   ✗ Complaint model test failed: " . $e->getMessage() . "\n";
}

// Test 5: Validation Helper
echo "\n5. Testing Validation Helper...\n";
try {
    // Test email validation
    $validEmail = Validation::email('test@example.com');
    $invalidEmail = Validation::email('invalid-email');
    
    if ($validEmail && !$invalidEmail) {
        echo "   ✓ Email validation working correctly\n";
    } else {
        echo "   ✗ Email validation failed\n";
    }
    
    // Test phone validation
    $validPhone = Validation::phone('08123456789');
    $invalidPhone = Validation::phone('123');
    
    if ($validPhone && !$invalidPhone) {
        echo "   ✓ Phone validation working correctly\n";
    } else {
        echo "   ✗ Phone validation failed\n";
    }
    
    // Test required validation
    $requiredTest = Validation::required('test', 'Test Field');
    $emptyTest = Validation::required('', 'Test Field');
    
    if ($requiredTest === true && $emptyTest !== true) {
        echo "   ✓ Required validation working correctly\n";
    } else {
        echo "   ✗ Required validation failed\n";
    }
    
} catch (Exception $e) {
    echo "   ✗ Validation helper test failed: " . $e->getMessage() . "\n";
}

// Test 6: Logger Helper
echo "\n6. Testing Logger Helper...\n";
try {
    // Test logging
    Logger::info('Test log entry from test script');
    Logger::error('Test error log entry');
    Logger::activity('Test Activity', 'test_user');
    
    // Check if log file was created
    if (file_exists('logs/app.log')) {
        echo "   ✓ Log file created successfully\n";
        
        // Get recent logs
        $recentLogs = Logger::getRecentLogs(5);
        echo "   ✓ Retrieved " . count($recentLogs) . " recent log entries\n";
    } else {
        echo "   ✗ Log file not created\n";
    }
    
} catch (Exception $e) {
    echo "   ✗ Logger helper test failed: " . $e->getMessage() . "\n";
}

// Test 7: File Permissions
echo "\n7. Testing File Permissions...\n";

$checkDirs = [
    'public/uploads' => 'Upload directory',
    'logs' => 'Logs directory'
];

foreach ($checkDirs as $dir => $description) {
    if (is_dir($dir)) {
        if (is_writable($dir)) {
            echo "   ✓ $description is writable\n";
        } else {
            echo "   ✗ $description is not writable\n";
        }
    } else {
        echo "   ✗ $description does not exist\n";
    }
}

// Test 8: Configuration
echo "\n8. Testing Configuration...\n";

$requiredConstants = [
    'BASE_URL' => 'Base URL',
    'APP_NAME' => 'Application Name',
    'DB_HOST' => 'Database Host',
    'DB_NAME' => 'Database Name',
    'DEBUG' => 'Debug Mode'
];

foreach ($requiredConstants as $constant => $description) {
    if (defined($constant)) {
        echo "   ✓ $description is defined: " . constant($constant) . "\n";
    } else {
        echo "   ✗ $description is not defined\n";
    }
}

// Test 9: Session
echo "\n9. Testing Session...\n";
if (session_status() === PHP_SESSION_ACTIVE) {
    echo "   ✓ Session is active\n";
    echo "   ✓ Session name: " . session_name() . "\n";
} else {
    echo "   ✗ Session is not active\n";
}

echo "\n=== TEST SUMMARY ===\n";
echo "All basic functionality tests completed.\n";
echo "If you see any ✗ marks above, please check the corresponding functionality.\n\n";

echo "Manual testing checklist:\n";
echo "□ Access homepage at " . BASE_URL . "\n";
echo "□ Register a new user account\n";
echo "□ Login with the new account\n";
echo "□ Create a complaint with photo upload\n";
echo "□ Login as admin (admin@pengaduan.com / admin123)\n";
echo "□ View and manage complaints in admin panel\n";
echo "□ Add/edit categories\n";
echo "□ Generate and export reports\n";
echo "□ Test responsive design on mobile\n";
echo "□ Test all form validations\n";
?>
