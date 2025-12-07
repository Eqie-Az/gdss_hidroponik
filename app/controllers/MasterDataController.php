<?php
// app/controllers/MasterDataController.php

require_once __DIR__ . '/../models/AlternatifModel.php';
require_once __DIR__ . '/../models/KriteriaModel.php';
require_once __DIR__ . '/../models/PenggunaModel.php';


class MasterDataController extends Controller
{
    // ==========================================================
    // BAGIAN KRITERIA
    // ==========================================================

    public function kriteria()
    {
        // HANYA ADMIN
        $this->requireRole(['admin']);

        $model = new KriteriaModel();
        $dataKriteria = $model->all();
        $title = 'Master Kriteria';

        $this->view('masterdata/kriteria', compact('title', 'dataKriteria'));
    }

    public function simpanKriteria()
    {
        $this->requireRole(['admin']);

        $kode = $_POST['kode_kriteria'] ?? '';
        $nama = $_POST['nama_kriteria'] ?? '';
        $desk = $_POST['deskripsi_kriteria'] ?? '';
        $bobot = (float) ($_POST['bobot_kriteria'] ?? 0);

        $model = new KriteriaModel();
        $model->create($kode, $nama, $desk, $bobot);

        $this->redirect('MasterData/kriteria');
    }

    public function editKriteria($id)
    {
        $this->requireRole(['admin']);

        $model = new KriteriaModel();
        $data = $model->findById($id);
        $title = 'Edit Kriteria';

        $this->view('masterdata/edit_kriteria', compact('title', 'data'));
    }

    public function updateKriteria()
    {
        $this->requireRole(['admin']);

        $id = $_POST['id_kriteria'];
        $kode = $_POST['kode_kriteria'];
        $nama = $_POST['nama_kriteria'];
        $desk = $_POST['deskripsi_kriteria'];
        $bobot = $_POST['bobot_kriteria'];

        $model = new KriteriaModel();
        $model->update($id, $kode, $nama, $desk, $bobot);

        $this->redirect('MasterData/kriteria');
    }

    public function hapusKriteria($id)
    {
        $this->requireRole(['admin']);

        $model = new KriteriaModel();
        $model->delete($id);

        $this->redirect('MasterData/kriteria');
    }

    // ==========================================================
    // BAGIAN ALTERNATIF
    // ==========================================================

    public function alternatif()
    {
        $this->requireRole(['admin']);

        $model = new AlternatifModel();
        $dataAlternatif = $model->all();
        $title = 'Master Alternatif';

        $this->view('masterdata/alternatif', compact('title', 'dataAlternatif'));
    }

    public function tambahAlternatif()
    {
        $this->requireRole(['admin']);

        $title = 'Tambah Alternatif';
        $this->view('masterdata/TambahAlternatif', compact('title'));
    }

    public function simpanAlternatif()
    {
        $this->requireRole(['admin']);

        $kode = $_POST['kode_alternatif'] ?? '';
        $nama = $_POST['nama_alternatif'] ?? '';
        $gambar = $_POST['gambar'] ?? null;

        $model = new AlternatifModel();
        $model->create($kode, $nama, $gambar);

        $this->setFlash('success', 'Data alternatif berhasil ditambahkan!');
        $this->redirect('MasterData/alternatif');
    }

    public function editAlternatif($id)
    {
        $this->requireRole(['admin']);

        $model = new AlternatifModel();
        $data = $model->findById($id);
        $title = 'Edit Alternatif';

        $this->view('masterdata/edit', compact('title', 'data'));
    }

    public function updateAlternatif()
    {
        $this->requireRole(['admin']);

        $id = $_POST['id_alternatif'];
        $kode = $_POST['kode_alternatif'];
        $nama = $_POST['nama_alternatif'];
        $gambar = $_POST['gambar'];

        $model = new AlternatifModel();
        $model->update($id, $kode, $nama, $gambar);

        $this->setFlash('success', 'Data alternatif berhasil diubah!');
        $this->redirect('MasterData/alternatif');
    }

    public function hapusAlternatif($id)
    {
        $this->requireRole(['admin']);

        $model = new AlternatifModel();
        $model->delete($id);

        $this->setFlash('success', 'Data alternatif berhasil dihapus!');
        $this->redirect('MasterData/alternatif');
    }

    // ==========================================================
    // BAGIAN PENGGUNA
    // ==========================================================

    public function pengguna()
    {
        $this->requireRole(['admin']);

        $model = new PenggunaModel();
        $dataPengguna = $model->all();
        $title = 'Master Pengguna';

        $this->view('masterdata/pengguna', compact('title', 'dataPengguna'));
    }

    public function editPengguna($id)
    {
        $this->requireRole(['admin']);

        $model = new PenggunaModel();
        $data = $model->findById($id);
        $title = 'Edit Pengguna';

        $this->view('masterdata/edit_pengguna', compact('title', 'data'));
    }

    public function updatePengguna()
    {
        $this->requireRole(['admin']);

        $id = $_POST['id_pengguna'];
        $nama = $_POST['nama'];
        $user = $_POST['username'];
        $pass = $_POST['password']; // Bisa kosong jika tidak ganti password
        $peran = $_POST['peran'];

        $model = new PenggunaModel();
        $model->update($id, $nama, $user, $peran, $pass);

        $this->redirect('MasterData/pengguna');
    }

    public function hapusPengguna($id)
    {
        $this->requireRole(['admin']);

        $model = new PenggunaModel();
        $model->delete($id);

        $this->redirect('MasterData/pengguna');
    }
}