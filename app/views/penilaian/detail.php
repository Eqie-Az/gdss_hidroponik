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
                Anda belum menginputkan data kriteria.
            </div>
            <a href="<?= BASEURL; ?>/penilaian/formKriteria" class="btn btn-primary btn-sm">Input Data</a>
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
                <a href="<?= BASEURL; ?>/penilaian/formKriteria" class="btn btn-warning btn-sm">Edit Data</a>
            </div>
        <?php endif; ?>
    </div>

    <div class="step-box section-gap">
        <h3>2. Data Penilaian Profil Tanaman</h3>
        <p>Nilai rating setiap tanaman terhadap seluruh kriteria.</p>

        <?php foreach ($alternatif as $a):
            $id_alt = $a['id_alternatif'];
            // Cek apakah ada data untuk alternatif ini
            // (Data dikirim dari controller dalam format $nilai_alternatif[id_alt][id_kriteria])
            $data_alt = isset($nilai_alternatif[$id_alt]) ? $nilai_alternatif[$id_alt] : [];
            $sudah_ada_isi = !empty($data_alt);
            ?>
            <div class="criteria-wrapper" style="border-left: 5px solid <?= $sudah_ada_isi ? '#2ecc71' : '#e74c3c' ?>;">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 10px;">
                    <h4 class="criteria-title" style="margin: 0; border: none;">
                        <?php if (!empty($a['gambar'])): ?>
                            <img src="<?= BASEURL ?>/assets/img/alternatif/<?= $a['gambar'] ?>"
                                style="width: 30px; height: 30px; object-fit: cover; border-radius: 50%; vertical-align: middle; margin-right: 10px;">
                        <?php endif; ?>
                        <?= htmlspecialchars($a['nama_alternatif']) ?>
                    </h4>

                    <a href="<?= BASEURL; ?>/penilaian/formAlternatif/<?= $id_alt ?>" class="btn btn-primary btn-sm">
                        <?= $sudah_ada_isi ? 'Ubah Nilai' : 'Input Data' ?>
                    </a>
                </div>

                <?php if (!$sudah_ada_isi): ?>
                    <div class="alert-warning" style="padding: 10px; font-size: 0.9em; margin: 0;">
                        Belum ada data nilai untuk tanaman ini.
                    </div>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-bordered table-sm" style="margin-top: 5px;">
                            <thead>
                                <tr class="table-header-gray">
                                    <th>Kriteria</th>
                                    <th class="text-center" style="width: 150px;">Nilai Rating</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($kriteria as $k):
                                    $id_k = $k['id_kriteria'];
                                    $nilai = $data_alt[$id_k] ?? '-';
                                    ?>
                                    <tr>
                                        <td><?= htmlspecialchars($k['nama_kriteria']) ?></td>
                                        <td class="text-center"><strong><?= $nilai ?></strong></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>
        <?php endforeach; ?>
    </div>
</div>