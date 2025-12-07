<?php

class ProsesController extends Controller
{
    /**
     * Method Index (Halaman Utama Proses)
     * Diakses via: http://localhost/gdss-hidroponik/public/proses
     */
    public function index()
    {
        // Cek login/role jika perlu
        $this->requireRole(['admin', 'ketua']);

        $data['judul'] = 'Proses Perhitungan';

        // Pastikan Anda punya file view di: app/views/proses/index.php
        // Isinya biasanya tombol untuk memicu ke method 'jalankan'
        $this->view('templates/header', $data);
        $this->view('proses/index', $data);
        $this->view('templates/footer');
    }

    /**
     * Method Jalankan (Logika Perhitungan)
     * Diakses via: http://localhost/gdss-hidroponik/public/proses/jalankan
     */
    public function jalankan()
    {
        $this->requireRole(['admin', 'ketua']);

        $penilaianModel = $this->model('PenilaianModel'); // Gunakan $this->model() agar konsisten dengan App wrapper
        $ahpModel = $this->model('NilaiAhpDmModel');
        $bordaModel = $this->model('NilaiBordaModel');

        // 1. Hitung nilai AHP (Bobot Kriteria * Nilai Input User)
        $rows = $penilaianModel->getDataUntukAhp();

        $nilai = [];
        foreach ($rows as $row) {
            $idp = $row['id_pengguna'];
            $ida = $row['id_alternatif'];
            $bk = $row['bobot_kriteria'];
            $nilai_input = $row['nilai_input'];

            if (!isset($nilai[$idp][$ida])) {
                $nilai[$idp][$ida] = 0;
            }

            // RUMUS: Bobot Kriteria * Nilai Input
            $nilai[$idp][$ida] += ($bk * $nilai_input);
        }

        // Simpan Nilai AHP ke Database
        // Kosongkan dulu tabel jika perlu reset ulang per hitungan baru (opsional)
        // $ahpModel->truncate(); 

        foreach ($nilai as $id_pengguna => $alts) {
            foreach ($alts as $id_alternatif => $nilai_ahp) {
                $ahpModel->simpanNilai($id_pengguna, $id_alternatif, $nilai_ahp);
            }
        }

        // 2. Hitung Borda 
        $dataAhp = $ahpModel->getSemuaNilaiTergrup();

        foreach ($dataAhp as $id_pengguna => $alts) {
            arsort($alts); // Urutkan dari nilai tertinggi
            $n = count($alts);
            $rank = 1;

            foreach ($alts as $id_alternatif => $score) {
                $poin = $n - $rank + 1;
                $bordaModel->simpanBorda($id_pengguna, $id_alternatif, $rank, $poin);
                $rank++;
            }
        }

        // 3. Hasil Konsensus
        $bordaModel->hitungHasilKonsensus();

        // Redirect ke laporan hasil
        header('Location: ' . BASEURL . '/Laporan/hasil');
        exit;
    }
} 