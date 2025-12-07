<?php

class AuthController extends Controller
{
    public function index()
    {
        $this->startSession();

        if (isset($_SESSION['user_id'])) {
            header('Location: ' . BASEURL . '/dashboard');
            exit;
        }
        $data['title'] = 'Login';
        $this->view('auth/login', $data);
    }

    public function login()
    {
        $this->startSession();

        $username = $_POST['username'] ?? '';
        $password = $_POST['password'] ?? '';

        // 1. VALIDASI INPUT KOSONG
        if (empty($username) || empty($password)) {
            $data['title'] = 'Login';
            $data['error'] = 'Username dan Password wajib diisi!';
            $this->view('auth/login', $data);
            return; // Hentikan proses
        }

        $model = $this->model('PenggunaModel');
        $user = $model->findByLoginName($username);

        // Cek User & Password
        if ($user && password_verify($password, $user['password'])) {

            $_SESSION['user_id'] = $user['id_pengguna'];
            $_SESSION['user_name'] = $user['nama_lengkap'];
            $_SESSION['user_role'] = $user['role'];

            header('Location: ' . BASEURL . '/dashboard');
            exit;
        } else {
            $data['title'] = 'Login';
            $data['error'] = 'Username atau password salah';
            $this->view('auth/login', $data);
        }
    }

    public function logout()
    {
        $this->startSession();
        session_destroy();
        header('Location: ' . BASEURL . '/auth');
        exit;
    }

    public function register()
    {
        $data['title'] = 'Registrasi Pengguna';
        $this->view('auth/register', $data);
    }

    public function doRegister()
    {
        $this->startSession();

        $nama = $_POST['nama'] ?? '';
        $username = $_POST['username'] ?? '';
        $password = $_POST['password'] ?? '';

        // 2. VALIDASI INPUT KOSONG (BACKEND)
        if (empty($nama) || empty($username) || empty($password)) {
            $data['title'] = 'Registrasi Pengguna';
            $data['error'] = 'Semua kolom (Nama, Username, Password) wajib diisi!';
            $this->view('auth/register', $data);
            return; // Hentikan proses simpan
        }

        // Siapkan data
        $data = [
            'nama_lengkap' => $nama,
            'username' => $username,
            'password' => $password,
            'role' => 'farmer' // Default role
        ];

        // Simpan ke DB
        if ($this->model('PenggunaModel')->tambahDataPengguna($data) > 0) {
            header('Location: ' . BASEURL . '/auth');
            exit;
        } else {
            // Jika gagal simpan (misal username kembar)
            $data['title'] = 'Registrasi Pengguna';
            $data['error'] = 'Gagal mendaftar. Username mungkin sudah digunakan.';
            $this->view('auth/register', $data);
        }
    }
}