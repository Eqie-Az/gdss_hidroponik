<?php $title = $title ?? 'Edit Alternatif'; ?>

<h2><?= htmlspecialchars($title) ?></h2>

<div class="card mt-2">
    <h3>Form Edit Alternatif</h3>

    <form action="<?= BASEURL ?>/MasterData/updateAlternatif" method="post" enctype="multipart/form-data">

        <input type="hidden" name="id_alternatif" value="<?= htmlspecialchars($data['id_alternatif']) ?>">

        <div class="form-group">
            <label for="kode_alternatif">Kode Alternatif</label>
            <input type="text" id="kode_alternatif" name="kode_alternatif" class="input"
                value="<?= htmlspecialchars($data['kode_alternatif']) ?>" required>
        </div>

        <div class="form-group">
            <label for="nama_alternatif">Nama Alternatif</label>
            <input type="text" id="nama_alternatif" name="nama_alternatif" class="input"
                value="<?= htmlspecialchars($data['nama_alternatif']) ?>" required>
        </div>

        <div class="form-group">
            <label for="gambar">Ganti Gambar (Opsional)</label>

            <?php if (!empty($data['gambar'])): ?>
                <div class="img-preview-wrapper" style="margin-bottom: 10px;">
                    <small class="text-muted">Gambar saat ini:</small><br>
                    <img src="<?= BASEURL ?>/assets/img/alternatif/<?= htmlspecialchars($data['gambar']) ?>"
                        alt="Gambar Saat Ini" class="img-preview">
                </div>
            <?php endif; ?>

            <input type="file" id="gambar" name="gambar" class="input">
            <small class="text-muted">*Biarkan kosong jika tidak ingin mengubah gambar.</small>
        </div>

        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
        <a href="<?= BASEURL ?>/MasterData/alternatif" class="btn btn-secondary ml-2">Batal</a>
    </form>
</div>