<?php $title = $title ?? 'Master Kriteria'; ?>

<h2>Master Kriteria</h2>

<div class="card">
    <h3>Tambah Kriteria</h3>
    <form action="/MasterData/simpanKriteria" method="post">
        <div class="form-group">
            <label>Kode (misal K1)</label>
            <input class="input" type="text" name="kode_kriteria" required>
        </div>
        <div class="form-group">
            <label>Nama Kriteria</label>
            <input class="input" type="text" name="nama_kriteria" required>
        </div>
        <div class="form-group">
            <label>Deskripsi</label>
            <textarea class="input" name="deskripsi_kriteria"></textarea>
        </div>
        <div class="form-group">
            <label>Bobot (hasil AHP)</label>
            <input class="input" type="text" name="bobot_kriteria" placeholder="0.123456" required>
        </div>
        <button class="btn btn-primary" type="submit">Simpan</button>
    </form>
</div>

<div class="card">
    <h3>Daftar Kriteria</h3>
    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Kode</th>
                    <th>Nama</th>
                    <th>Bobot</th>
                    <th style="width: 150px;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($dataKriteria as $k): ?>
                    <tr>
                        <td><?= $k['id_kriteria'] ?></td>
                        <td><?= htmlspecialchars($k['kode_kriteria']) ?></td>
                        <td><?= htmlspecialchars($k['nama_kriteria']) ?></td>
                        <td><?= $k['bobot_kriteria'] ?></td>
                        <td>
                            <!-- TOMBOL EDIT -->
                            <a href="/MasterData/editKriteria/<?= $k['id_kriteria'] ?>" class="btn"
                                style="background: #f39c12; color: #fff; padding: 5px 10px; font-size: 12px; margin-right: 5px;">
                                Edit
                            </a>
                            <!-- TOMBOL HAPUS -->
                            <a href="/MasterData/hapusKriteria/<?= $k['id_kriteria'] ?>" class="btn"
                                style="background: #e74c3c; color: #fff; padding: 5px 10px; font-size: 12px;"
                                onclick="return confirm('Hapus kriteria ini?')">
                                Hapus
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>