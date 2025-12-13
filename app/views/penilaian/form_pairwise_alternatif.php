<?php $title = $title ?? 'Perbandingan Alternatif'; ?>

<div class="card">
    <div class="header-actions">
        <h2>Perbandingan Alternatif</h2>
        <span class="badge badge-ketua" style="font-size:1em;">
            Kriteria: <?= htmlspecialchars($kriteria['nama_kriteria']) ?>
        </span>
    </div>

    <div class="alert-info alert-inline" style="margin-bottom: 20px;">
        <strong>Panduan:</strong> Bandingkan mana yang lebih unggul untuk kriteria ini. <br>
        (Angka Bulat = Kiri lebih unggul, Desimal = Kanan lebih unggul).
    </div>

    <form action="<?= BASEURL; ?>/penilaian/prosesAlternatif" method="post">
        <input type="hidden" name="id_kriteria" value="<?= $kriteria['id_kriteria'] ?>">

        <div class="table-responsive">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th class="text-right" style="width: 35%;">Alternatif A</th>
                        <th class="text-center" style="width: 30%;">Nilai Perbandingan</th>
                        <th class="text-left" style="width: 35%;">Alternatif B</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $n = count($alternatif);
                    if ($n < 2): ?>
                        <tr>
                            <td colspan="3" class="text-center">Minimal harus ada 2 alternatif data.</td>
                        </tr>
                    <?php else:
                        for ($i = 0; $i < $n; $i++):
                            for ($j = $i + 1; $j < $n; $j++):
                                $a1 = $alternatif[$i];
                                $a2 = $alternatif[$j];

                                // Ambil Value Lama (Pre-fill)
                                $val = isset($existing[$a1['id_alternatif']][$a2['id_alternatif']])
                                    ? $existing[$a1['id_alternatif']][$a2['id_alternatif']]
                                    : '';
                                ?>
                                <tr>
                                    <td class="text-right" style="vertical-align: middle;">
                                        <?php if (!empty($a1['gambar'])): ?>
                                            <img src="<?= BASEURL ?>/assets/img/alternatif/<?= $a1['gambar'] ?>" class="img-tiny mr-1">
                                        <?php endif; ?>
                                        <strong><?= htmlspecialchars($a1['nama_alternatif']) ?></strong>
                                    </td>

                                    <td class="text-center">
                                        <input type="number" step="0.0001" min="0.1" max="9" class="input text-center input-primary"
                                            name="nilai[<?= $a1['id_alternatif'] ?>][<?= $a2['id_alternatif'] ?>]"
                                            value="<?= $val ?>" placeholder="Nilai" required
                                            style="width: 100px; border: 2px solid #2e7d32;">
                                    </td>

                                    <td class="text-left" style="vertical-align: middle;">
                                        <strong><?= htmlspecialchars($a2['nama_alternatif']) ?></strong>
                                        <?php if (!empty($a2['gambar'])): ?>
                                            <img src="<?= BASEURL ?>/assets/img/alternatif/<?= $a2['gambar'] ?>" class="img-tiny ml-1">
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php
                            endfor;
                        endfor;
                    endif;
                    ?>
                </tbody>
            </table>
        </div>

        <?php if ($n >= 2): ?>
            <div class="form-actions">
                <button type="submit" class="btn btn-primary">Simpan & Lanjut</button>
                <a href="<?= BASEURL; ?>/penilaian" class="btn btn-secondary ml-2">Batal</a>
            </div>
        <?php endif; ?>
    </form>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        // Opsional: Highlight baris saat input difokuskan
        const inputs = document.querySelectorAll('.input-primary');
        inputs.forEach(input => {
            input.addEventListener('focus', function () {
                this.closest('tr').style.backgroundColor = '#e8f5e9';
            });
            input.addEventListener('blur', function () {
                this.closest('tr').style.backgroundColor = '';
            });
        });
    });
</script>