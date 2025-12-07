<?php $title = $title ?? 'Tambah Kriteria'; ?>

<h2><?= htmlspecialchars($title) ?></h2>

<div class="card mt-2">
    <h3>Tambah Kriteria Baru</h3>

    <form action="<?= BASEURL; ?>/MasterData/simpankriteria" method="post">
        <div class="form-group">
            <label>Kode (misal K1)</label>
            <input class="input" type="text" name="kode_kriteria" required>
        </div>
        <div class="form-group">
            <label>Nama Kriteria</label>
            <input class="input" type="text" name="nama_kriteria" required>
        </div>
        <div class="form-group">
            <label>Bobot (hasil AHP, cth: 0.25)</label>
            <input class="input" type="text" name="bobot_kriteria" placeholder="0.123456" required>
        </div>
        <button class="btn btn-primary" type="submit">Simpan Kriteria</button>

        <a href="<?= BASEURL; ?>/masterdata/kriteria" class="btn btn-secondary ml-2">Batal</a>
    </form>
</div>