<?php $title = $title ?? 'Detail Penilaian Saya'; ?>

<div class="card">
    <div class="header-actions">
        <h2>Detail & Riwayat Input Penilaian</h2>
        <a href="<?= BASEURL; ?>/penilaian" class="btn btn-secondary btn-sm">Kembali ke Dashboard</a>
    </div>

    <div class="step-box section-gap">
        <h3>1. Data Perbandingan Kriteria</h3>

        <?php if (empty($nilai_kriteria)): ?>

            <div class="alert-warning" style="margin-bottom: 15px;">
                Anda belum menginputkan data, silahkan inputkan datanya terlebih dahulu.
            </div>
            <a href="<?= BASEURL; ?>/penilaian/formKriteria" class="btn btn-primary btn-sm">
                Input Data Sekarang
            </a>

        <?php else: ?>

            <div class="table-responsive">
                <table class="table table-bordered table-sm">
                    <thead>
                        <tr class="table-header-gray">
                            <th>Kriteria 1</th>
                            <th class="text-center">Nilai</th>
                            <th>Kriteria 2</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        foreach ($kriteria as $i => $k1):
                            foreach ($kriteria as $j => $k2):
                                if ($i < $j):
                                    $id1 = $k1['id_kriteria'];
                                    $id2 = $k2['id_kriteria'];
                                    // Ambil nilai, jika kosong strip
                                    $val = $nilai_kriteria[$id1][$id2] ?? '-';
                                    ?>
                                    <tr>
                                        <td><?= htmlspecialchars($k1['nama_kriteria']) ?></td>
                                        <td class="text-center"><strong><?= $val ?></strong></td>
                                        <td><?= htmlspecialchars($k2['nama_kriteria']) ?></td>
                                    </tr>
                                <?php
                                endif;
                            endforeach;
                        endforeach;
                        ?>
                    </tbody>
                </table>
            </div>

            <div class="action-buttons">
                <a href="<?= BASEURL; ?>/penilaian/formKriteria" class="btn btn-warning btn-sm">
                    &#9998; Ubah Data (Edit)
                </a>
                <a href="<?= BASEURL; ?>/penilaian/resetKriteria" class="btn btn-danger-action btn-sm"
                    onclick="return confirm('Yakin ingin MENGHAPUS seluruh data kriteria?');">
                    &#128465; Hapus Data
                </a>
            </div>

        <?php endif; ?>
    </div>

    <div class="step-box section-gap">
        <h3>2. Data Perbandingan Alternatif</h3>

        <?php foreach ($kriteria as $k):
            $idk = $k['id_kriteria'];
            // Cek apakah ada data untuk kriteria ini
            $adaData = isset($nilai_alternatif[$idk]) && !empty($nilai_alternatif[$idk]);
            ?>
            <div class="criteria-wrapper">
                <h4 class="criteria-title">
                    Kriteria: <?= htmlspecialchars($k['nama_kriteria']) ?>
                </h4>

                <?php if (!$adaData): ?>

                    <div class="alert-warning" style="padding: 10px; font-size: 0.9em; margin-bottom: 10px;">
                        Anda belum menginputkan data untuk kriteria ini, silahkan inputkan datanya terlebih dahulu.
                    </div>
                    <a href="<?= BASEURL; ?>/penilaian/formAlternatif/<?= $idk ?>" class="btn btn-primary btn-sm">
                        Input Data
                    </a>

                <?php else: ?>

                    <div class="table-scroll-container">
                        <table class="table table-bordered table-sm">
                            <thead>
                                <tr class="table-header-gray">
                                    <th>Alternatif A</th>
                                    <th class="text-center">Nilai</th>
                                    <th>Alternatif B</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $mat = $nilai_alternatif[$idk];
                                $n = count($alternatif);
                                for ($i = 0; $i < $n; $i++):
                                    for ($j = $i + 1; $j < $n; $j++):
                                        $a1 = $alternatif[$i];
                                        $a2 = $alternatif[$j];
                                        $id_a1 = $a1['id_alternatif'];
                                        $id_a2 = $a2['id_alternatif'];
                                        $val = $mat[$id_a1][$id_a2] ?? '-';
                                        ?>
                                        <tr>
                                            <td><?= htmlspecialchars($a1['nama_alternatif']) ?></td>
                                            <td class="text-center"><strong><?= $val ?></strong></td>
                                            <td><?= htmlspecialchars($a2['nama_alternatif']) ?></td>
                                        </tr>
                                    <?php
                                    endfor;
                                endfor;
                                ?>
                            </tbody>
                        </table>
                    </div>

                    <div class="action-buttons">
                        <a href="<?= BASEURL; ?>/penilaian/formAlternatif/<?= $idk ?>" class="btn btn-warning btn-sm">
                            &#9998; Ubah Data
                        </a>
                        <a href="<?= BASEURL; ?>/penilaian/resetAlternatif/<?= $idk ?>" class="btn btn-danger-action btn-sm"
                            onclick="return confirm('Yakin ingin menghapus data alternatif untuk kriteria ini?');">
                            &#128465; Hapus Data
                        </a>
                    </div>

                <?php endif; ?>
            </div>
        <?php endforeach; ?>
    </div>
</div>