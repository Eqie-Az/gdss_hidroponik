<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($title) ? htmlspecialchars($title) : 'GDSS Hidroponik' ?></title>
    <link rel="stylesheet" href="<?= BASEURL; ?>/assets/css/style.css?v=<?= time() ?>">
</head>

<body>

    <?php
    if (session_status() === PHP_SESSION_NONE)
        session_start();

    $isLoggedIn = !empty($_SESSION['user_id']);
    $role = $_SESSION['user_role'] ?? '';

    // Logika deteksi URL aktif
    $current_uri = $_SERVER['REQUEST_URI'];
    $isAuthPage = (stripos($current_uri, 'auth') !== false) ||
        (stripos($current_uri, 'login') !== false) ||
        (stripos($current_uri, 'register') !== false) ||
        ($current_uri == str_replace('/index.php', '', $_SERVER['SCRIPT_NAME']) . '/') ||
        ($current_uri === '/');

    $isActive = function ($keyword) use ($current_uri) {
        return (stripos($current_uri, $keyword) !== false) ? 'active' : '';
    };
    ?>

    <?php if ($isLoggedIn && !$isAuthPage): ?>
        <header class="navbar">
            <div class="navbar-inner">
                <span class="navbar-title">GDSS Hidroponik (<?= ucfirst($role) ?>)</span>

                <nav>
                    <a href="<?= BASEURL; ?>/dashboard" class="<?= $isActive('/dashboard') ?>">Dashboard</a>

                    <?php if ($role === 'admin'): ?>
                        <a href="<?= BASEURL; ?>/masterdata/pengguna" class="<?= $isActive('/masterdata/pengguna') ?>">User</a>
                        <a href="<?= BASEURL; ?>/masterdata/kriteria"
                            class="<?= $isActive('/masterdata/kriteria') ?>">Kriteria</a>
                        <a href="<?= BASEURL; ?>/masterdata/alternatif"
                            class="<?= $isActive('/masterdata/alternatif') ?>">Alternatif</a>
                    <?php endif; ?>

                    <a href="<?= BASEURL; ?>/penilaian/form" class="<?= $isActive('/penilaian') ?>">Input Nilai</a>

                    <?php if ($role === 'admin' || $role === 'ketua'): ?>

                        <a href="<?= BASEURL; ?>/proses" class="<?= $isActive('/proses') ?>">Proses Hitung</a>

                        <a href="<?= BASEURL; ?>/laporan/hasil" class="<?= $isActive('/laporan/hasil') ?>">Hasil Akhir</a>
                        <a href="<?= BASEURL; ?>/laporan/detail" class="<?= $isActive('/laporan/detail') ?>">Detail</a>
                        <a href="<?= BASEURL; ?>/laporan/perhitungan"
                            class="<?= $isActive('/laporan/perhitungan') ?>">Perhitungan</a>

                    <?php endif; ?>

                    <a href="<?= BASEURL; ?>/auth/logout" onclick="return confirm('Yakin ingin keluar?');"
                        style="color: #ffcdd2;">Logout</a>
                </nav>
            </div>
        </header>
    <?php endif; ?>

    <main class="container">
        <?php
        // Cek apakah ada flash message dan pastikan itu adalah array
        if (isset($_SESSION['flash']) && is_array($_SESSION['flash'])):
            ?>
            <?php foreach ($_SESSION['flash'] as $key => $message): ?>
                <?php
                // Tentukan kelas alert berdasarkan key (success, error, info)
                $alertClass = 'alert-info';
                if ($key === 'success')
                    $alertClass = 'alert-success';
                if ($key === 'error')
                    $alertClass = 'alert-error';
                ?>
                <div class="<?= $alertClass; ?>">
                    <?= htmlspecialchars($message); ?>
                </div>
            <?php endforeach; ?>
            <?php unset($_SESSION['flash']); // Hapus array flash setelah ditampilkan ?>
        <?php endif; ?>
        <?= $content ?>
    </main>

    <script src="<?= BASEURL; ?>/assets/js/script.js"></script>
</body>

</html>