<?php
// app/controllers/PenilaianController.php

require_once __DIR__ . '/../models/AlternatifModel.php';
require_once __DIR__ . '/../models/KriteriaModel.php';
require_once __DIR__ . '/../models/SubkriteriaModel.php';
require_once __DIR__ . '/../models/PenilaianModel.php';

class PenilaianController extends Controller
{
    public function form()
    {
        // REVISI: Role 'dm' DIPERBOLEHKAN masuk sini
        $this->requireRole(['dm', 'ketua', 'admin']);
        $this->startSession();

        $altModel = new AlternatifModel();
        $kritModel = new KriteriaModel();
        $subModel = new SubkriteriaModel();

        $alternatif = $altModel->all();
        $kriteria = $kritModel->all();

        $subkriteria = [];
        foreach ($kriteria as $k) {
            $subkriteria[$k['id_kriteria']] = $subModel->findByKriteria($k['id_kriteria']);
        }

        $title = 'Form Penilaian';
        $this->view('penilaian/form', compact('title', 'alternatif', 'kriteria', 'subkriteria'));
    }

    public function simpan()
    {
        // REVISI: Role 'dm' DIPERBOLEHKAN menyimpan data
        $this->requireRole(['dm', 'ketua', 'admin']);
        $this->startSession();
        $id_pengguna = $_SESSION['user_id'];

        $nilai = $_POST['nilai'] ?? [];
        $model = new PenilaianModel();

        foreach ($nilai as $id_alternatif => $kArr) {
            foreach ($kArr as $id_kriteria => $id_subkriteria) {
                if (!empty($id_subkriteria)) {
                    $model->savePenilaian($id_pengguna, $id_alternatif, $id_kriteria, $id_subkriteria);
                }
            }
        }

        $title = 'Penilaian Tersimpan';
        $this->view('penilaian/sukses', compact('title'));
    }
}