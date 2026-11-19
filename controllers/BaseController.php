<?php
/** Menandai menu sidebar yang sedang aktif (dipakai views/layout/header.php). */
function active(string $p): string
{
    return (($_GET['page'] ?? 'dashboard') === $p) ? 'active' : '';
}

abstract class BaseController
{
    protected PDO $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    /** Menampilkan view di dalam layout (header + footer). */
    protected function render(string $view, array $data = [], string $title = 'Dashboard'): void
    {
        $flash = $_SESSION['flash'] ?? null;
        unset($_SESSION['flash']);
        extract($data);
        require __DIR__ . '/../views/layout/header.php';
        require __DIR__ . '/../views/' . $view . '.php';
        require __DIR__ . '/../views/layout/footer.php';
    }
}
