<?php $title = $title ?? 'Edit Tanaman'; ?>

<h2><?= htmlspecialchars($title) ?></h2>

<div class="card mt-2">
    <form action="<?= BASEURL; ?>/petani/update" method="post" enctype="multipart/form-data">
        <input type="hidden" name="id" value="<?= $dt['id_alternatif'] ?>">

        <div class="form-group">
            <label>Kode Tanaman</label>
            <input class="input" type="text" name="kode" value="<?= htmlspecialchars($dt['kode_alternatif']) ?>"
                required>
        </div>

        <div class="form-group">
            <label>Nama Tanaman</label>
            <input class="input" type="text" name="nama" value="<?= htmlspecialchars($dt['nama_alternatif']) ?>"
                required>
        </div>

        <div class="form-group">
            <label>Lokasi Ladang / Green House</label>
            <input class="input" type="text" name="ladang" value="<?= htmlspecialchars($dt['ladang'] ?? '') ?>">
        </div>

        <div class="form-group">
            <label>Tanggal Mulai Tanam</label>
            <input class="input" type="date" name="tgl" value="<?= htmlspecialchars($dt['tgl_tanam'] ?? '') ?>">
        </div>

        <div class="form-group">
            <label>Ganti Foto (Opsional)</label>
            <?php if (!empty($dt['gambar'])): ?>
                <div style="margin-bottom: 10px;">
                    <img src="<?= BASEURL; ?>/assets/img/alternatif/<?= htmlspecialchars($dt['gambar']) ?>"
                        style="width: 100px; border-radius: 4px; border: 1px solid #ddd;">
                </div>
            <?php endif; ?>
            <input type="file" name="gambar" class="input">
            <small class="text-muted">*Biarkan kosong jika tidak ingin mengubah foto</small>
        </div>

        <button class="btn btn-primary" type="submit">Simpan Perubahan</button>
        <a href="<?= BASEURL; ?>/petani" class="btn btn-secondary ml-2">Batal</a>
    </form>
</div>