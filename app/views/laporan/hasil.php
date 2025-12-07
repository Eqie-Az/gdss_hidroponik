<?php
// app/views/laporan/hasil.php

$title = $title ?? 'Hasil Konsensus Akhir';
?>

<div class="card">
    <div class="header-actions">
        <h2><?= htmlspecialchars($title) ?></h2>

        <?php if (!empty($tombol_hitung)): ?>
            <?= $tombol_hitung; ?>
        <?php endif; ?>
    </div>

    <div class="table-responsive">
        <table class="table" cellspacing="0">
            <thead>
                <tr>
                    <th>Peringkat</th>
                    <th>Alternatif</th>
                    <th>Total Poin Borda</th>
                    <th>Skor Akhir</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($hasil)): ?>
                    <?php foreach ($hasil as $row): ?>
                        <tr>
                            <td>
                                <div class="rank-circle">
                                    <?= htmlspecialchars($row['ranking_final']) ?>
                                </div>
                            </td>
                            <td>
                                <strong><?= htmlspecialchars($row['nama_alternatif']) ?></strong><br>
                                <?php if (!empty($row['gambar'])): ?>
                                    <img src="<?= BASEURL ?>/assets/img/alternatif/<?= htmlspecialchars($row['gambar']) ?>"
                                        class="img-alternatif" alt="<?= htmlspecialchars($row['nama_alternatif']) ?>">
                                <?php endif; ?>
                            </td>
                            <td><?= number_format($row['total_poin'], 4) ?></td>
                            <td><strong><?= number_format($row['total_poin'], 4) ?></strong></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="4" class="text-center">
                            Belum ada data hasil konsensus akhir. Silakan jalankan proses perhitungan.
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>