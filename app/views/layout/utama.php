<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title><?= isset($title) ? htmlspecialchars($title) : 'GDSS Hidroponik' ?></title>
    <!-- Tambahkan time() agar CSS tidak kena cache -->
    <link rel="stylesheet" href="/assets/css/style.css?v=<?= time() ?>">
</head>

<body>

    <?php
    if (session_status() === PHP_SESSION_NONE)
        session_start();
    $isLoggedIn = !empty($_SESSION['user_id']);
    $role = $_SESSION['user_role'] ?? '';

    // --- LOGIKA UNTUK MENANDAI MENU AKTIF ---
    $current_uri = $_SERVER['REQUEST_URI'];

    // Fungsi sederhana untuk cek apakah URL mengandung kata tertentu
    $isActive = function ($keyword) use ($current_uri) {
        // Khusus Dashboard (Home)
        if ($keyword == '/Dashboard') {
            return (strpos($current_uri, '/Dashboard') !== false || $current_uri == '/' || $current_uri == '/public/') ? 'active' : '';
        }
        // Menu Lainnya
        return (strpos($current_uri, $keyword) !== false) ? 'active' : '';
    };
    ?>

    <?php if ($isLoggedIn): ?>
        <header class="navbar">
            <div class="navbar-inner">
                <span class="navbar-title">GDSS Hidroponik (<?= ucfirst($role) ?>)</span>

                <nav>
                    <!-- 1. SEMUA ROLE (Admin, Ketua, DM) BISA AKSES INI -->
                    <a href="/Dashboard/index" class="<?= $isActive('/Dashboard') ?>">Dashboard</a>
                    <a href="/Penilaian/form" class="<?= $isActive('/Penilaian') ?>">Input Penilaian</a>

                    <!-- 2. HANYA ADMIN YANG BISA AKSES MASTER DATA & JALANKAN PROSES -->
                    <!-- Ketua TIDAK bisa melihat menu ini -->
                    <?php if ($role === 'admin'): ?>
                        <a href="/MasterData/alternatif" class="<?= $isActive('/MasterData/alternatif') ?>">Alternatif</a>
                        <a href="/MasterData/kriteria" class="<?= $isActive('/MasterData/kriteria') ?>">Kriteria</a>
                        <a href="/MasterData/subkriteria" class="<?= $isActive('/MasterData/subkriteria') ?>">Subkriteria</a>
                        <a href="/MasterData/pengguna" class="<?= $isActive('/MasterData/pengguna') ?>">Pengguna</a>
                    <?php endif; ?>

                    <!-- 3. ADMIN DAN KETUA BISA MELIHAT HASIL AKHIR & PROSES -->
                    <!-- DM Biasa TIDAK bisa melihat menu ini -->
                    <?php if ($role === 'admin' || $role === 'ketua'): ?>
                        <a href="/Proses/index" class="<?= $isActive('/Proses') ?>">Proses Hitung</a>
                        <a href="/Laporan/hasil" class="<?= $isActive('/Laporan/hasil') ?>">Hasil Akhir</a>

                        <!-- Menu Laporan Tambahan -->
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