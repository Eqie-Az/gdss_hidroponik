<?php
// app/controllers/AuthController.php

require_once __DIR__ . '/../models/PenggunaModel.php';

class AuthController extends Controller
{
    public function index()
    {
        $title = 'Login';
        $this->view('auth/login', compact('title'));
    }

    public function login()
{
    $this->startSession();

    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    $model = new PenggunaModel();
    $user  = $model->findByLoginName($username);

    // SEBELUMNYA:
    // if ($user && password_verify($password, $user['kata_sandi_hash'])) {

    // GANTI JADI CEK BIASA:
    if ($user && $password === $user['kata_sandi_hash']) {

        $_SESSION['user_id']   = $user['id_pengguna'];
        $_SESSION['user_name'] = $user['nama_pengguna'];
        $_SESSION['user_role'] = $user['peran'];

        $this->redirect('Dashboard/index');
    } else {
        $title = 'Login';
        $error = 'Username atau password salah';
        $this->view('auth/login', compact('title','error'));
    }
}


    public function logout()
    {
        $this->startSession();
        session_destroy();
        $this->redirect('Auth/index');
    }

    public function register()
    {
        $title = 'Registrasi Pengguna';
        $this->view('auth/register', compact('title'));
    }

    public function doRegister()
    {
        $nama     = $_POST['nama'] ?? '';
        $username = $_POST['username'] ?? '';
        $password = $_POST['password'] ?? '';
        $peran    = $_POST['peran'] ?? 'dm';

        $model = new PenggunaModel();
        $model->create($nama, $username, $password, $peran);

        $this->redirect('Auth/index');
    }
}
