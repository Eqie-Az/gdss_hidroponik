<?php $title = $title ?? 'Edit Pengguna'; ?>

<h2><?= htmlspecialchars($title) ?></h2>

<div class="card mt-2">
    <h3>Form Edit Pengguna</h3>

    <form action="<?= BASEURL; ?>/masterdata/updatePengguna" method="post">
        <input type="hidden" name="id_pengguna" value="<?= $data['id_pengguna']; ?>">

        <div class="form-group">
            <label>Nama Lengkap</label>
            <input class="input" type="text" name="nama" value="<?= htmlspecialchars($data['nama_lengkap']); ?>"
                required>
        </div>

        <div class="form-group">
            <label>Username</label>
            <input class="input" type="text" name="username" value="<?= htmlspecialchars($data['username']); ?>"
                required>
        </div>

        <div class="form-group">
            <label>Password (Opsional)</label>
            <input class="input" type="password" name="password" placeholder="Isi hanya jika ingin mengubah password">
            <small class="text-muted">*Kosongkan jika tidak ingin mengganti password</small>
        </div>

        <div class="form-group">
            <label>Role</label>
            <select class="input" name="peran">
                <option value="farmer" <?= ($data['role'] == 'farmer') ? 'selected' : ''; ?>>Farmer Hydroponic (DM)
                </option>
                <option value="ketua" <?= ($data['role'] == 'ketua') ? 'selected' : ''; ?>>Ketua</option>
                <option value="admin" <?= ($data['role'] == 'admin') ? 'selected' : ''; ?>>Admin</option>
            </select>
        </div>

        <button class="btn btn-primary" type="submit">Simpan Perubahan</button>

        <a href="<?= BASEURL; ?>/masterdata/pengguna" class="btn btn-secondary ml-2">Batal</a>
    </form>
</div>