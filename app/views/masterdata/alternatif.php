<?php $title = $title ?? 'Master Alternatif'; ?>

<h2>Master Alternatif</h2>

<?php if ($msg = $this->getFlash('success')): ?>
    <div class="alert-success"><?= $msg ?></div>
<?php endif; ?>

<a href="<?= BASEURL ?>/MasterData/tambahAlternatif" class="btn btn-primary mb-3">
    Tambah Alternatif
</a>

<div class="card">
    <h3>Daftar Alternatif</h3>
    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Kode</th>
                    <th>Nama</th>
                    <th>Gambar</th>
                    <th style="width: 150px;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($dataAlternatif as $a): ?>
                    <tr>
                        <td><?= $a['id_alternatif'] ?></td>
                        <td><?= htmlspecialchars($a['kode_alternatif']) ?></td>
                        <td><?= htmlspecialchars($a['nama_alternatif']) ?></td>
                        <td>
                            <?php if (!empty($a['gambar'])): ?>
                                <img src="<?= BASEURL ?>/assets/img/alternatif/<?= htmlspecialchars($a['gambar']) ?>"
                                    class="img-alternatif">
                                <small><?= htmlspecialchars($a['gambar']) ?></small>
                            <?php else: ?>
                                -
                            <?php endif; ?>
                        </td>
                        <td>
                            <!-- TOMBOL EDIT -->
                            <a href="<?= BASEURL ?>/MasterData/editAlternatif/<?= $a['id_alternatif'] ?>" class="btn"
                                style="background: #f39c12; color: #fff; padding: 5px 10px; font-size: 12px; margin-right: 5px;">
                                Edit
                            </a>
                            <!-- TOMBOL HAPUS -->
                            <a href="<?= BASEURL ?>/MasterData/hapusAlternatif/<?= $a['id_alternatif'] ?>" class="btn"
                                style="background: #e74c3c; color: #fff; padding: 5px 10px; font-size: 12px;"
                                onclick="return confirm('Hapus alternatif ini?')">
                                Hapus
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>