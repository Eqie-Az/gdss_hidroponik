<?php $title = $title ?? 'Form Penilaian'; ?>

<h2>Form Penilaian Tanaman Hidroponik (Input Manual)</h2>

<form action="/Penilaian/simpan" method="post">
    <table class="table">
        <thead>
            <tr>
                <th>Alternatif</th>
                <?php foreach ($kriteria as $k): ?>
                    <th>
                        <?= htmlspecialchars($k['nama_kriteria']) ?> <br>
                        <small>(Bobot: <?= $k['bobot_kriteria'] ?>)</small>
                    </th>
                <?php endforeach; ?>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($alternatif as $a): ?>
                <tr>
                    <td>
                        <strong><?= htmlspecialchars($a['nama_alternatif']) ?></strong><br>
                        <?php if (!empty($a['gambar'])): ?>
                            <img src="/assets/img/alternatif/<?= htmlspecialchars($a['gambar']) ?>"
                                alt="<?= htmlspecialchars($a['nama_alternatif']) ?>" class="img-alternatif">
                        <?php endif; ?>
                    </td>
                    <?php foreach ($kriteria as $k): ?>
                        <td>
                            <input type="number" step="any" class="input" required
                                name="nilai[<?= $a['id_alternatif'] ?>][<?= $k['id_kriteria'] ?>]" placeholder="Contoh: 0.41">
                        </td>
                    <?php endforeach; ?>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <button class="btn btn-primary" type="submit">Simpan Penilaian</button>
</form>