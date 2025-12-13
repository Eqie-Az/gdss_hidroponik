<?php
// --- UPDATE KEAMANAN: CEK AKSES DI SINI ---
// Pastikan session terbaca
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Cek apakah user memiliki role 'admin'
// Jika session kosong ATAU role bukan admin, tendang ke dashboard
if (empty($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    echo "<script>
        alert('AKSES DITOLAK: Anda tidak memiliki izin mengakses halaman Master Alternatif.');
        window.location.href = '" . BASEURL . "/Dashboard'; 
    </script>";
    exit(); // Stop script agar tabel di bawah tidak dimuat
}
// --- BATAS AKHIR UPDATE KEAMANAN ---

$title = $title ?? 'Master Alternatif';
?>

<?php if ($msg = $this->getFlash('success')): ?>
    <div class="alert-success"><?= htmlspecialchars($msg) ?></div>
<?php endif; ?>

<div class="card">
    <div class="header-actions">
        <h2>Master Alternatif</h2>
        <a href="<?= BASEURL ?>/MasterData/tambahAlternatif" class="btn btn-primary btn-sm">+ Tambah Alternatif</a>
    </div>

    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Gambar</th>
                    <th>Kode</th>
                    <th>Nama Tanaman</th>
                    <th>Lokasi Ladang</th>
                    <th>Tgl Tanam</th>
                    <th style="width: 150px;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($dataAlternatif)): ?>
                    <tr>
                        <td colspan="7" class="text-center">Belum ada data alternatif.</td>
                    </tr>
                <?php else: ?>
                    <?php $no = 1;
                    foreach ($dataAlternatif as $a): ?>
                        <tr>
                            <td><?= $no++ ?></td>
                            <td>
                                <?php if (!empty($a['gambar'])): ?>
                                    <img src="<?= BASEURL ?>/assets/img/alternatif/<?= htmlspecialchars($a['gambar']) ?>"
                                        class="img-alternatif"
                                        style="width: 60px; height: 60px; object-fit: cover; border-radius: 4px; border: 1px solid #ddd;">
                                <?php else: ?>
                                    <span class="text-muted">-</span>
                                <?php endif; ?>
                            </td>
                            <td><?= htmlspecialchars($a['kode_alternatif']) ?></td>
                            <td><strong><?= htmlspecialchars($a['nama_alternatif']) ?></strong></td>

                            <td><?= htmlspecialchars($a['ladang'] ?? '-') ?></td>

                            <td>
                                <?= !empty($a['tgl_tanam']) && $a['tgl_tanam'] != '0000-00-00'
                                    ? date('d M Y', strtotime($a['tgl_tanam']))
                                    : '-' ?>
                            </td>

                            <td>
                                <a href="<?= BASEURL ?>/MasterData/editAlternatif/<?= $a['id_alternatif'] ?>"
                                    class="btn btn-warning btn-sm">Edit</a>
                                <a href="<?= BASEURL ?>/MasterData/hapusAlternatif/<?= $a['id_alternatif'] ?>"
                                    class="btn btn-danger-action btn-sm"
                                    onclick="return confirm('Hapus alternatif ini?');">Hapus</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>