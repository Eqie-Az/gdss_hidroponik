<?php

class PetaniController extends Controller
{
    // REVISI: CRUD Data DM (Petani Mengelola Tanaman Sendiri)
    public function index()
    {
        $this->requireRole(['farmer']);

        // Asumsi: Semua petani melihat data alternatif yang sama (Master).
        // Jika ingin data privat, query harus difilter WHERE id_pengguna = session
        $data['tanaman'] = $this->model('AlternatifModel')->getAllAlternatif();
        $data['title'] = 'Kelola Data Tanaman Saya';

        $this->view('petani/index', $data);
    }

    public function tambah()
    {
        $this->requireRole(['farmer']);
        $this->view('petani/tambah', ['title' => 'Tambah Tanaman']);
    }

    public function simpan()
    {
        $this->requireRole(['farmer']);

        // Upload Gambar
        $gambar = null;
        if (!empty($_FILES['gambar']['name'])) {
            $target = __DIR__ . '/../../public/assets/img/alternatif/';
            if (!is_dir($target))
                mkdir($target, 0777, true);

            $ext = pathinfo($_FILES['gambar']['name'], PATHINFO_EXTENSION);
            $gambar = 'tani_' . time() . '.' . $ext;
            move_uploaded_file($_FILES['gambar']['tmp_name'], $target . $gambar);
        }

        $data = [
            'kode_alternatif' => $_POST['kode'],
            'nama_alternatif' => $_POST['nama'],
            'ladang' => $_POST['ladang'], // Input ladang
            'tgl_tanam' => $_POST['tgl'],    // Input tanggal
            'gambar' => $gambar
        ];

        // Menggunakan AlternatifModel yang sudah ada
        if ($this->model('AlternatifModel')->tambahDataAlternatif($data) > 0) {
            $this->setFlash('success', 'Data tanaman berhasil ditambahkan');
        }
        header('Location: ' . BASEURL . '/petani');
    }

    public function edit($id)
    {
        $this->requireRole(['farmer']);
        $data['dt'] = $this->model('AlternatifModel')->getAlternatifById($id);
        $data['title'] = 'Edit Data Tanaman';
        $this->view('petani/edit', $data);
    }

    public function update()
    {
        $this->requireRole(['farmer']);
        $id = $_POST['id'];
        $old = $this->model('AlternatifModel')->getAlternatifById($id);

        $gambar = $old['gambar'];
        if (!empty($_FILES['gambar']['name'])) {
            $target = __DIR__ . '/../../public/assets/img/alternatif/';
            $ext = pathinfo($_FILES['gambar']['name'], PATHINFO_EXTENSION);
            $gambar = 'tani_' . time() . '.' . $ext;
            move_uploaded_file($_FILES['gambar']['tmp_name'], $target . $gambar);
        }

        $data = [
            'id_alternatif' => $id,
            'kode_alternatif' => $_POST['kode'],
            'nama_alternatif' => $_POST['nama'],
            'ladang' => $_POST['ladang'],
            'tgl_tanam' => $_POST['tgl'],
            'gambar' => $gambar
        ];

        $this->model('AlternatifModel')->ubahDataAlternatif($data);
        $this->setFlash('success', 'Data tanaman diperbarui');
        header('Location: ' . BASEURL . '/petani');
    }

    public function hapus($id)
    {
        $this->requireRole(['farmer']);
        $this->model('AlternatifModel')->hapusDataAlternatif($id);
        $this->setFlash('success', 'Data dihapus');
        header('Location: ' . BASEURL . '/petani');
    }
}