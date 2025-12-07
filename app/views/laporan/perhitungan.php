<?php $title = $title ?? 'Laporan Perhitungan'; ?>

<div class="card" style="max-width: 1000px; margin: 30px auto;">
    <div class="header-actions">
        <h2>Laporan Perhitungan Borda</h2>
    </div>

    <div class="table-responsive">
        <table class="table" cellspacing="0">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama Juri</th>
                    <th>Alternatif</th>
                    <th>Ranking (Individu)</th>
                    <th>Poin Borda</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($borda)): ?>
                    <?php $no = 1;
                    foreach ($borda as $row): ?>
                        <tr>
                            <td><?= $no++; ?></td>
                            <td><?= htmlspecialchars($row['nama_lengkap']); ?></td>
                            <td><?= htmlspecialchars($row['nama_alternatif']); ?></td>
                            <td><?= $row['ranking']; ?></td>
                            <td><?= number_format($row['poin_borda'], 3); ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="5">Belum ada data perhitungan. Silakan jalankan proses terlebih
                            dahulu.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>