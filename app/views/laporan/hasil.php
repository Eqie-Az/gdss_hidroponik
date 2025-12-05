<?php
// app/views/laporan/hasil.php

// Judul halaman (fallback jika tidak di-set dari controller)
$title = $title ?? 'Hasil Konsensus Akhir';
?>

<h2><?= htmlspecialchars($title) ?></h2>

<div class="card">
    <div class="table-responsive">
        <table class="table">
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
                                <div
                                    style="background:var(--primary); color:#fff; width:30px; height:30px; border-radius:50%; display:flex; align-items:center; justify-content:center; font-weight:bold;">
                                    <?= htmlspecialchars($row['peringkat_akhir']) ?>
                                </div>
                            </td>
                            <td>
                                <strong><?= htmlspecialchars($row['nama_alternatif']) ?></strong><br>
                                <?php if (!empty($row['gambar'])): ?>
                                    <img src="<?= BASEURL ?>/assets/img/alternatif/<?= htmlspecialchars($row['gambar']) ?>"
                                        class="img-alternatif" alt="<?= htmlspecialchars($row['nama_alternatif']) ?>">
                                <?php endif; ?>
                            </td>
                            <td><?= htmlspecialchars($row['total_poin_borda']) ?></td>
                            <td><strong><?= number_format($row['skor_akhir'], 2) ?></strong></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="4" class="text-center">
                            Belum ada data hasil konsensus akhir.
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>