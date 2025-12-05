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

            header('Location: ' . BASEURL . '/laporan/hasil');
            exit;
        }

        // 3. Kalau ADA penilaian:
        //    Untuk saat ini, kita hanya reset dulu hasil lama,
        //    supaya tidak menampilkan ranking yang sudah tidak relevan.
        //    Nanti logika hitung ulang AHP/Borda bisa ditambahkan di sini.
        $hasilModel->hapusSemuaHasil();

        // TODO: Implementasi hitung ulang AHP + Borda berdasarkan $dataPenilaian
        // Contoh (nanti):
        // $ahpModel   = new NilaiAhpDmModel();
        // $bordaModel = new NilaiBordaModel();
        // $ahpModel->prosesUlangAhp($dataPenilaian);
        // $bordaModel->prosesUlangBorda($dataPenilaian);
        // $hasilModel->generateHasilAkhir();

        // 4. Redirect ke halaman hasil
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
            $data[$r['nama_pengguna']][$r['nama_alternatif']][$r['nama_kriteria']] = $r['nama_subkriteria'];
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
