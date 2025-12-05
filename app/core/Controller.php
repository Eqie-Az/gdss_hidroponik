<?php
class Controller
{
    protected function view($view, $data = [])
    {
        extract($data);
        ob_start();
        require __DIR__ . '/../views/' . $view . '.php';
        $content = ob_get_clean();
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
            echo "<h1>Akses Ditolak (403)</h1><p>Anda tidak memiliki izin mengakses halaman ini.</p><a href='" . BASEURL . "/Dashboard'>Kembali</a>";
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