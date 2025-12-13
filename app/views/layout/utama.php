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

    // Logika deteksi URL aktif untuk styling navbar
    $current_uri = $_SERVER['REQUEST_URI'];
    $isAuthPage = (stripos($current_uri, 'auth') !== false) ||
        (stripos($current_uri, 'login') !== false) ||
        (stripos($current_uri, 'register') !== false) ||
        ($current_uri == str_replace('/index.php', '', $_SERVER['SCRIPT_NAME']) . '/') ||
        ($current_uri === '/');

    // Helper function active class
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
                            class="<?= $isActive('/masterdata/alternatif') ?>">Alternatif (Master)</a>

                        <a href="<?= BASEURL; ?>/laporan/detail" class="<?= $isActive('/laporan/detail') ?>">Laporan Lengkap</a>
                    <?php endif; ?>

                    <?php if ($role === 'farmer'): ?>
                        <a href="<?= BASEURL; ?>/petani" class="<?= $isActive('/petani') ?>">Data Tanaman Saya</a>

                        <a href="<?= BASEURL; ?>/penilaian"
                            class="<?= $isActive('/penilaian') && !$isActive('/penilaian/detail') ? 'active' : '' ?>">Input
                            Penilaian</a>
                        <a href="<?= BASEURL; ?>/penilaian/detail" class="<?= $isActive('/penilaian/detail') ?>">Detail
                            Penilaian Saya</a>
                    <?php endif; ?>

                    <?php if ($role === 'ketua'): ?>
                        <a href="<?= BASEURL; ?>/penilaian"
                            class="<?= $isActive('/penilaian') && !$isActive('/penilaian/detail') ? 'active' : '' ?>">Input
                            Penilaian</a>
                        <a href="<?= BASEURL; ?>/penilaian/detail" class="<?= $isActive('/penilaian/detail') ?>">Detail
                            Penilaian Saya</a>

                        <a href="<?= BASEURL; ?>/laporan/detail" class="<?= $isActive('/laporan/detail') ?>">Laporan Lengkap</a>

                        <a href="<?= BASEURL; ?>/proses" class="<?= $isActive('/proses') ?>">Proses Hitung</a>
                    <?php endif; ?>

                    <a href="<?= BASEURL; ?>/laporan/hasil" class="<?= $isActive('/laporan/hasil') ?>">Hasil Ranking</a>

                    <a href="<?= BASEURL; ?>/auth/logout" onclick="return confirm('Yakin ingin keluar?');"
                        style="color: #ffcdd2;">Logout</a>
                </nav>
            </div>
        </header>
    <?php endif; ?>

    <main class="container">
        <?php
        // Flash Message System
        if (isset($_SESSION['flash']) && is_array($_SESSION['flash'])):
            ?>
            <?php foreach ($_SESSION['flash'] as $key => $message): ?>
                <?php
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
            <?php unset($_SESSION['flash']); ?>
        <?php endif; ?>

        <?= $content ?>
    </main>

    <script src="<?= BASEURL; ?>/assets/js/script.js"></script>
</body>

</html>