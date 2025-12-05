<?php $title = $title ?? 'Detail Penilaian'; ?>

<h2>Detail Input Penilaian Decision Maker (DM)</h2>

<div class="card">
    <p>Berikut adalah data mentah kriteria yang dipilih oleh masing-masing DM.</p>
</div>

<?php if (empty($data)): ?>
    <div class="card">
        <p>Belum ada data penilaian.</p>
    </div>
<?php else: ?>

    <?php foreach ($data as $namaUser => $alternatifs): ?>
        <div class="card">
            <h3>DM: <?= htmlspecialchars($namaUser) ?></h3>
            <table class="table">
                <thead>
                    <tr>
                        <th>Alternatif</th>
                        <?php foreach (array_keys($kriterias) as $k): ?>
                            <th><?= htmlspecialchars($k) ?></th>
                        <?php endforeach; ?>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($alternatifs as $namaAlt => $kriteriaVals): ?>
                        <tr>
                            <td><strong><?= htmlspecialchars($namaAlt) ?></strong></td>
                            <?php foreach (array_keys($kriterias) as $k): ?>
                                <td><?= htmlspecialchars($kriteriaVals[$k] ?? '-') ?></td>
                            <?php endforeach; ?>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endforeach; ?>

<?php endif; ?>