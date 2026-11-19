<?php
/**
 * Front controller POSKU (pola MVC).
 * - models/       : akses database (query)
 * - views/        : tampilan HTML
 * - controllers/  : validasi input & alur aplikasi
 */
require __DIR__ . '/config.php';
verify_csrf();

foreach (['User', 'Kategori', 'Produk', 'Pelanggan', 'Transaksi'] as $m) {
    require __DIR__ . '/models/' . $m . '.php';
}
foreach (['Auth', 'Dashboard', 'Kategori', 'Produk', 'Pelanggan', 'Transaksi', 'Laporan'] as $c) {
    require __DIR__ . '/controllers/' . $c . 'Controller.php';
}

$page = $_GET['page'] ?? (isset($_SESSION['user']) ? 'dashboard' : 'login');

// Aksi POST yang tidak butuh login
$publicActions = [
    'login'  => ['AuthController', 'login'],
    'logout' => ['AuthController', 'logout'],
];
// Aksi POST yang wajib login
$actions = [
    'save_category'   => ['KategoriController', 'save'],
    'delete_category' => ['KategoriController', 'delete'],
    'save_product'    => ['ProdukController', 'save'],
    'delete_product'  => ['ProdukController', 'delete'],
    'save_customer'   => ['PelangganController', 'save'],
    'delete_customer' => ['PelangganController', 'delete'],
    'checkout'        => ['TransaksiController', 'checkout'],
];
// Halaman (GET)
$routes = [
    'login'      => ['AuthController', 'showLogin'],
    'dashboard'  => ['DashboardController', 'index'],
    'categories' => ['KategoriController', 'index'],
    'products'   => ['ProdukController', 'index'],
    'customers'  => ['PelangganController', 'index'],
    'pos'        => ['TransaksiController', 'pos'],
    'reports'    => ['LaporanController', 'index'],
    'receipt'    => ['TransaksiController', 'receipt'],
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    if (isset($publicActions[$action])) {
        [$class, $method] = $publicActions[$action];
        (new $class($pdo))->$method();
    }
    require_login();
    if (isset($actions[$action])) {
        [$class, $method] = $actions[$action];
        (new $class($pdo))->$method();
    }
}

if ($page !== 'login') {
    require_login();
}

[$class, $method] = $routes[$page] ?? $routes['dashboard'];
(new $class($pdo))->$method();
