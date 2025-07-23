<?php
class Logger {
    private static $logFile = 'logs/app.log';
    
    // Log error
    public static function error($message, $context = []) {
        self::log('ERROR', $message, $context);
    }
    
    // Log warning
    public static function warning($message, $context = []) {
        self::log('WARNING', $message, $context);
    }
    
    // Log info
    public static function info($message, $context = []) {
        self::log('INFO', $message, $context);
    }
    
    // Log debug
    public static function debug($message, $context = []) {
        self::log('DEBUG', $message, $context);
    }
    
    // Main log method
    private static function log($level, $message, $context = []) {
        // Create logs directory if it doesn't exist
        $logDir = dirname(self::$logFile);
        if (!is_dir($logDir)) {
            mkdir($logDir, 0755, true);
        }
        
        // Prepare log entry
        $timestamp = date('Y-m-d H:i:s');
        $contextString = !empty($context) ? ' | Context: ' . json_encode($context) : '';
        $logEntry = "[{$timestamp}] {$level}: {$message}{$contextString}" . PHP_EOL;
        
        // Write to log file
        file_put_contents(self::$logFile, $logEntry, FILE_APPEND | LOCK_EX);
    }
    
    // Log user activity
    public static function activity($action, $userId = null, $details = []) {
        $userId = $userId ?? ($_SESSION['user_id'] ?? 'Guest');
        $userAgent = $_SERVER['HTTP_USER_AGENT'] ?? 'Unknown';
        $ip = self::getClientIP();
        
        $context = array_merge($details, [
            'user_id' => $userId,
            'ip' => $ip,
            'user_agent' => $userAgent,
            'url' => $_SERVER['REQUEST_URI'] ?? ''
        ]);
        
        self::info("User Activity: {$action}", $context);
    }
    
    // Log database errors
    public static function dbError($query, $error, $params = []) {
        $context = [
            'query' => $query,
            'error' => $error,
            'params' => $params
        ];
        
        self::error('Database Error', $context);
    }
    
    // Log security events
    public static function security($event, $details = []) {
        $ip = self::getClientIP();
        $userAgent = $_SERVER['HTTP_USER_AGENT'] ?? 'Unknown';
        
        $context = array_merge($details, [
            'ip' => $ip,
            'user_agent' => $userAgent,
            'timestamp' => time()
        ]);
        
        self::warning("Security Event: {$event}", $context);
    }
    
    // Get client IP address
    private static function getClientIP() {
        $ipKeys = ['HTTP_CLIENT_IP', 'HTTP_X_FORWARDED_FOR', 'REMOTE_ADDR'];
        
        foreach ($ipKeys as $key) {
            if (array_key_exists($key, $_SERVER) === true) {
                foreach (explode(',', $_SERVER[$key]) as $ip) {
                    $ip = trim($ip);
                    if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE) !== false) {
                        return $ip;
                    }
                }
            }
        }
        
        return $_SERVER['REMOTE_ADDR'] ?? 'Unknown';
    }
    
    // Clear old logs (keep last 30 days)
    public static function clearOldLogs($days = 30) {
        $logFile = self::$logFile;
        
        if (!file_exists($logFile)) {
            return;
        }
        
        $lines = file($logFile, FILE_IGNORE_NEW_LINES);
        $cutoffDate = date('Y-m-d', strtotime("-{$days} days"));
        $newLines = [];
        
        foreach ($lines as $line) {
            if (preg_match('/^\[(\d{4}-\d{2}-\d{2})/', $line, $matches)) {
                if ($matches[1] >= $cutoffDate) {
                    $newLines[] = $line;
                }
            }
        }
        
        file_put_contents($logFile, implode(PHP_EOL, $newLines) . PHP_EOL);
    }
    
    // Get recent logs
    public static function getRecentLogs($lines = 100) {
        $logFile = self::$logFile;
        
        if (!file_exists($logFile)) {
            return [];
        }
        
        $allLines = file($logFile, FILE_IGNORE_NEW_LINES);
        return array_slice($allLines, -$lines);
    }
}
?>
