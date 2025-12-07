<?php $title = $title ?? 'Form Penilaian'; ?>

<div class="card">
    <div class="header-actions">
        <h2>Form Penilaian Tanaman Hidroponik (Input Manual)</h2>
    </div>

    <form action="<?= BASEURL; ?>/penilaian/simpan" method="post">

        <div class="table-responsive">
            <table class="table" cellspacing="0">
                <thead>
                    <tr>
                        <th>Alternatif</th>
                        <?php foreach ($kriteria as $k): ?>
                            <th>
                                <?= htmlspecialchars($k['nama_kriteria']) ?>
                            </th>
                        <?php endforeach; ?>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($alternatif)): ?>
                        <?php foreach ($alternatif as $a): ?>
                            <tr>
                                <td>
                                    <strong><?= htmlspecialchars($a['nama_alternatif']) ?></strong><br>
                                    <?php if (!empty($a['gambar'])): ?>
                                        <img src="<?= BASEURL; ?>/assets/img/alternatif/<?= htmlspecialchars($a['gambar']) ?>"
                                            alt="<?= htmlspecialchars($a['nama_alternatif']) ?>" class="img-alternatif">
                                    <?php endif; ?>
                                </td>
                                <?php foreach ($kriteria as $k): ?>
                                    <td>
                                        <input type="number" step="any" class="input" required
                                            name="nilai[<?= $a['id_alternatif'] ?>][<?= $k['id_kriteria'] ?>]"
                                            placeholder="Contoh: 0.41">
                                    </td>
                                <?php endforeach; ?>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="<?= count($kriteria) + 1 ?>">Tidak ada data Alternatif untuk dinilai.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <button class="btn btn-primary mt-2" type="submit">Simpan Penilaian</button>
    </form>
</div>