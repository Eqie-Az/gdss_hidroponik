<?php $title = $title ?? 'Perbandingan Kriteria'; ?>

<div class="card">
    <div class="header-actions">
        <h2>Kuesioner Perbandingan Kriteria</h2>
    </div>

    <div class="alert-info alert-inline" style="margin-bottom: 20px;">
        <strong>Panduan Pengisian:</strong><br>
        1. Isi nilai pada kolom input.<br>
        2. Gunakan <strong>TITIK (.)</strong> sebagai pemisah desimal (Contoh: 0.3333).<br>
        3. Masukkan angka presisi tinggi (banyak angka di belakang koma) agar hasil perhitungan valid (CR < 0.1). </div>

            <form action="<?= BASEURL; ?>/penilaian/prosesKriteria" method="post">

                <?php foreach ($kriteria as $i => $k1): ?>
                    <div class="step-box" style="margin-bottom: 20px;">
                        <h3 style="border-bottom: 2px solid #eee; padding-bottom: 10px; margin-bottom: 15px;">
                            Perbandingan terhadap: <?= htmlspecialchars($k1['nama_kriteria']) ?>
                        </h3>

                        <div class="table-responsive">
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
                                            <td style="vertical-align: middle;">
                                                <strong><?= htmlspecialchars($k1['nama_kriteria']) ?></strong>
                                            </td>

                                            <td style="text-align: center; vertical-align: middle;">
                                                <?php if ($i == $j): ?>
                                                    <input type="text" class="input text-center diagonal-cell" value="1" readonly
                                                        style="width: 100%; background: #eee; font-weight: bold; border: 1px solid #ddd;">

                                                <?php elseif ($i < $j): ?>
                                                    <?php
                                                    $val = isset($existing[$id_row][$id_col]) ? $existing[$id_row][$id_col] : '';
                                                    ?>

                                                    <input type="text" inputmode="decimal" class="input text-center input-primary"
                                                        name="nilai[<?= $id_row ?>][<?= $id_col ?>]"
                                                        id="input_<?= $id_row ?>_<?= $id_col ?>"
                                                        data-target="input_<?= $id_col ?>_<?= $id_row ?>" value="<?= $val ?>"
                                                        placeholder="Contoh: 0.3333333333" required autocomplete="off"
                                                        style="width: 100%; border: 2px solid #2e7d32; padding: 8px; font-weight: bold;">

                                                <?php else: ?>
                                                    <input type="text" class="input text-center readonly-cell"
                                                        id="input_<?= $id_row ?>_<?= $id_col ?>" placeholder="Otomatis" readonly
                                                        style="width: 100%; background: #f9f9f9; color: #666; border: 1px solid #ddd;">
                                                <?php endif; ?>
                                            </td>

                                            <td style="vertical-align: middle;">
                                                <?= htmlspecialchars($k2['nama_kriteria']) ?>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
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

            // Fungsi hitung invers otomatis
            function calculateInverse(inputElement) {
                // Ganti koma jadi titik agar JS bisa hitung (jika user pakai koma)
                let valStr = inputElement.value.replace(',', '.');
                // Hapus karakter selain angka dan titik
                valStr = valStr.replace(/[^0-9.]/g, '');

                const val = parseFloat(valStr);
                const targetId = inputElement.getAttribute('data-target');
                const targetInput = document.getElementById(targetId);

                if (targetInput) {
                    if (val && val !== 0) {
                        let inverse = 1 / val;
                        // Tampilkan invers dengan presisi tinggi
                        targetInput.value = inverse.toFixed(10);
                    } else {
                        targetInput.value = '';
                    }
                }
            }

            inputs.forEach(input => {
                // Event saat mengetik
                input.addEventListener('input', function () {
                    calculateInverse(this);
                });

                // Event saat kehilangan fokus (Validasi sederhana)
                input.addEventListener('blur', function () {
                    let val = this.value.replace(',', '.');
                    if (val && !isNaN(val)) {
                        // Biarkan apa adanya, jangan dibulatkan
                    }
                });

                // Trigger saat load (untuk mode edit / pre-fill)
                if (input.value) {
                    calculateInverse(input);
                }
            });
        });
    </script>