<?php
// Load environment variables
require_once 'vendor/autoload.php'; // Ensure you use Composer to install vlucas/phpdotenv
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

// Database configuration
$config = [
    'server' => $_ENV['DB_SERVER'],      // Load from environment variables
    'username' => $_ENV['DB_USERNAME'],
    'password' => $_ENV['DB_PASSWORD'],
    'database' => $_ENV['DB_NAME'],
];

// Create a secure database connection
try {
    $dsn = "mysql:host={$config['server']};dbname={$config['database']};charset=utf8mb4";
    $pdo = new PDO($dsn, $config['username'], $config['password'], [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ]);
    echo "Connection successful!";
} catch (PDOException $e) {
    // Log error details securely
    error_log("Database connection error: " . $e->getMessage());
    die("Database connection failed. Please contact support.");
}

// Example: Using prepared statements for a query
$username = 'example_user';
$stmt = $pdo->prepare("SELECT * FROM users WHERE username = :username");
$stmt->execute(['username' => $username]);
$user = $stmt->fetch();

if ($user) {
    echo "User found: " . htmlspecialchars($user['username']);
} else {
    echo "User not found.";
}
