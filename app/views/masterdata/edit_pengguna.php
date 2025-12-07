<?php $title = $title ?? 'Edit Pengguna'; ?>

<div class="card card-center">
    <h2>Edit Pengguna</h2>

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
                <option value="petani" <?= ($data['role'] == 'petani') ? 'selected' : ''; ?>>Petani (DM)</option>
                <option value="ketua" <?= ($data['role'] == 'ketua') ? 'selected' : ''; ?>>Ketua</option>
                <option value="admin" <?= ($data['role'] == 'admin') ? 'selected' : ''; ?>>Admin</option>
            </select>
        </div>

        <button class="btn btn-primary" type="submit">Simpan Perubahan</button>
        <a href="<?= BASEURL; ?>/masterdata/pengguna" class="ml-2 text-muted">Batal</a>
    </form>
</div>