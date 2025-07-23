<?php
/**
 * Export Database Structure and Sample Data
 * Run this script to generate SQL file for deployment
 */

require_once '../app/config/config.php';

// Set headers for download
header('Content-Type: application/sql');
header('Content-Disposition: attachment; filename="pengaduan_warga_' . date('Y-m-d_H-i-s') . '.sql"');

try {
    $pdo = new PDO('mysql:host=' . DB_HOST . ';dbname=' . DB_NAME, DB_USER, DB_PASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "-- Pengaduan Warga Database Export\n";
    echo "-- Generated on: " . date('Y-m-d H:i:s') . "\n";
    echo "-- Database: " . DB_NAME . "\n\n";
    
    echo "SET SQL_MODE = \"NO_AUTO_VALUE_ON_ZERO\";\n";
    echo "START TRANSACTION;\n";
    echo "SET time_zone = \"+00:00\";\n\n";
    
    // Get all tables
    $tables = $pdo->query("SHOW TABLES")->fetchAll(PDO::FETCH_COLUMN);
    
    foreach ($tables as $table) {
        echo "-- --------------------------------------------------------\n";
        echo "-- Table structure for table `$table`\n";
        echo "-- --------------------------------------------------------\n\n";
        
        // Drop table if exists
        echo "DROP TABLE IF EXISTS `$table`;\n";
        
        // Create table structure
        $createTable = $pdo->query("SHOW CREATE TABLE `$table`")->fetch();
        echo $createTable[1] . ";\n\n";
        
        // Insert data
        echo "-- Dumping data for table `$table`\n\n";
        
        $rows = $pdo->query("SELECT * FROM `$table`")->fetchAll(PDO::FETCH_ASSOC);
        
        if (!empty($rows)) {
            $columns = array_keys($rows[0]);
            $columnList = '`' . implode('`, `', $columns) . '`';
            
            echo "INSERT INTO `$table` ($columnList) VALUES\n";
            
            $values = [];
            foreach ($rows as $row) {
                $rowValues = [];
                foreach ($row as $value) {
                    if ($value === null) {
                        $rowValues[] = 'NULL';
                    } else {
                        $rowValues[] = "'" . addslashes($value) . "'";
                    }
                }
                $values[] = '(' . implode(', ', $rowValues) . ')';
            }
            
            echo implode(",\n", $values) . ";\n\n";
        }
    }
    
    echo "COMMIT;\n";
    
} catch (PDOException $e) {
    echo "-- Error: " . $e->getMessage() . "\n";
}
?>
