<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title><?= isset($title) ? htmlspecialchars($title) : 'GDSS Hidroponik' ?></title>
    <link rel="stylesheet" href="/assets/css/style.css?v=<?= time() ?>">
</head>

<body>

    <?php
    if (session_status() === PHP_SESSION_NONE)
        session_start();

    $isLoggedIn = !empty($_SESSION['user_id']);
    $role = $_SESSION['user_role'] ?? '';

    $current_uri = $_SERVER['REQUEST_URI'];

    // --- PERBAIKAN LOGIKA DETEKSI HALAMAN LOGIN ---
    // Cek apakah URL mengandung kata kunci login/register/auth
    // ATAU apakah URL sangat pendek (hanya '/' atau '/public/') yang berarti halaman utama (login)
    $isAuthPage = (stripos($current_uri, 'login') !== false) ||
        (stripos($current_uri, 'register') !== false) ||
        (stripos($current_uri, 'Auth') !== false) ||
        ($current_uri === '/' || $current_uri === '/public/');

    // Logika Menu Aktif
    $isActive = function ($keyword) use ($current_uri) {
        if ($keyword == '/Dashboard') {
            return (strpos($current_uri, '/Dashboard') !== false) ? 'active' : '';
        }
        return (strpos($current_uri, $keyword) !== false) ? 'active' : '';
    };
    ?>

    <?php if ($isLoggedIn && !$isAuthPage): ?>
        <header class="navbar">
            <div class="navbar-inner">
                <span class="navbar-title">GDSS Hidroponik (<?= ucfirst($role) ?>)</span>

                <nav>
                    <a href="/Dashboard/index" class="<?= $isActive('/Dashboard') ?>">Dashboard</a>
                    <a href="/Penilaian/form" class="<?= $isActive('/Penilaian') ?>">Input Penilaian</a>

                    <?php if ($role === 'admin'): ?>
                        <a href="/MasterData/alternatif" class="<?= $isActive('/MasterData/alternatif') ?>">Alternatif</a>
                        <a href="/MasterData/kriteria" class="<?= $isActive('/MasterData/kriteria') ?>">Kriteria</a>
                        <a href="/MasterData/pengguna" class="<?= $isActive('/MasterData/pengguna') ?>">Pengguna</a>
                    <?php endif; ?>

                    <?php if ($role === 'admin' || $role === 'ketua'): ?>
                        <a href="/Proses/index" class="<?= $isActive('/Proses') ?>">Proses Hitung</a>
                        <a href="/Laporan/hasil" class="<?= $isActive('/Laporan/hasil') ?>">Hasil Akhir</a>
                        <a href="/Laporan/detail" class="<?= $isActive('/Laporan/detail') ?>">Detail</a>
                        <a href="/Laporan/perhitungan" class="<?= $isActive('/Laporan/perhitungan') ?>">Perhitungan</a>
                    <?php endif; ?>

                    <a href="/Auth/logout" onclick="return confirm('Yakin ingin keluar?');"
                        style="color: #ffcdd2;">Logout</a>
                </nav>
            </div>
        </header>
    <?php endif; ?>

    <main class="container">
        <?= $content ?>
    </main>

    <script src="/assets/js/script.js"></script>
</body>

</html>