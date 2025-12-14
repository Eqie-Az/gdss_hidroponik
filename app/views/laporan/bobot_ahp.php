<?php $title = $title ?? 'Perhitungan Bobot'; ?>

<div class="card">
    <div class="header-actions">
        <h2>Detail Perhitungan Bobot AHP</h2>
        <a href="<?= BASEURL; ?>/laporan/hasil" class="btn btn-secondary btn-sm">Kembali ke Hasil</a>
    </div>
    <p>Halaman ini menampilkan proses "Black Box" perhitungan AHP: Matriks Asli &rarr; Normalisasi &rarr; Eigen Vector
        &rarr; Uji Konsistensi.</p>
</div>

<?php if (empty($laporan)): ?>
    <div class="card">
        <div class="alert-warning">Belum ada data penilaian dari DM.</div>
    </div>
<?php else: ?>

    <?php foreach ($laporan as $data): ?>
        <div class="card card-calculation">
            <h3>
                DM: <?= htmlspecialchars($data['nama']) ?>
                <span class="badge badge-default"><?= ucfirst($data['role']) ?></span>
            </h3>
            <hr class="divider">

            <h4 class="section-title">1. Matriks Perbandingan Berpasangan</h4>
            <div class="table-responsive">
                <table class="table table-bordered table-sm table-matrix">
                    <thead class="bg-header-green">
                        <tr>
                            <th>Kriteria</th>
                            <?php foreach ($kriteria as $k): ?>
                                <th><?= $k['kode_kriteria'] ?></th>
                            <?php endforeach; ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($kriteria as $rowK): ?>
                            <tr>
                                <td class="col-kriteria"><?= $rowK['nama_kriteria'] ?> (<?= $rowK['kode_kriteria'] ?>)</td>
                                <?php foreach ($kriteria as $colK):
                                    $val = $data['fullMatrix'][$rowK['id_kriteria']][$colK['id_kriteria']];
                                    ?>
                                    <td><?= round($val, 3) ?></td>
                                <?php endforeach; ?>
                            </tr>
                        <?php endforeach; ?>

                        <tr class="bg-row-total">
                            <td class="text-left">TOTAL KOLOM</td>
                            <?php foreach ($kriteria as $colK): ?>
                                <td><?= round($data['colTotal'][$colK['id_kriteria']], 3) ?></td>
                            <?php endforeach; ?>
                        </tr>
                    </tbody>
                </table>
            </div>

            <h4 class="section-title">2. Normalisasi & Eigen Vector (Bobot)</h4>
            <div class="table-responsive">
                <table class="table table-bordered table-sm table-matrix">
                    <thead class="bg-header-blue">
                        <tr>
                            <th>Kriteria</th>
                            <?php foreach ($kriteria as $k): ?>
                                <th><?= $k['kode_kriteria'] ?></th>
                            <?php endforeach; ?>
                            <th class="col-header-weight">Bobot (Priority Vector)</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($kriteria as $rowK): ?>
                            <tr>
                                <td class="col-kriteria-code"><?= $rowK['kode_kriteria'] ?></td>
                                <?php foreach ($kriteria as $colK):
                                    $val = $data['normMatrix'][$rowK['id_kriteria']][$colK['id_kriteria']];
                                    ?>
                                    <td><?= number_format($val, 4) ?></td>
                                <?php endforeach; ?>

                                <td class="col-weight-value">
                                    <?= number_format($data['bobot'][$rowK['id_kriteria']], 4) ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <h4 class="section-title">3. Uji Konsistensi (CR)</h4>
            <div class="consistency-box">
                <table class="table table-bordered">
                    <tr>
                        <td width="60%">Lambda Max (&lambda; max)</td>
                        <td class="font-bold"><?= number_format($data['lambdaMax'], 4) ?></td>
                    </tr>
                    <tr>
                        <td>Consistency Index (CI)</td>
                        <td><?= number_format($data['CI'], 4) ?></td>
                    </tr>
                    <tr>
                        <td>Consistency Ratio (CR)</td>
                        <td class="<?= ($data['CR'] <= 0.1) ? 'text-success' : 'text-danger' ?> font-bold">
                            <?= number_format($data['CR'], 4) ?>
                            <span class="status-badge">(<?= $data['status'] ?>)</span>
                        </td>
                    </tr>
                </table>
            </div>

        </div>
    <?php endforeach; ?>

<?php endif; ?>