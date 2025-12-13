<?php $title = $title ?? 'Dashboard Penilaian AHP'; ?>

<div class="card">
    <h2>Progress Penilaian AHP</h2>
    <p>Silakan selesaikan tahapan penilaian berikut secara berurutan.</p>

    <div class="step-box">
        <h3>Langkah 1: Perbandingan Kriteria</h3>
        <p>Tentukan tingkat kepentingan antar kriteria (Misal: Rasa vs Harga).</p>

        <?php if ($statusKriteria): ?>
            <div class="alert-success alert-inline">
                &#10004; Sudah Dinilai
            </div>
            <div style="margin-top: 5px;">
                <a href="<?= BASEURL; ?>/penilaian/formKriteria" class="btn btn-primary btn-sm">Edit Penilaian</a>

                <a href="<?= BASEURL; ?>/penilaian/resetKriteria" class="btn btn-danger-action btn-sm"
                    onclick="return confirm('Yakin ingin menghapus seluruh penilaian kriteria? Data akan hilang.');">
                    Reset
                </a>
            </div>
        <?php else: ?>
            <div class="alert-info alert-inline">
                Belum Dinilai
            </div>
            <div style="margin-top: 5px;">
                <a href="<?= BASEURL; ?>/penilaian/formKriteria" class="btn btn-primary">Mulai Penilaian</a>
            </div>
        <?php endif; ?>
    </div>

    <div class="step-box">
        <h3>Langkah 2: Perbandingan Alternatif</h3>
        <p>Bandingkan tanaman satu dengan lainnya berdasarkan masing-masing kriteria.</p>

        <?php if (!$statusKriteria): ?>
            <div class="alert-error">Harap selesaikan Langkah 1 terlebih dahulu.</div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Kriteria</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $no = 1;
                        foreach ($kriteria as $k): ?>
                            <tr>
                                <td><?= $no++; ?></td>
                                <td><?= htmlspecialchars($k['nama_kriteria']) ?></td>
                                <td>
                                    <?php if (!empty($statusAlt[$k['id_kriteria']])): ?>
                                        <span class="text-success">&#10004; Selesai</span>
                                    <?php else: ?>
                                        <span class="text-danger">Belum</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <a href="<?= BASEURL; ?>/penilaian/formAlternatif/<?= $k['id_kriteria'] ?>"
                                        class="btn btn-primary btn-sm">
                                        <?= !empty($statusAlt[$k['id_kriteria']]) ? 'Edit Nilai' : 'Isi Nilai' ?>
                                    </a>

                                    <?php if (!empty($statusAlt[$k['id_kriteria']])): ?>
                                        <a href="<?= BASEURL; ?>/penilaian/resetAlternatif/<?= $k['id_kriteria'] ?>"
                                            class="btn btn-danger-action btn-sm"
                                            onclick="return confirm('Hapus penilaian alternatif untuk kriteria ini?');">
                                            Reset
                                        </a>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>

    <div class="mt-2">
        <?php
        // Cek kelengkapan
        $allDone = $statusKriteria;
        if ($allDone) {
            foreach ($kriteria as $k) {
                if (empty($statusAlt[$k['id_kriteria']])) {
                    $allDone = false;
                    break;
                }
            }
        }
        ?>

        <?php if ($allDone): ?>
            <div class="alert-info" style="margin-bottom: 10px;">
                Semua data lengkap. Klik tombol di bawah untuk memproses hasil akhir.
            </div>
            <a href="<?= BASEURL; ?>/penilaian/simpanFinal" class="btn btn-primary"
                onclick="return confirm('Apakah Anda yakin data sudah benar? Hasil akan disimpan/diupdate.')">
                Simpan Final & Selesai
            </a>
        <?php else: ?>
            <button class="btn btn-secondary" disabled>Simpan Final (Lengkapi Data Dulu)</button>
        <?php endif; ?>
    </div>
</div>