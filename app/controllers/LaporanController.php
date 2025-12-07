<?php
// app/controllers/LaporanController.php

require_once __DIR__ . '/../models/HasilKonsensusModel.php';
require_once __DIR__ . '/../models/PenilaianModel.php';
require_once __DIR__ . '/../models/NilaiAhpDmModel.php';
require_once __DIR__ . '/../models/NilaiBordaModel.php';

class LaporanController extends Controller
{
    /**
     * Default method ketika akses: /laporan
     * Arahkan saja ke halaman hasil
     */
    public function index()
    {
        $this->hasil();
    }

    // Halaman Output Hasil Akhir (Ranking)
    // KETUA & ADMIN BISA LIHAT
    public function hasil()
    {
        $this->requireRole(['admin', 'ketua']);

        $model = new HasilKonsensusModel();
        $hasil = $model->getHasilDenganAlternatif();

        $title = 'Hasil Konsensus Akhir';
        $this->view('laporan/hasil', compact('title', 'hasil'));
    }

    /**
     * Tombol "Hitung Ulang" akan mengarah ke method ini
     * URL: /laporan/hitung_ulang
     */
    public function hitung_ulang()
    {
        $this->requireRole(['admin', 'ketua']);

        $hasilModel = new HasilKonsensusModel();
        $penilaianModel = new PenilaianModel();

        // 1. Ambil data penilaian terbaru
        $dataPenilaian = $penilaianModel->getAllPenilaianLengkap();

        // 2. Kalau TIDAK ada penilaian → hapus semua hasil lama
        if (empty($dataPenilaian)) {
            $hasilModel->hapusSemuaHasil();
            $this->setFlash('error', 'Tidak ada data penilaian untuk dihitung ulang.');

            header('Location: ' . BASEURL . '/laporan/hasil');
            exit;
        }

        // 3. Kalau ADA penilaian:
        //    Reset hasil lama dan jalankan perhitungan AHP-Borda

        // ** (Pastikan Anda sudah mengaktifkan ini di ProsesController) **
        // $this->model('ProsesController')->jalankan(); 

        // Untuk saat ini, kita hanya reset dulu hasil lama.
        $hasilModel->hapusSemuaHasil();

        // 4. Redirect ke halaman hasil
        $this->setFlash('success', 'Hasil lama dihapus. Silakan jalankan proses hitung di menu "Proses Hitung".');
        header('Location: ' . BASEURL . '/laporan/hasil');
        exit;
    }

    // Halaman Detail Inputan Semua DM
    // KETUA & ADMIN BISA LIHAT
    public function detail()
    {
        $this->requireRole(['admin', 'ketua']);

        $model = new PenilaianModel();
        $rows = $model->getAllPenilaianLengkap();

        // Grouping data untuk view
        $data = [];
        $kriterias = [];

        foreach ($rows as $r) {
            // PERBAIKAN: 
            // 1. Ganti 'nama_pengguna' menjadi 'nama_lengkap' (sesuai Model)
            // 2. Ganti 'nama_subkriteria' menjadi 'nilai_input' (karena sistem tanpa subkriteria)
            $data[$r['nama_lengkap']][$r['nama_alternatif']][$r['nama_kriteria']] = $r['nilai_input'];

            $kriterias[$r['nama_kriteria']] = 1;
        }

        $title = 'Detail Penilaian DM';
        $this->view('laporan/detail', compact('title', 'data', 'kriterias'));
    }

    // Halaman Perhitungan (Tabel AHP & Tabel Borda per User)
    // KETUA & ADMIN BISA LIHAT
    public function perhitungan()
    {
        $this->requireRole(['admin', 'ketua']);

        $ahpModel = new NilaiAhpDmModel();
        $bordaModel = new NilaiBordaModel();

        $dataAhp = $ahpModel->getLaporanAhp();
        $dataBorda = $bordaModel->getLaporanBorda();

        $title = 'Proses Perhitungan';
        $this->view('laporan/perhitungan', compact('title', 'dataAhp', 'dataBorda'));
    }
}