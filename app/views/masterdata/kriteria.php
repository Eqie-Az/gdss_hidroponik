<?php $title = $title ?? 'Master Kriteria'; ?>

<div class="card">
    <div class="header-actions">
        <h2>Daftar Kriteria</h2>
        <a href="<?= BASEURL; ?>/MasterData/tambahkriteria" class="btn btn-primary btn-sm">+ Tambah Kriteria</a>
    </div>

    <div class="table-responsive">
        <table class="table" cellspacing="0">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Kode</th>
                    <th>Nama</th>
                    <th>Bobot</th>
                    <th style="width: 150px;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($dataKriteria)): ?>
                    <?php foreach ($dataKriteria as $k): ?>
                        <tr>
                            <td><?= $k['id_kriteria'] ?></td>
                            <td><?= htmlspecialchars($k['kode_kriteria']) ?></td>
                            <td><?= htmlspecialchars($k['nama_kriteria']) ?></td>
                            <td><?= number_format($k['bobot_kriteria'], 4) ?></td>
                            <td>
                                <a href="<?= BASEURL; ?>/MasterData/editKriteria/<?= $k['id_kriteria'] ?>"
                                    class="btn btn-warning btn-sm gap-5">Edit</a>

                                <a href="<?= BASEURL; ?>/MasterData/hapusKriteria/<?= $k['id_kriteria'] ?>"
                                    class="btn btn-danger-action btn-sm"
                                    onclick="return confirm('Hapus kriteria ini? Tindakan ini tidak dapat dibatalkan.');">Hapus</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="5">Tidak ada data kriteria.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>