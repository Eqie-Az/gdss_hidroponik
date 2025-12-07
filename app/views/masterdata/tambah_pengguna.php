<?php $title = $title ?? 'Tambah Pengguna Baru'; ?>

<h2><?= htmlspecialchars($title) ?></h2>

<div class="card mt-2">
    <form action="<?= BASEURL; ?>/masterdata/simpanPengguna" method="post">

        <div class="form-group">
            <label>Nama Lengkap</label>
            <input class="input" type="text" name="nama" required placeholder="Contoh: Budi Santoso">
        </div>

        <div class="form-group">
            <label>Username</label>
            <input class="input" type="text" name="username" required placeholder="Username untuk login">
        </div>

        <div class="form-group">
            <label>Password</label>
            <input class="input" type="password" name="password" required placeholder="Masukkan password">
        </div>

        <div class="form-group">
            <label>Role</label>
            <select class="input" name="peran">
                <option value="farmer">Farmer Hydroponic (DM)</option>
                <option value="ketua">Ketua</option>
                <option value="admin">Admin</option>
            </select>
        </div>

        <button class="btn btn-primary" type="submit">Simpan Data</button>
        <a href="<?= BASEURL; ?>/masterdata/pengguna" class="btn btn-secondary ml-2">Batal</a>
    </form>
</div>