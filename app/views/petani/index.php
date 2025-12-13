<?php $title = $title ?? 'Kelola Data Tanaman Saya'; ?>

<div class="card">
    <div class="header-actions">
        <h2>Data Tanaman Saya</h2>
        <a href="<?= BASEURL; ?>/petani/tambah" class="btn btn-primary btn-sm">+ Tambah Tanaman</a>
    </div>

    <div class="table-responsive">
        <table class="table" cellspacing="0">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Gambar</th>
                    <th>Kode</th>
                    <th>Nama Tanaman</th>
                    <th>Ladang</th>
                    <th>Tgl Tanam</th>
                    <th style="width: 150px;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($tanaman)): ?>
                    <?php $no = 1;
                    foreach ($tanaman as $t): ?>
                        <tr>
                            <td><?= $no++; ?></td>
                            <td>
                                <?php if (!empty($t['gambar'])): ?>
                                    <img src="<?= BASEURL; ?>/assets/img/alternatif/<?= htmlspecialchars($t['gambar']) ?>"
                                        class="img-alternatif"
                                        style="width: 50px; height: 50px; object-fit: cover; border-radius: 4px;">
                                <?php else: ?>
                                    <span class="text-muted">-</span>
                                <?php endif; ?>
                            </td>
                            <td><?= htmlspecialchars($t['kode_alternatif']) ?></td>
                            <td><strong><?= htmlspecialchars($t['nama_alternatif']) ?></strong></td>
                            <td><?= htmlspecialchars($t['ladang'] ?? '-') ?></td>
                            <td>
                                <?php
                                if (!empty($t['tgl_tanam']) && $t['tgl_tanam'] != '0000-00-00') {
                                    echo date('d M Y', strtotime($t['tgl_tanam']));
                                } else {
                                    echo '-';
                                }
                                ?>
                            </td>
                            <td>
                                <a href="<?= BASEURL; ?>/petani/edit/<?= $t['id_alternatif'] ?>"
                                    class="btn btn-warning btn-sm">Edit</a>
                                <a href="<?= BASEURL; ?>/petani/hapus/<?= $t['id_alternatif'] ?>"
                                    class="btn btn-danger-action btn-sm"
                                    onclick="return confirm('Yakin hapus data tanaman ini?');">Hapus</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="7" class="text-center">Belum ada data tanaman. Silakan tambah data baru.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>