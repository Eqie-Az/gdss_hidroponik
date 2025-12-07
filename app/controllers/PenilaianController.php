<?php
// app/controllers/PenilaianController.php

require_once __DIR__ . '/../models/AlternatifModel.php';
require_once __DIR__ . '/../models/KriteriaModel.php';
// require_once __DIR__ . '/../models/SubkriteriaModel.php'; // Tidak lagi dibutuhkan untuk form input
require_once __DIR__ . '/../models/PenilaianModel.php';

class PenilaianController extends Controller
{
    public function form()
    {
        $this->requireRole(['dm', 'ketua', 'admin']);
        $this->startSession();

        $altModel = new AlternatifModel();
        $kritModel = new KriteriaModel();

        $alternatif = $altModel->all();
        $kriteria = $kritModel->all();

        // Tidak perlu memuat subkriteria karena input manual

        $title = 'Form Penilaian';
        $this->view('penilaian/form', compact('title', 'alternatif', 'kriteria'));
    }

    public function simpan()
    {
        $this->requireRole(['dm', 'ketua', 'admin']);
        $this->startSession();
        $id_pengguna = $_SESSION['user_id'];

        $nilai = $_POST['nilai'] ?? [];
        $model = new PenilaianModel();

        foreach ($nilai as $id_alternatif => $kArr) {
            foreach ($kArr as $id_kriteria => $nilai_input) {
                // Cek jika nilai tidak kosong (bisa 0, jadi pakai is_numeric)
                if (is_numeric($nilai_input)) {
                    $model->savePenilaian($id_pengguna, $id_alternatif, $id_kriteria, $nilai_input);
                }
            }
        }

        $title = 'Penilaian Tersimpan';
        $this->view('penilaian/sukses', compact('title'));
    }
}