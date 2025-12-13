<?php $title = $title ?? 'Input Nilai'; ?>

<div class="card">
    <div class="header-actions">
        <h2>Penilaian Tanaman: <span
                class="text-success"><?= htmlspecialchars($targetAlternatif['nama_alternatif']) ?></span></h2>
    </div>

    <?php if (!empty($targetAlternatif['gambar'])): ?>
        <div style="margin-bottom: 20px; text-align: center;">
            <img src="<?= BASEURL ?>/assets/img/alternatif/<?= htmlspecialchars($targetAlternatif['gambar']) ?>"
                style="width: 150px; height: 150px; object-fit: cover; border-radius: 8px; border: 4px solid #fff; box-shadow: 0 4px 10px rgba(0,0,0,0.1);">
            <p class="text-muted" style="margin-top: 10px;">Kode:
                <?= htmlspecialchars($targetAlternatif['kode_alternatif']) ?></p>
        </div>
    <?php endif; ?>

    <div class="alert-info alert-inline" style="margin-bottom: 20px;">
        <strong>Instruksi:</strong><br>
        Masukkan nilai rating untuk tanaman ini berdasarkan data Excel.<br>
        Gunakan <strong>TITIK (.)</strong> untuk desimal (Contoh: 0.41).
    </div>

    <form action="<?= BASEURL; ?>/penilaian/prosesAlternatif" method="post">
        <input type="hidden" name="id_alternatif" value="<?= $targetAlternatif['id_alternatif'] ?>">

        <div class="table-responsive">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th style="width: 50px;">No</th>
                        <th>Kriteria Penilaian</th>
                        <th style="width: 200px;">Nilai Input</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($kriteriaList)): ?>
                        <tr>
                            <td colspan="3" class="text-center">Belum ada data kriteria.</td>
                        </tr>
                    <?php else: ?>
                        <?php $no = 1;
                        foreach ($kriteriaList as $k):
                            $id_k = $k['id_kriteria'];
                            $val = isset($existing[$id_k]) ? $existing[$id_k] : '';
                            ?>
                            <tr>
                                <td class="text-center"><?= $no++; ?></td>
                                <td style="vertical-align: middle;">
                                    <strong><?= htmlspecialchars($k['nama_kriteria']) ?></strong>
                                </td>
                                <td>
                                    <input type="text" inputmode="decimal" class="input text-center input-primary"
                                        name="nilai[<?= $k['id_kriteria'] ?>]" value="<?= $val ?>" placeholder="0" required
                                        autocomplete="off" style="border: 2px solid #2e7d32; font-weight: bold;">
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <div class="form-actions" style="margin-top: 30px; text-align: center;">
            <button type="submit" class="btn btn-primary" style="padding: 12px 40px; font-size: 16px;">
                Simpan Penilaian
            </button>
            <a href="<?= BASEURL; ?>/penilaian" class="btn btn-secondary ml-2">Kembali</a>
        </div>
    </form>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        const inputs = document.querySelectorAll('.input-primary');
        inputs.forEach(input => {
            // Highlight saat fokus
            input.addEventListener('focus', function () {
                this.closest('tr').style.backgroundColor = '#e8f5e9';
            });
            input.addEventListener('blur', function () {
                this.closest('tr').style.backgroundColor = '';
            });

            // Validasi Input: Hanya boleh Angka dan Titik
            input.addEventListener('input', function () {
                // Ganti koma jadi titik
                this.value = this.value.replace(/,/g, '.');
                // Hapus karakter non-angka dan non-titik
                this.value = this.value.replace(/[^0-9.]/g, '');
            });
        });
    });
</script>