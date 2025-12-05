<?php $title = $title ?? 'Proses Perhitungan'; ?>

<h2>Detail Perhitungan Multi-DM</h2>

<?php if (empty($dataAhp)): ?>

    <div class="card">
        <div class="alert-error" style="background: #fff3cd; color: #856404; border-color: #ffeeba;">
            <h3>⚠️ Data Perhitungan Kosong</h3>
            <p>Halaman ini belum bisa menampilkan data karena salah satu alasan berikut:</p>
            <ul style="margin-left: 20px; margin-top: 10px;">
                <li>Para Decision Maker (DM) <strong>belum menginputkan nilai</strong> penilaian.</li>
                <li>Atau, Admin/Ketua <strong>belum menjalankan "Proses Hitung"</strong>.</li>
            </ul>
            <br>
            <p>Silakan input penilaian terlebih dahulu, lalu buka menu <strong><a href="/Proses/index">Proses
                        Hitung</a></strong> dan klik tombol jalankan.</p>
        </div>
    </div>

<?php else: ?>

    <div class="card">
        <h3>1. Hasil Perhitungan AHP (Skor Individu)</h3>
        <p>Nilai ini didapat dari penjumlahan bobot subkriteria yang dipilih DM.</p>
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>Nama DM</th>
                        <th>Alternatif</th>
                        <th>Nilai AHP</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($dataAhp as $row): ?>
                        <tr>
                            <td><?= htmlspecialchars($row['nama_pengguna']) ?></td>
                            <td><?= htmlspecialchars($row['nama_alternatif']) ?></td>
                            <td><?= number_format($row['nilai_ahp'], 4) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <div class="card">
        <h3>2. Konversi ke Poin Borda (Ranking Individu)</h3>
        <p>Setiap alternatif diranking per DM. Rumus Borda: <code>(Jumlah Alternatif - Ranking + 1)</code>.</p>
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>Nama DM</th>
                        <th>Alternatif</th>
                        <th>Peringkat</th>
                        <th>Poin Borda</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($dataBorda as $row): ?>
                        <tr>
                            <td><?= htmlspecialchars($row['nama_pengguna']) ?></td>
                            <td><?= htmlspecialchars($row['nama_alternatif']) ?></td>
                            <td>Ke-<?= $row['peringkat'] ?></td>
                            <td><strong><?= $row['poin_borda'] ?></strong></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <div class="card">
        <h3>3. Aggregasi Akhir</h3>
        <p>Poin Borda dari ke-3 DM (atau lebih) di atas dijumlahkan untuk mendapatkan <a href="/Laporan/hasil"
                class="link">Hasil Konsensus Akhir</a>.</p>
    </div>

<?php endif; ?>