<?php
require_once __DIR__ . '/BaseController.php';

class AuthController extends BaseController
{
    public function showLogin(): void
    {
        if (!empty($_SESSION['user'])) redirect();
        $this->render('auth/login', [], 'Login');
    }

    public function login(): void
    {
        $user = (new User($this->db))->findByUsername(trim($_POST['username'] ?? ''));
        if ($user && password_verify($_POST['password'] ?? '', $user['password'])) {
            session_regenerate_id(true);
            $_SESSION['user'] = ['id' => $user['id'], 'nama' => $user['nama'], 'username' => $user['username'], 'role' => $user['role']];
            flash('Selamat datang, ' . $user['nama'] . '!');
            redirect();
        }
        flash('Username atau password salah.', 'danger');
        redirect('index.php?page=login');
    }

    public function logout(): void
    {
        $_SESSION = [];
        session_destroy();
        redirect('index.php?page=login');
    }
}
