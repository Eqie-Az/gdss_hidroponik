<?php $title = $title ?? 'Master Pengguna'; ?>

<h2>Master Pengguna</h2>

<div class="card">
    <p>Untuk menambah pengguna baru, gunakan menu <strong>Registrasi</strong> di URL: <code>/Auth/register</code> atau
        buat tombol tambah di sini jika diperlukan.</p>
</div>

<div class="card">
    <h3>Daftar Pengguna</h3>
    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nama</th>
                    <th>Username</th>
                    <th>Peran</th>
                    <th style="width: 150px;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($dataPengguna as $u): ?>
                    <tr>
                        <td><?= $u['id_pengguna'] ?></td>
                        <td><?= htmlspecialchars($u['nama_pengguna']) ?></td>
                        <td><?= htmlspecialchars($u['nama_pengguna_login']) ?></td>
                        <td>
                            <span
                                style="background: <?= $u['peran'] == 'admin' ? '#2c3e50' : ($u['peran'] == 'ketua' ? '#27ae60' : '#7f8c8d') ?>; color: #fff; padding: 2px 6px; border-radius: 4px; font-size: 12px;">
                                <?= htmlspecialchars($u['peran']) ?>
                            </span>
                        </td>
                        <td>
                            <!-- TOMBOL EDIT -->
                            <a href="/MasterData/editPengguna/<?= $u['id_pengguna'] ?>" class="btn"
                                style="background: #f39c12; color: #fff; padding: 5px 10px; font-size: 12px; margin-right: 5px;">
                                Edit
                            </a>
                            <!-- TOMBOL HAPUS -->
                            <a href="/MasterData/hapusPengguna/<?= $u['id_pengguna'] ?>" class="btn"
                                style="background: #e74c3c; color: #fff; padding: 5px 10px; font-size: 12px;"
                                onclick="return confirm('Hapus pengguna ini?')">
                                Hapus
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>