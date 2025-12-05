<?php $title = $title ?? 'Master Subkriteria'; ?>

<h2>Master Subkriteria</h2>

<div class="card">
    <h3>Tambah Subkriteria</h3>
    <form action="/MasterData/simpanSubkriteria" method="post">
        <div class="form-group">
            <label>Kriteria</label>
            <select class="input" name="id_kriteria">
                <?php foreach ($kriteria as $k): ?>
                    <option value="<?= $k['id_kriteria'] ?>">
                        <?= htmlspecialchars($k['nama_kriteria']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="form-group">
            <label>Kode Subkriteria</label>
            <input class="input" type="text" name="kode_subkriteria">
        </div>
        <div class="form-group">
            <label>Nama Subkriteria</label>
            <input class="input" type="text" name="nama_subkriteria">
        </div>
        <div class="form-group">
            <label>Bobot (hasil AHP)</label>
            <input class="input" type="text" name="bobot_subkriteria" placeholder="0.123456">
        </div>
        <button class="btn btn-primary" type="submit">Simpan</button>
    </form>
</div>

<div class="card">
    <h3>Daftar Subkriteria</h3>
    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Kriteria</th>
                    <th>Kode</th>
                    <th>Nama</th>
                    <th>Bobot</th>
                    <th style="width: 150px;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($dataSubkriter as $s): ?>
                    <tr>
                        <td><?= $s['id_subkriteria'] ?></td>
                        <td><?= htmlspecialchars($s['nama_kriteria']) ?></td>
                        <td><?= htmlspecialchars($s['kode_subkriteria']) ?></td>
                        <td><?= htmlspecialchars($s['nama_subkriteria']) ?></td>
                        <td><?= $s['bobot_subkriteria'] ?></td>
                        <td>
                            <!-- TOMBOL EDIT -->
                            <a href="/MasterData/editSubkriteria/<?= $s['id_subkriteria'] ?>" class="btn"
                                style="background: #f39c12; color: #fff; padding: 5px 10px; font-size: 12px; margin-right: 5px;">
                                Edit
                            </a>
                            <!-- TOMBOL HAPUS -->
                            <a href="/MasterData/hapusSubkriteria/<?= $s['id_subkriteria'] ?>" class="btn"
                                style="background: #e74c3c; color: #fff; padding: 5px 10px; font-size: 12px;"
                                onclick="return confirm('Hapus subkriteria ini?')">
                                Hapus
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>