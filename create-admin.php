<?php
/**
 * Script untuk membuat akun admin baru
 */

require_once 'app/config/config.php';

echo "<h1>Create Admin Account</h1>";

// Database connection
try {
    $pdo = new PDO('mysql:host=' . DB_HOST . ';dbname=' . DB_NAME, DB_USER, DB_PASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "<p style='color: green;'>✓ Database connection successful</p>";
} catch (PDOException $e) {
    die("<p style='color: red;'>✗ Database connection failed: " . $e->getMessage() . "</p>");
}

// Process form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    $confirm_password = trim($_POST['confirm_password']);
    
    $errors = [];
    
    // Validation
    if (empty($name)) {
        $errors[] = "Nama wajib diisi";
    }
    
    if (empty($email)) {
        $errors[] = "Email wajib diisi";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Format email tidak valid";
    }
    
    if (empty($password)) {
        $errors[] = "Password wajib diisi";
    } elseif (strlen($password) < 6) {
        $errors[] = "Password minimal 6 karakter";
    }
    
    if ($password !== $confirm_password) {
        $errors[] = "Konfirmasi password tidak sama";
    }
    
    // Check if email already exists
    if (empty($errors)) {
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM users WHERE email = ?");
        $stmt->execute([$email]);
        if ($stmt->fetchColumn() > 0) {
            $errors[] = "Email sudah terdaftar";
        }
    }
    
    // Create admin if no errors
    if (empty($errors)) {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        
        try {
            $stmt = $pdo->prepare("INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, 'admin')");
            $stmt->execute([$name, $email, $hashedPassword]);
            
            echo "<div style='background: #d4edda; color: #155724; padding: 15px; border: 1px solid #c3e6cb; border-radius: 5px; margin: 20px 0;'>";
            echo "<h3>✓ Admin berhasil dibuat!</h3>";
            echo "<p><strong>Nama:</strong> $name</p>";
            echo "<p><strong>Email:</strong> $email</p>";
            echo "<p><strong>Role:</strong> Admin</p>";
            echo "<p><a href='" . BASE_URL . "index.php?url=auth/login'>Login sekarang</a></p>";
            echo "</div>";
            
        } catch (PDOException $e) {
            $errors[] = "Gagal membuat admin: " . $e->getMessage();
        }
    }
    
    // Show errors
    if (!empty($errors)) {
        echo "<div style='background: #f8d7da; color: #721c24; padding: 15px; border: 1px solid #f5c6cb; border-radius: 5px; margin: 20px 0;'>";
        echo "<h3>Error:</h3>";
        echo "<ul>";
        foreach ($errors as $error) {
            echo "<li>$error</li>";
        }
        echo "</ul>";
        echo "</div>";
    }
}

// Show existing admins
echo "<h2>Existing Admin Accounts:</h2>";
try {
    $stmt = $pdo->prepare("SELECT id, name, email, created_at FROM users WHERE role = 'admin' ORDER BY created_at DESC");
    $stmt->execute();
    $admins = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    if (count($admins) > 0) {
        echo "<table border='1' cellpadding='10' cellspacing='0' style='border-collapse: collapse; width: 100%;'>";
        echo "<tr style='background: #f8f9fa;'>";
        echo "<th>ID</th><th>Nama</th><th>Email</th><th>Dibuat</th>";
        echo "</tr>";
        
        foreach ($admins as $admin) {
            echo "<tr>";
            echo "<td>" . $admin['id'] . "</td>";
            echo "<td>" . htmlspecialchars($admin['name']) . "</td>";
            echo "<td>" . htmlspecialchars($admin['email']) . "</td>";
            echo "<td>" . date('d/m/Y H:i', strtotime($admin['created_at'])) . "</td>";
            echo "</tr>";
        }
        echo "</table>";
    } else {
        echo "<p>Belum ada admin yang terdaftar.</p>";
    }
} catch (PDOException $e) {
    echo "<p style='color: red;'>Error mengambil data admin: " . $e->getMessage() . "</p>";
}
?>

<h2>Create New Admin Account:</h2>
<form method="POST" style="max-width: 500px;">
    <table>
        <tr>
            <td><label for="name">Nama Lengkap:</label></td>
            <td><input type="text" name="name" id="name" required style="width: 200px; padding: 5px;"></td>
        </tr>
        <tr>
            <td><label for="email">Email:</label></td>
            <td><input type="email" name="email" id="email" required style="width: 200px; padding: 5px;"></td>
        </tr>
        <tr>
            <td><label for="password">Password:</label></td>
            <td><input type="password" name="password" id="password" required style="width: 200px; padding: 5px;"></td>
        </tr>
        <tr>
            <td><label for="confirm_password">Konfirmasi Password:</label></td>
            <td><input type="password" name="confirm_password" id="confirm_password" required style="width: 200px; padding: 5px;"></td>
        </tr>
        <tr>
            <td colspan="2">
                <button type="submit" style="background: #007bff; color: white; padding: 10px 20px; border: none; border-radius: 5px; cursor: pointer;">
                    Buat Admin
                </button>
            </td>
        </tr>
    </table>
</form>

<h2>Quick Login Links:</h2>
<ul>
    <li><a href="<?php echo BASE_URL; ?>index.php?url=auth/login">Login Page</a></li>
    <li><a href="<?php echo BASE_URL; ?>">Homepage</a></li>
</ul>

<style>
body { font-family: Arial, sans-serif; margin: 20px; }
table { margin: 20px 0; }
td { padding: 8px; }
input { margin: 5px 0; }
</style>
