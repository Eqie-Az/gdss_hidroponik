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

        // 1. Ambil data input
        $kode = $_POST['kode_alternatif'];
        $nama = $_POST['nama_alternatif'];

        // 2. Siapkan Variabel Gambar
        $file_gambar = $_FILES['gambar'] ?? null;
        $nama_file_baru = null; // Default null jika tidak ada gambar

        // 3. Cek apakah user mengupload gambar?
        if ($file_gambar && $file_gambar['error'] == UPLOAD_ERR_OK) {

            // Validasi Ekstensi
            $allowed_ext = ['jpg', 'jpeg', 'png', 'gif'];
            $file_info = pathinfo($file_gambar['name']);
            $extension = strtolower($file_info['extension']);

            if (in_array($extension, $allowed_ext)) {
                // Buat Nama File Unik (Kode + Waktu) agar tidak bentrok
                $nama_file_baru = $kode . '_' . time() . '.' . $extension;

                // Tentukan Folder Tujuan (Pastikan path ini benar)
                // __DIR__ ada di app/controllers, jadi naik 2 level ke root, lalu ke public
                $target_dir = __DIR__ . '/../../public/assets/img/alternatif/';

                // Buat folder jika belum ada
                if (!is_dir($target_dir)) {
                    mkdir($target_dir, 0777, true);
                }

                // Pindahkan file dari folder sementara ke folder tujuan
                if (!move_uploaded_file($file_gambar['tmp_name'], $target_dir . $nama_file_baru)) {
                    $this->setFlash('error', 'Gagal mengunggah gambar ke server.');
                    header('Location: ' . BASEURL . '/MasterData/tambahAlternatif');
                    exit;
                }
            } else {
                $this->setFlash('error', 'Format gambar salah! Gunakan JPG atau PNG.');
                header('Location: ' . BASEURL . '/MasterData/tambahAlternatif');
                exit;
            }
        }

        // 4. Simpan ke Database via Model
        // Gunakan method 'create' atau 'tambahDataAlternatif' sesuai yang ada di Model Anda
        $data = [
            'kode_alternatif' => $kode,
            'nama_alternatif' => $nama,
            'gambar' => $nama_file_baru // Simpan nama filenya saja
        ];

        // Pastikan nama method model sesuai (di file AlternatifModel.php Anda pakai 'tambahDataAlternatif')
        if ($this->model('AlternatifModel')->tambahDataAlternatif($data) > 0) {
            $this->setFlash('success', 'Alternatif berhasil ditambahkan!');
        } else {
            $this->setFlash('error', 'Gagal menyimpan data ke database.');
        }

        header('Location: ' . BASEURL . '/masterdata/alternatif');
        exit;
    }

    // Memastikan Controller memanggil View yang BARU (edit_alternatif)
    public function editAlternatif($id)
    {
        $this->requireRole(['admin']);

        // Gunakan $this->model(...) agar konsisten
        $data['data'] = $this->model('AlternatifModel')->getAlternatifById($id);
        $data['title'] = 'Edit Alternatif';

        // PERBAIKAN: Arahkan ke 'masterdata/edit_alternatif', BUKAN 'masterdata/edit'
        $this->view('masterdata/edit_alternatif', $data);
    }

    // Logika Update dengan Upload Gambar
    public function updateAlternatif()
    {
        $this->requireRole(['admin']);

        $id = $_POST['id_alternatif'];
        $kode = $_POST['kode_alternatif'];
        $nama = $_POST['nama_alternatif'];

        // 1. Ambil data gambar lama dari database (sebagai default)
        $dataLama = $this->model('AlternatifModel')->getAlternatifById($id);
        $nama_file = $dataLama['gambar'];

        // 2. Cek apakah user mengupload gambar baru?
        $file_gambar = $_FILES['gambar'] ?? null;

        if ($file_gambar && $file_gambar['error'] == UPLOAD_ERR_OK) {
            $allowed_ext = ['jpg', 'jpeg', 'png', 'gif'];
            $file_info = pathinfo($file_gambar['name']);
            $extension = strtolower($file_info['extension']);

            if (in_array($extension, $allowed_ext)) {
                // Buat nama file baru yang unik
                $nama_file_baru = $kode . '_' . time() . '.' . $extension;
                $target_dir = __DIR__ . '/../../public/assets/img/alternatif/';

                // Upload file baru
                if (move_uploaded_file($file_gambar['tmp_name'], $target_dir . $nama_file_baru)) {
                    // Hapus file lama jika ada (opsional, untuk hemat storage)
                    if ($nama_file && file_exists($target_dir . $nama_file)) {
                        unlink($target_dir . $nama_file);
                    }
                    // Update variabel nama file dengan yang baru
                    $nama_file = $nama_file_baru;
                }
            }
        }

        // 3. Simpan ke database
        $data = [
            'id_alternatif' => $id,
            'kode_alternatif' => $kode,
            'nama_alternatif' => $nama,
            'gambar' => $nama_file // Akan berisi gambar baru atau tetap gambar lama
        ];

        $this->model('AlternatifModel')->ubahDataAlternatif($data);
        $this->setFlash('success', 'Data alternatif berhasil diperbarui!');
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