<?php
$title = $title ?? 'Tambah Alternatif';
?>

<h2><?= htmlspecialchars($title) ?></h2>

<div class="card mt-2">
    <form action="<?= BASEURL ?>/MasterData/simpanAlternatif" method="post" enctype="multipart/form-data">
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
            <label for="gambar">Unggah Gambar Alternatif (JPG/PNG)</label>
            <input type="file" id="gambar" name="gambar" class="input">
            <small class="text-muted">*Maksimal ukuran file: 2MB</small>
        </div>

        <button type="submit" class="btn btn-primary">Simpan</button>
        <a href="<?= BASEURL ?>/MasterData/alternatif" class="btn btn-secondary ml-2">Batal</a>
    </form>
</div>