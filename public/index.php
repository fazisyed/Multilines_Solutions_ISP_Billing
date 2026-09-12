<?php
/**
 * Multilines Solutions ISP Billing Platform
 * Entry Point Router & Bootstrap
 */

// Strict error reporting for development & debugging (adjust to 0 in production)
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Define base application path constant if not already defined
if (!defined('BASE_PATH')) {
    define('BASE_PATH', dirname(__DIR__));
}

// Start Secure Session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Modern PSR-4 Compliant Autoloader
spl_autoload_register(function ($class) {
    $prefix = 'App\\';
    $base_dir = BASE_PATH . '/app/';

    $len = strlen($prefix);
    if (strncmp($prefix, $class, $len) !== 0) {
        return;
    }

    $relative_class = substr($class, $len);
    $file = $base_dir . str_replace('\\', '/', $relative_class) . '.php';

    if (file_exists($file)) {
        require_once $file;
    }
});

// Load Essential Core Dependencies & Configuration
require_once BASE_PATH . '/config/database.php';
require_once BASE_PATH . '/app/Core/Database.php';
require_once BASE_PATH . '/app/Core/Helpers.php';

// Initialize and Run Application Core
try {
    $app = new App\Core\App();
} catch (\Exception $e) {
    // Graceful error handling for fatal application exceptions
    http_response_code(500);
    echo "<div style='font-family: Inter, sans-serif; padding: 40px; text-align: center; color: #1e293b;'>";
    echo "<h2>System Initialization Error</h2>";
    echo "<p>Unable to load application core components. Please check your system logs.</p>";
    if (ini_get('display_errors')) {
        echo "<pre style='background: #f1f5f9; padding: 15px; border-radius: 8px; text-align: left; max-width: 800px; margin: 20px auto; color: #ef4444;'>" . htmlspecialchars($e->getMessage()) . "</pre>";
    }
    echo "</div>";
}