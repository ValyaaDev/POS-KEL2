<?php
declare(strict_types=1);
session_start();

$host = 'localhost';
$dbname = 'pos_native';
$dbuser = 'root';
$dbpass = ''; // Sesuaikan jika MySQL Anda memakai password.

try {
    $pdo = new PDO(
        "mysql:host={$host};dbname={$dbname};charset=utf8mb4",
        $dbuser,
        $dbpass,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ]
    );
} catch (PDOException $e) {
    http_response_code(500);
    exit('Koneksi database gagal. Pastikan database pos_native sudah di-import dan pengaturan config.php benar.');
}

function e($value): string {
    return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
}
function rupiah($value): string {
    return 'Rp ' . number_format((float)$value, 0, ',', '.');
}
function redirect(string $url = 'index.php'): never {
    header('Location: ' . $url);
    exit;
}
function flash(string $message, string $type = 'success'): void {
    $_SESSION['flash'] = ['message' => $message, 'type' => $type];
}
function require_login(): void {
    if (empty($_SESSION['user'])) redirect('index.php?page=login');
}
function require_admin(): void {
    require_login();
    if (($_SESSION['user']['role'] ?? '') !== 'admin') {
        flash('Menu ini hanya dapat diakses admin.', 'danger');
        redirect('index.php');
    }
}
function csrf_field(): string {
    if (empty($_SESSION['csrf'])) $_SESSION['csrf'] = bin2hex(random_bytes(32));
    return '<input type="hidden" name="csrf" value="' . e($_SESSION['csrf']) . '">';
}
function verify_csrf(): void {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $token = $_POST['csrf'] ?? '';
        if (!hash_equals($_SESSION['csrf'] ?? '', $token)) {
            http_response_code(419);
            exit('Token keamanan tidak valid. Muat ulang halaman dan coba lagi.');
        }
    }
}

// Buat akun awal otomatis saat tabel users masih kosong.
$count = (int)$pdo->query('SELECT COUNT(*) FROM users')->fetchColumn();
if ($count === 0) {
    $stmt = $pdo->prepare('INSERT INTO users (nama, username, password, role) VALUES (?, ?, ?, ?)');
    $stmt->execute(['Administrator', 'admin', password_hash('admin123', PASSWORD_DEFAULT), 'admin']);
    $stmt->execute(['Kasir', 'kasir', password_hash('kasir123', PASSWORD_DEFAULT), 'kasir']);
}
