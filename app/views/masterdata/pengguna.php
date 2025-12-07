<?php $title = $title ?? 'Master Pengguna'; ?>

<div class="card">
    <div class="header-actions">
        <h2>Data Pengguna</h2>
        <a href="<?= BASEURL; ?>/masterdata/tambahPengguna" class="btn btn-primary btn-sm">+ Tambah Pengguna</a>
    </div>

    <div class="table-responsive">
        <table class="table" cellspacing="0">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama Lengkap</th>
                    <th>Username</th>
                    <th>Role</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($dataPengguna)): ?>
                    <?php $no = 1;
                    foreach ($dataPengguna as $row): ?>
                        <tr>
                            <td><?= $no++; ?></td>
                            <td><?= htmlspecialchars($row['nama_lengkap']); ?></td>
                            <td><?= htmlspecialchars($row['username']); ?></td>
                            <td>
                                <?php
                                $roleClass = 'badge-default';
                                if ($row['role'] === 'admin')
                                    $roleClass = 'badge-admin';
                                elseif ($row['role'] === 'ketua')
                                    $roleClass = 'badge-ketua';
                                elseif ($row['role'] === 'farmer')
                                    $roleClass = 'badge-farmer';
                                ?>
                                <span class="badge <?= $roleClass; ?>">
                                    <?= htmlspecialchars(ucfirst($row['role'])); ?>
                                </span>
                            </td>
                            <td>
                                <a href="<?= BASEURL; ?>/masterdata/editPengguna/<?= $row['id_pengguna']; ?>"
                                    class="btn btn-warning btn-sm">Edit</a>
                                <a href="<?= BASEURL; ?>/masterdata/hapusPengguna/<?= $row['id_pengguna']; ?>"
                                    class="btn btn-danger-action btn-sm"
                                    onclick="return confirm('Yakin hapus pengguna ini?');">Hapus</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="5">Tidak ada data pengguna.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>