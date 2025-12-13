<?php $title = $title ?? 'Dashboard Penilaian'; ?>

<div class="card">
    <h2>Progress Penilaian</h2>
    <p>Silakan selesaikan tahapan penilaian berikut secara berurutan.</p>

    <div class="step-box" style="margin-bottom: 30px; border-left: 5px solid #2980b9;">
        <h3>Langkah 1: Perbandingan Antar Kriteria</h3>
        <p>Tentukan tingkat kepentingan antar kriteria (Misal: Daun vs Batang).</p>

        <?php if ($statusKriteria): ?>
            <div class="alert-success alert-inline">
                &#10004; Sudah Dinilai
            </div>
            <div style="margin-top: 10px;">
                <a href="<?= BASEURL; ?>/penilaian/formKriteria" class="btn btn-warning btn-sm">Edit Penilaian</a>
                <a href="<?= BASEURL; ?>/penilaian/resetKriteria" class="btn btn-danger-action btn-sm"
                    onclick="return confirm('Yakin ingin mereset/menghapus semua penilaian kriteria?');">Reset</a>
            </div>
        <?php else: ?>
            <div class="alert-info alert-inline">
                Belum Dinilai
            </div>
            <div style="margin-top: 10px;">
                <a href="<?= BASEURL; ?>/penilaian/formKriteria" class="btn btn-primary">Mulai Penilaian Kriteria</a>
            </div>
        <?php endif; ?>
    </div>

    <div class="step-box" style="border-left: 5px solid #2e7d32;">
        <h3>Langkah 2: Penilaian Profil Tanaman</h3>
        <p>Isi nilai atribut untuk masing-masing tanaman.</p>

        <?php if (!$statusKriteria): ?>

            <div class="alert-error"
                style="background-color: #ffebee; color: #c0392b; padding: 15px; border-radius: 5px; border: 1px solid #ffcdd2;">
                <strong>Akses Ditolak:</strong><br>
                Anda harus menyelesaikan <strong>Langkah 1 (Perbandingan Kriteria)</strong> terlebih dahulu sebelum bisa
                mengisi nilai tanaman.
            </div>

        <?php else: ?>

            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr style="background-color: #f1f8e9;">
                            <th style="width: 50px; text-align: center;">No</th>
                            <th>Nama Tanaman</th>
                            <th>Status Input</th>
                            <th style="width: 150px; text-align: center;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $no = 1;
                        foreach ($alternatif as $a):
                            $isComplete = !empty($statusAlt[$a['id_alternatif']]);
                            ?>
                            <tr>
                                <td style="text-align: center; vertical-align: middle;"><?= $no++; ?></td>
                                <td style="vertical-align: middle;">
                                    <div style="display: flex; align-items: center;">
                                        <?php if (!empty($a['gambar'])): ?>
                                            <img src="<?= BASEURL ?>/assets/img/alternatif/<?= $a['gambar'] ?>"
                                                style="width: 40px; height: 40px; object-fit: cover; border-radius: 4px; margin-right: 10px;">
                                        <?php endif; ?>
                                        <strong><?= htmlspecialchars($a['nama_alternatif']) ?></strong>
                                    </div>
                                </td>
                                <td style="vertical-align: middle;">
                                    <?php if ($isComplete): ?>
                                        <span class="badge badge-ketua" style="background-color: #27ae60;">&#10004; Selesai</span>
                                    <?php else: ?>
                                        <span class="badge badge-admin" style="background-color: #95a5a6;">Belum Lengkap</span>
                                    <?php endif; ?>
                                </td>
                                <td style="text-align: center; vertical-align: middle;">
                                    <a href="<?= BASEURL; ?>/penilaian/formAlternatif/<?= $a['id_alternatif'] ?>"
                                        class="btn <?= $isComplete ? 'btn-warning' : 'btn-primary' ?> btn-sm">
                                        <?= $isComplete ? 'Ubah Nilai' : 'Input Data' ?>
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

        <?php endif; ?>
    </div>

    <div class="mt-2" style="margin-top: 30px; text-align: center;">
        <?php
        // Cek apakah semua langkah selesai
        $allDone = $statusKriteria;
        if ($allDone) {
            foreach ($alternatif as $a) {
                if (empty($statusAlt[$a['id_alternatif']])) {
                    $allDone = false;
                    break;
                }
            }
        }
        ?>

        <?php if ($allDone): ?>
            <div class="alert-success" style="margin-bottom: 15px; display: inline-block;">
                Semua data telah lengkap! Siap untuk diproses.
            </div>
            <br>
            <a href="<?= BASEURL; ?>/penilaian/simpanFinal" class="btn btn-primary"
                onclick="return confirm('Apakah Anda yakin data sudah benar? Hasil akan disimpan untuk perhitungan akhir.');"
                style="padding: 12px 30px; font-size: 16px;">
                Simpan Final & Selesai
            </a>
        <?php else: ?>
            <button class="btn btn-secondary" disabled style="opacity: 0.6; cursor: not-allowed; padding: 12px 30px;">
                Simpan Final (Lengkapi Data Dulu)
            </button>
        <?php endif; ?>
    </div>
</div>