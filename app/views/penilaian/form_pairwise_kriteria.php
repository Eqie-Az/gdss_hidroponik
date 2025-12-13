<?php $title = $title ?? 'Perbandingan Kriteria'; ?>

<div class="card">
    <div class="header-actions">
        <h2>Kuesioner Perbandingan Kriteria</h2>
    </div>

    <div class="alert-info alert-inline" style="margin-bottom: 20px;">
        <strong>Panduan Pengisian:</strong><br>
        1. Isi nilai pada kolom input.<br>
        2. Jika <strong>Kriteria Kiri</strong> lebih penting, isi angka bulat (1-9).<br>
        3. Jika <strong>Kriteria Kanan</strong> lebih penting, isi desimal (contoh: 0.5 untuk 1/2, 0.33 untuk 1/3).
    </div>

    <form action="<?= BASEURL; ?>/penilaian/prosesKriteria" method="post">

        <?php foreach ($kriteria as $i => $k1): ?>
            <div class="step-box" style="margin-bottom: 20px;">
                <h3 style="border-bottom: 2px solid #eee; padding-bottom: 10px; margin-bottom: 15px;">
                    Perbandingan terhadap: <?= htmlspecialchars($k1['nama_kriteria']) ?>
                </h3>

                <table class="table">
                    <thead>
                        <tr>
                            <th style="width: 35%;">Kriteria Kiri</th>
                            <th style="width: 30%; text-align: center;">Nilai (Input)</th>
                            <th style="width: 35%;">Kriteria Kanan</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($kriteria as $j => $k2):
                            $id_row = $k1['id_kriteria'];
                            $id_col = $k2['id_kriteria'];
                            ?>
                            <tr>
                                <td>
                                    <strong><?= htmlspecialchars($k1['nama_kriteria']) ?></strong>
                                </td>

                                <td style="text-align: center;">
                                    <?php if ($i == $j): ?>
                                        <input type="number" class="input text-center diagonal-cell" value="1" readonly
                                            style="width: 100px; background: #eee; font-weight: bold;">

                                    <?php elseif ($i < $j): ?>
                                        <?php
                                        // Ambil data lama jika ada (Pre-fill)
                                        $val = isset($existing[$id_row][$id_col]) ? $existing[$id_row][$id_col] : '';
                                        ?>
                                        <input type="number" step="0.0001" min="0.1" max="9" class="input text-center input-primary"
                                            name="nilai[<?= $id_row ?>][<?= $id_col ?>]" id="input_<?= $id_row ?>_<?= $id_col ?>"
                                            data-target="input_<?= $id_col ?>_<?= $id_row ?>" value="<?= $val ?>"
                                            placeholder="Isi Nilai" required style="width: 100px; border: 2px solid #2e7d32;">

                                    <?php else: ?>
                                        <input type="number" step="0.0001" class="input text-center readonly-cell"
                                            id="input_<?= $id_row ?>_<?= $id_col ?>" placeholder="Otomatis" readonly
                                            style="width: 100px; background: #f9f9f9; color: #666;">
                                    <?php endif; ?>
                                </td>

                                <td>
                                    <?= htmlspecialchars($k2['nama_kriteria']) ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endforeach; ?>

        <div class="form-actions" style="margin-top: 30px; text-align: center;">
            <button type="submit" class="btn btn-primary" style="padding: 12px 30px; font-size: 16px;">
                Simpan & Cek Konsistensi
            </button>
            <a href="<?= BASEURL; ?>/penilaian" class="btn btn-secondary ml-2">Batal</a>
        </div>
    </form>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        const inputs = document.querySelectorAll('.input-primary');

        // Fungsi hitung invers
        function calculateInverse(inputElement) {
            const val = parseFloat(inputElement.value);
            const targetId = inputElement.getAttribute('data-target');
            const targetInput = document.getElementById(targetId);

            if (targetInput) {
                if (val && val !== 0) {
                    let inverse = 1 / val;
                    targetInput.value = inverse.toFixed(4);
                } else {
                    targetInput.value = '';
                }
            }
        }

        inputs.forEach(input => {
            // 1. Event saat user mengetik
            input.addEventListener('input', function () {
                calculateInverse(this);
            });

            // 2. Trigger saat halaman dimuat (untuk data Pre-fill / Edit Mode)
            if (input.value) {
                calculateInverse(input);
            }
        });
    });
</script>