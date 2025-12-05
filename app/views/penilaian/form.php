<?php $title = $title ?? 'Form Penilaian'; ?>

<h2>Form Penilaian Tanaman Hidroponik</h2>

<form action="/Penilaian/simpan" method="post">
    <table class="table">
        <thead>
        <tr>
            <th>Alternatif</th>
            <?php foreach ($kriteria as $k): ?>
                <th><?= htmlspecialchars($k['nama_kriteria']) ?></th>
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
                             alt="<?= htmlspecialchars($a['nama_alternatif']) ?>"
                             class="img-alternatif">
                    <?php endif; ?>
                </td>
                <?php foreach ($kriteria as $k): ?>
                    <td>
                        <select class="input"
                                name="nilai[<?= $a['id_alternatif'] ?>][<?= $k['id_kriteria'] ?>]">
                            <option value="">--pilih--</option>
                            <?php foreach ($subkriteria[$k['id_kriteria']] as $s): ?>
                                <option value="<?= $s['id_subkriteria'] ?>">
                                    <?= htmlspecialchars($s['nama_subkriteria']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </td>
                <?php endforeach; ?>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>

    <button class="btn btn-primary" type="submit">Simpan Penilaian</button>
</form>
