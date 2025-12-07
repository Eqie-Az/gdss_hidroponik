<?php $title = $title ?? 'Edit Kriteria'; ?>

<h2><?= htmlspecialchars($title) ?></h2>

<div class="card mt-2">
    <h3>Edit Data Kriteria</h3>

    <form action="<?= BASEURL; ?>/MasterData/updateKriteria" method="post">
        <input type="hidden" name="id_kriteria" value="<?= $data['id_kriteria']; ?>">

        <div class="form-group">
            <label>Kode Kriteria</label>
            <input class="input" type="text" name="kode_kriteria"
                value="<?= htmlspecialchars($data['kode_kriteria']); ?>" required>
        </div>

        <div class="form-group">
            <label>Nama Kriteria</label>
            <input class="input" type="text" name="nama_kriteria"
                value="<?= htmlspecialchars($data['nama_kriteria']); ?>" required>
        </div>

        <div class="form-group">
            <label>Bobot (hasil AHP)</label>
            <input class="input" type="text" name="bobot_kriteria"
                value="<?= htmlspecialchars($data['bobot_kriteria']); ?>" required>
        </div>

        <button class="btn btn-primary" type="submit">Simpan Perubahan</button>
        <a href="<?= BASEURL; ?>/masterdata/kriteria" class="btn btn-secondary ml-2">Batal</a>
    </form>
</div>