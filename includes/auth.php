<?php
/**
 * Authentication helpers
 */

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!defined('APP_NAME')) {
    if (function_exists('config')) {
        $appConfig = config('app', []);
    } elseif (function_exists('env')) {
        $appConfig = require __DIR__ . '/../config/app.php';
    } else {
        $appConfig = [];
    }
    $appName = $appConfig['name'] ?? (function_exists('env') ? env('APP_NAME', 'CSP Learning Portal') : 'CSP Learning Portal');
    define('APP_NAME', $appName);
}

if (!defined('APP_TAGLINE')) {
    $appConfig = isset($appConfig) ? $appConfig : [];
    $appTagline = $appConfig['tagline'] ?? (function_exists('env') ? env('APP_TAGLINE', 'A Web-based Student Information and Learning Materials System') : 'A Web-based Student Information and Learning Materials System');
    define('APP_TAGLINE', $appTagline);
}

if (!defined('BASE_URL')) {
    $appUrl = function_exists('env') ? env('APP_URL', null) : null;
    if (!$appUrl) {
        $scheme = ((!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') || ($_SERVER['SERVER_PORT'] ?? '') == '443') ? 'https' : 'http';
        $host = $_SERVER['HTTP_HOST'] ?? 'localhost';
        $scriptPath = $_SERVER['SCRIPT_NAME'] ?? '';
        $path = rtrim(str_replace('\\', '/', dirname($scriptPath)), '/');
        $appUrl = $scheme . '://' . $host . ($path === '' || $path === '/' ? '' : $path);
    }
    define('BASE_URL', rtrim($appUrl, '/') . '/');
}

if (!function_exists('getDB')) {
    function getDB(): \PDO {
        static $pdo = null;
        if ($pdo instanceof \PDO) {
            return $pdo;
        }

        $dbHost = function_exists('env') ? env('DB_HOST', '127.0.0.1') : (getenv('DB_HOST') ?: '127.0.0.1');
        $dbPort = function_exists('env') ? env('DB_PORT', '3306') : (getenv('DB_PORT') ?: '3306');
        $dbName = function_exists('env') ? env('DB_DATABASE', 'csp_learning_portal') : (getenv('DB_DATABASE') ?: 'csp_learning_portal');
        $dbUser = function_exists('env') ? env('DB_USERNAME', 'root') : (getenv('DB_USERNAME') ?: 'root');
        $dbPass = function_exists('env') ? env('DB_PASSWORD', '') : (getenv('DB_PASSWORD') ?: '');

        $dsn = sprintf('mysql:host=%s;port=%s;dbname=%s;charset=utf8mb4', $dbHost, $dbPort, $dbName);
        $pdo = new \PDO($dsn, $dbUser, $dbPass, [
            \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
            \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC,
            \PDO::ATTR_EMULATE_PREPARES => false,
        ]);

        return $pdo;
    }
}

function requireLogin(): void {
    if (!isset($_SESSION['user_id'])) {
        header('Location: ' . BASE_URL . 'login.php?redirect=' . urlencode($_SERVER['REQUEST_URI']));
        exit;
    }
}

function requireRole(string ...$roles): void {
    requireLogin();
    if (!in_array($_SESSION['role'] ?? '', $roles, true)) {
        header('HTTP/1.1 403 Forbidden');
        echo 'Access denied.';
        exit;
    }
}

function currentUser(): ?array {
    if (!isset($_SESSION['user_id'])) return null;
    $pdo = getDB();
    $stmt = $pdo->prepare('SELECT u.*, s.id as student_id, s.student_number, s.program, s.year_level, s.status as student_status FROM users u LEFT JOIN students s ON s.user_id = u.id WHERE u.id = ?');
    $stmt->execute([$_SESSION['user_id']]);
    return $stmt->fetch() ?: null;
}

function login(string $email, string $password): bool {
    $pdo = getDB();
    $stmt = $pdo->prepare('SELECT id, password_hash, role, full_name FROM users WHERE email = ?');
    $stmt->execute([$email]);
    $user = $stmt->fetch();
    if (!$user || !password_verify($password, $user['password_hash'])) return false;
    $_SESSION['user_id'] = $user['id'];
    $_SESSION['role'] = $user['role'];
    $_SESSION['full_name'] = $user['full_name'];
    return true;
}

function logout(): void {
    $_SESSION = [];
    if (ini_get('session.use_cookies')) {
        $p = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000, $p['path'], $p['domain'], $p['secure'], $p['httponly']);
    }
    session_destroy();
}
