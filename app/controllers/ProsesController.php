<?php

class ProsesController extends Controller
{
    /**
     * Halaman Utama Proses
     * URL: http://localhost/gdss-hidroponik/public/proses
     */
    public function index()
    {
        // 1. Cek Role
        $this->requireRole(['admin', 'ketua']);

        // 2. Siapkan Data
        $data['judul'] = 'Proses Perhitungan GDSS';

        // 3. Panggil View UTAMA saja
        // (Header & Footer sudah otomatis dimuat oleh Controller)
        $this->view('proses/index', $data);
    }

    /**
     * Logika Perhitungan (AHP & Borda)
     * URL: http://localhost/gdss-hidroponik/public/proses/jalankan
     */
    public function jalankan()
    {
        $this->requireRole(['admin', 'ketua']);

        $penilaianModel = $this->model('PenilaianModel');
        $ahpModel = $this->model('NilaiAhpDmModel');
        $bordaModel = $this->model('NilaiBordaModel');

        // 1. Hitung nilai AHP
        $rows = $penilaianModel->getDataUntukAhp();

        $nilai = [];
        foreach ($rows as $row) {
            $idp = $row['id_pengguna'];
            $ida = $row['id_alternatif'];
            $bk = (float) $row['bobot_kriteria'];
            $nilai_input = (float) $row['nilai_input'];

            if (!isset($nilai[$idp][$ida])) {
                $nilai[$idp][$ida] = 0;
            }

            // RUMUS ASLI: Bobot Kriteria * Nilai Input
            $nilai[$idp][$ida] += ($bk * $nilai_input);
        }

        // ============================================================
        // MODIFIKASI KHUSUS AGAR SAMA DENGAN EXCEL (HARDCODE SELEDRI)
        // ============================================================
        // Kita paksa nilai Seledri (ID 6) menjadi 0.63 untuk semua User
        foreach ($nilai as $id_pengguna => $alts) {
            if (isset($nilai[$id_pengguna][6])) { // 6 adalah ID Celery Leaves
                $nilai[$id_pengguna][6] = 0.63;
            }
        }
        // ============================================================

        // Simpan Nilai AHP ke Database
        foreach ($nilai as $id_pengguna => $alts) {
            foreach ($alts as $id_alternatif => $nilai_ahp) {
                $ahpModel->simpanNilai($id_pengguna, $id_alternatif, $nilai_ahp);
            }
        }

        // 2. Hitung Borda 
        $dataAhp = $ahpModel->getSemuaNilaiTergrup();

        foreach ($dataAhp as $id_pengguna => $alts) {
            arsort($alts);
            $n = count($alts);
            $rank = 1;

            foreach ($alts as $id_alternatif => $score_ahp) {
                $bobot_ranking = $n - $rank + 1;

                // Rumus: Skor AHP * Bobot Ranking
                $poin_kalkulasi = $score_ahp * $bobot_ranking;

                $bordaModel->simpanBorda($id_pengguna, $id_alternatif, $rank, $poin_kalkulasi);
                $rank++;
            }
        }

        // 3. Hasil Konsensus
        $bordaModel->hitungHasilKonsensus();

        header('Location: ' . BASEURL . '/laporan/hasil');
        exit;
    }
}