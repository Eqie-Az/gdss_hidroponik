<?php

class MasterDataController extends Controller
{
    /**
     * Method Default (Solusi Error: "does not have a method index")
     * Redirect otomatis ke halaman Pengguna saat menu Master Data diklik.
     */
    public function index()
    {
        $this->requireRole(['admin']);
        header('Location: ' . BASEURL . '/masterdata/pengguna');
        exit;
    }

    // ==========================================================
    // 1. BAGIAN PENGGUNA
    // ==========================================================

    public function pengguna()
    {
        $this->requireRole(['admin']);

        $dataPengguna = $this->model('PenggunaModel')->getAllPengguna();
        $data['title'] = 'Master Pengguna';
        $data['dataPengguna'] = $dataPengguna;

        $this->view('masterdata/pengguna', $data);
    }

    public function tambahPengguna()
    {
        $this->requireRole(['admin']);
        $data['title'] = 'Tambah Pengguna Baru';
        $this->view('masterdata/tambah_pengguna', $data);
    }

    public function simpanPengguna()
    {
        $this->requireRole(['admin']);

        if (empty($_POST['nama']) || empty($_POST['username']) || empty($_POST['password'])) {
            $this->setFlash('error', 'Semua kolom wajib diisi!');
            header('Location: ' . BASEURL . '/masterdata/tambahPengguna');
            exit;
        }

        $data = [
            'nama_lengkap' => $_POST['nama'],
            'username' => $_POST['username'],
            'password' => $_POST['password'],
            'role' => $_POST['peran']
        ];

        // Cek duplikat username (bisa ditambahkan try-catch jika perlu)
        if ($this->model('PenggunaModel')->tambahDataPengguna($data) > 0) {
            $this->setFlash('success', 'Pengguna baru berhasil ditambahkan');
            header('Location: ' . BASEURL . '/masterdata/pengguna');
            exit;
        } else {
            $this->setFlash('error', 'Gagal menyimpan. Username mungkin sudah ada.');
            header('Location: ' . BASEURL . '/masterdata/tambahPengguna');
            exit;
        }
    }

    public function editPengguna($id)
    {
        $this->requireRole(['admin']);

        $data['data'] = $this->model('PenggunaModel')->getPenggunaById($id);
        $data['title'] = 'Edit Pengguna';

        $this->view('masterdata/edit_pengguna', $data);
    }

    public function updatePengguna()
    {
        $this->requireRole(['admin']);

        $data = [
            'id_pengguna' => $_POST['id_pengguna'],
            'nama_lengkap' => $_POST['nama'],
            'username' => $_POST['username'],
            'password' => $_POST['password'],
            'role' => $_POST['peran']
        ];

        if ($this->model('PenggunaModel')->ubahDataPengguna($data) > 0) {
            $this->setFlash('success', 'Data pengguna berhasil diubah');
        }

        header('Location: ' . BASEURL . '/masterdata/pengguna');
        exit;
    }

    public function hapusPengguna($id)
    {
        $this->requireRole(['admin']);

        if ($this->model('PenggunaModel')->hapusDataPengguna($id) > 0) {
            $this->setFlash('success', 'Data pengguna berhasil dihapus');
        }

        header('Location: ' . BASEURL . '/masterdata/pengguna');
        exit;
    }

    // ==========================================================
    // 2. BAGIAN KRITERIA
    // ==========================================================

    public function kriteria()
    {
        $this->requireRole(['admin']);

        $dataKriteria = $this->model('KriteriaModel')->getAllKriteria();
        $data['title'] = 'Master Kriteria';
        $data['dataKriteria'] = $dataKriteria;

        $this->view('masterdata/kriteria', $data);
    }

    public function tambahKriteria()
    {
        $this->requireRole(['admin']);
        $data['title'] = 'Tambah Kriteria';
        $this->view('masterdata/tambah_kriteria', $data);
    }

    public function simpanKriteria()
    {
        $this->requireRole(['admin']);

        $data = [
            'kode_kriteria' => $_POST['kode_kriteria'],
            'nama_kriteria' => $_POST['nama_kriteria'],
            'bobot_kriteria' => $_POST['bobot_kriteria']
        ];

        if ($this->model('KriteriaModel')->tambahDataKriteria($data) > 0) {
            $this->setFlash('success', 'Kriteria berhasil ditambahkan');
        }

        header('Location: ' . BASEURL . '/masterdata/kriteria');
        exit;
    }

    public function editKriteria($id)
    {
        $this->requireRole(['admin']);

        $data['data'] = $this->model('KriteriaModel')->getKriteriaById($id);
        $data['title'] = 'Edit Kriteria';

        $this->view('masterdata/edit_kriteria', $data);
    }

    public function updateKriteria()
    {
        $this->requireRole(['admin']);

        $data = [
            'id_kriteria' => $_POST['id_kriteria'],
            'kode_kriteria' => $_POST['kode_kriteria'],
            'nama_kriteria' => $_POST['nama_kriteria'],
            'bobot_kriteria' => $_POST['bobot_kriteria']
        ];

        if ($this->model('KriteriaModel')->ubahDataKriteria($data) > 0) {
            $this->setFlash('success', 'Kriteria berhasil diubah');
        }

        header('Location: ' . BASEURL . '/masterdata/kriteria');
        exit;
    }

    public function hapusKriteria($id)
    {
        $this->requireRole(['admin']);
        $this->model('KriteriaModel')->hapusDataKriteria($id);
        header('Location: ' . BASEURL . '/masterdata/kriteria');
        exit;
    }

    // ==========================================================
    // 3. BAGIAN ALTERNATIF
    // ==========================================================

    public function alternatif()
    {
        $this->requireRole(['admin']);

        $dataAlternatif = $this->model('AlternatifModel')->getAllAlternatif();
        $data['title'] = 'Master Alternatif';
        $data['dataAlternatif'] = $dataAlternatif;

        $this->view('masterdata/alternatif', $data);
    }

    public function tambahAlternatif()
    {
        $this->requireRole(['admin']);
        $data['title'] = 'Tambah Alternatif';
        $this->view('masterdata/TambahAlternatif', $data);
    }

    public function simpanAlternatif()
    {
        $this->requireRole(['admin']);

        $data = [
            'kode_alternatif' => $_POST['kode_alternatif'],
            'nama_alternatif' => $_POST['nama_alternatif'],
            'gambar' => $_POST['gambar'] ?? null
        ];

        $this->model('AlternatifModel')->tambahDataAlternatif($data);
        header('Location: ' . BASEURL . '/masterdata/alternatif');
        exit;
    }

    public function editAlternatif($id)
    {
        $this->requireRole(['admin']);

        $data['data'] = $this->model('AlternatifModel')->getAlternatifById($id);
        $data['title'] = 'Edit Alternatif';
        $this->view('masterdata/edit', $data);
    }

    public function updateAlternatif()
    {
        $this->requireRole(['admin']);

        $data = [
            'id_alternatif' => $_POST['id_alternatif'],
            'kode_alternatif' => $_POST['kode_alternatif'],
            'nama_alternatif' => $_POST['nama_alternatif'],
            'gambar' => $_POST['gambar']
        ];

        $this->model('AlternatifModel')->ubahDataAlternatif($data);
        header('Location: ' . BASEURL . '/masterdata/alternatif');
        exit;
    }

    public function hapusAlternatif($id)
    {
        $this->requireRole(['admin']);
        $this->model('AlternatifModel')->hapusDataAlternatif($id);
        header('Location: ' . BASEURL . '/masterdata/alternatif');
        exit;
    }
}