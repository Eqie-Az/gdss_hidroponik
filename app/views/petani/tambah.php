<?php $title = $title ?? 'Tambah Tanaman'; ?>

<h2><?= htmlspecialchars($title) ?></h2>

<div class="card mt-2">
    <form action="<?= BASEURL; ?>/petani/simpan" method="post" enctype="multipart/form-data">

        <div class="form-group">
            <label>Kode Tanaman (Misal: A1, A2)</label>
            <input class="input" type="text" name="kode" required placeholder="A1">
        </div>

        <div class="form-group">
            <label>Nama Tanaman</label>
            <input class="input" type="text" name="nama" required placeholder="Contoh: Selada Romaine">
        </div>

        <div class="form-group">
            <label>Lokasi Ladang / Green House</label>
            <input class="input" type="text" name="ladang" placeholder="Contoh: GH 01 - Rak A">
        </div>

        <div class="form-group">
            <label>Tanggal Mulai Tanam</label>
            <input class="input" type="date" name="tgl">
        </div>

        <div class="form-group">
            <label>Foto Tanaman (Opsional)</label>
            <input type="file" name="gambar" class="input">
            <small class="text-muted">*Format JPG/PNG, Maks 2MB</small>
        </div>

        <button class="btn btn-primary" type="submit">Simpan Data</button>
        <a href="<?= BASEURL; ?>/petani" class="btn btn-secondary ml-2">Batal</a>
    </form>
</div>