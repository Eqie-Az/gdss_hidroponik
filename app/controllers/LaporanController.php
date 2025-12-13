<?php

require_once __DIR__ . '/../models/HasilKonsensusModel.php';
require_once __DIR__ . '/../models/PenilaianModel.php';

class LaporanController extends Controller
{
    public function index()
    {
        $this->hasil();
    }

    // Halaman Output Hasil Akhir (Ranking)
    // REVISI: Semua role (termasuk farmer) boleh melihat hasil
    public function hasil()
    {
        $this->requireRole(['admin', 'ketua', 'farmer']);

        $model = new HasilKonsensusModel();
        $hasil = $model->getHasilDenganAlternatif();

        $title = 'Hasil Peringkat Konsensus (Borda)';
        // View tetap menggunakan yang lama
        $this->view('laporan/hasil', compact('title', 'hasil'));
    }

    // REVISI: Method 'perhitungan' DIHAPUS agar Admin tidak bingung/salah akses
    // (Method hitung_ulang juga dihapus jika tidak diperlukan, atau biarkan untuk reset ketua)

    public function hitung_ulang()
    {
        $this->requireRole(['ketua']); // Hanya ketua yang boleh reset
        $hasilModel = new HasilKonsensusModel();
        $hasilModel->hapusSemuaHasil();
        $this->setFlash('success', 'Hasil lama dihapus. Silakan proses hitung ulang.');
        header('Location: ' . BASEURL . '/laporan/hasil');
        exit;
    }

    public function detail()
    {
        $this->requireRole(['admin', 'ketua']);

        $model = new PenilaianModel();
        $rows = $model->getAllPenilaianLengkap();

        $data = [];
        $kriterias = [];

        foreach ($rows as $r) {
            $data[$r['nama_lengkap']][$r['nama_alternatif']][$r['nama_kriteria']] = $r['nilai_input'];
            $kriterias[$r['nama_kriteria']] = 1;
        }

        $title = 'Detail Input Penilaian';
        $this->view('laporan/detail', compact('title', 'data', 'kriterias'));
    }
}