<?php
// app/controllers/ProsesController.php

require_once __DIR__ . '/../models/PenilaianModel.php';
require_once __DIR__ . '/../models/NilaiAhpDmModel.php';
require_once __DIR__ . '/../models/NilaiBordaModel.php';

class ProsesController extends Controller
{
    public function index()
    {
        // REVISI: Hanya Admin dan Ketua yang boleh masuk sini
        $this->requireRole(['admin', 'ketua']);

        $title = 'Proses AHP & BORDA';
        $this->view('proses/index', compact('title'));
    }

    public function jalankan()
    {
        // REVISI: Hanya Admin dan Ketua yang boleh memproses
        $this->requireRole(['admin', 'ketua']);

        $penilaianModel = new PenilaianModel();
        $ahpModel = new NilaiAhpDmModel();
        $bordaModel = new NilaiBordaModel();

        // 1. Hitung nilai AHP (Bobot Kriteria * Bobot Subkriteria)
        $rows = $penilaianModel->getDataUntukAhp();

        $nilai = []; // [id_pengguna][id_alternatif] => skor
        foreach ($rows as $row) {
            $idp = $row['id_pengguna'];
            $ida = $row['id_alternatif'];
            $bk = $row['bobot_kriteria'];
            $bs = $row['bobot_subkriteria'];

            if (!isset($nilai[$idp][$ida])) {
                $nilai[$idp][$ida] = 0;
            }
            $nilai[$idp][$ida] += ($bk * $bs);
        }

        // Simpan Nilai AHP ke Database
        foreach ($nilai as $id_pengguna => $alts) {
            foreach ($alts as $id_alternatif => $nilai_ahp) {
                $ahpModel->simpanNilai($id_pengguna, $id_alternatif, $nilai_ahp);
            }
        }

        // 2. Hitung Borda (Berdasarkan Ranking per User)
        $dataAhp = $ahpModel->getSemuaNilaiTergrup();

        foreach ($dataAhp as $id_pengguna => $alts) {
            // Urutkan nilai AHP dari terbesar ke terkecil (Ranking)
            arsort($alts);

            $n = count($alts); // Jumlah alternatif
            $rank = 1;

            foreach ($alts as $id_alternatif => $score) {
                // Rumus Borda: (Jumlah Alternatif - Peringkat + 1)
                $poin = $n - $rank + 1;

                $bordaModel->simpanBorda($id_pengguna, $id_alternatif, $rank, $poin);
                $rank++;
            }
        }

        // 3. Hasil Konsensus (Total Poin Borda Semua User)
        $bordaModel->hitungHasilKonsensus();

        // Redirect ke halaman hasil setelah selesai
        $this->redirect('Laporan/hasil');
    }
}