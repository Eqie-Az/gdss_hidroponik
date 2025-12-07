<?php $title = $title ?? 'Master Alternatif'; ?>

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
                            <?php else: ?>
                                -
                            <?php endif; ?>
                        </td>
                        <td>
                            <a href="<?= BASEURL ?>/MasterData/editAlternatif/<?= $a['id_alternatif'] ?>"
                                class="btn btn-warning btn-sm gap-5">
                                Edit
                            </a>
                            <a href="<?= BASEURL ?>/MasterData/hapusAlternatif/<?= $a['id_alternatif'] ?>"
                                class="btn btn-danger-action btn-sm" onclick="return confirm('Hapus alternatif ini?')">
                                Hapus
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>