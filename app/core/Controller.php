<?php
class Controller
{
    /**
     * Method untuk memanggil model
     * Contoh penggunaan: $this->model('PenggunaModel');
     */
    public function model($model)
    {
        require_once __DIR__ . '/../models/' . $model . '.php';
        return new $model;
    }

    protected function view($view, $data = [])
    {
        extract($data);
        ob_start();
        // Memuat view konten utama
        require __DIR__ . '/../views/' . $view . '.php';
        $content = ob_get_clean();

        // Memuat layout utama (header/footer/sidebar) yang akan membungkus $content
        require __DIR__ . '/../views/layout/utama.php';
    }

    protected function redirect($path)
    {
        header('Location: ' . BASEURL . '/' . ltrim($path, '/'));
        exit;
    }

    protected function startSession()
    {
        if (session_status() === PHP_SESSION_NONE)
            session_start();
    }

    protected function requireLogin()
    {
        $this->startSession();
        if (empty($_SESSION['user_id'])) {
            $this->redirect('Auth/index');
        }
    }

    protected function requireRole($allowedRoles = [])
    {
        $this->requireLogin();
        // Jika user tidak punya salah satu role yang diizinkan, tolak.
        if (!in_array($_SESSION['user_role'], $allowedRoles)) {
            // Tampilan sederhana untuk akses ditolak
            echo "<div style='text-align:center; margin-top:50px;'>";
            echo "<h1>Akses Ditolak (403)</h1>";
            echo "<p>Anda tidak memiliki izin mengakses halaman ini.</p>";
            echo "<a href='" . BASEURL . "/Dashboard'>Kembali ke Dashboard</a>";
            echo "</div>";
            exit;
        }
    }

    protected function setFlash($key, $message)
    {
        $this->startSession();
        $_SESSION['flash'][$key] = $message;
    }

    protected function getFlash($key)
    {
        $this->startSession();
        if (isset($_SESSION['flash'][$key])) {
            $msg = $_SESSION['flash'][$key];
            unset($_SESSION['flash'][$key]);
            return $msg;
        }
        return null;
    }
}