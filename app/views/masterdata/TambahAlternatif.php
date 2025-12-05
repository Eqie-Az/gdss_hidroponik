<?php
$title = $title ?? 'Tambah Alternatif';
?>

<h2><?= htmlspecialchars($title) ?></h2>

<div class="center-page">
    <div class="card card-narrow mt-2">
        <h3>Tambah Alternatif</h3>

        <form action="<?= BASEURL ?>/MasterData/simpanAlternatif" method="post">
            <div class="form-group">
                <label for="kode_alternatif">Kode Alternatif</label>
                <input type="text" id="kode_alternatif" name="kode_alternatif" class="input" placeholder="Misal: A1"
                    required>
            </div>

            <div class="form-group">
                <label for="nama_alternatif">Nama Alternatif</label>
                <input type="text" id="nama_alternatif" name="nama_alternatif" class="input"
                    placeholder="Masukkan nama alternatif" required>
            </div>

            <div class="form-group">
                <label for="gambar">Nama File Gambar (opsional)</label>
                <input type="text" id="gambar" name="gambar" class="input" placeholder="misal: bayam.jpg">
            </div>

            <button type="submit" class="btn btn-primary">Simpan</button>
            <a href="<?= BASEURL ?>/MasterData/alternatif" class="btn btn-secondary">Batal</a>
        </form>
    </div>
</div>