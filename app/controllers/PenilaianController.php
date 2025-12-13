<?php

require_once __DIR__ . '/../models/AhpHitungModel.php';
require_once __DIR__ . '/../models/AhpPairwiseKriteriaModel.php';
require_once __DIR__ . '/../models/AhpPairwiseAlternatifModel.php';

class PenilaianController extends Controller
{
    /**
     * DASHBOARD UTAMA PENILAIAN
     * Menampilkan status pengisian (Progress)
     */
    public function index()
    {
        $this->requireRole(['farmer', 'ketua']);
        $this->startSession();
        $uid = $_SESSION['user_id'];

        $title = 'Dashboard Penilaian AHP';
        $kriteria = $this->model('KriteriaModel')->getAllKriteria();

        // Cek status pengisian Kriteria dari Database
        $pwKriteria = $this->model('AhpPairwiseKriteriaModel')->getMatrix($uid);
        $statusKriteria = !empty($pwKriteria);

        // Cek status pengisian Alternatif per Kriteria
        $pwAltModel = $this->model('AhpPairwiseAlternatifModel');
        $statusAlt = [];
        foreach ($kriteria as $k) {
            $statusAlt[$k['id_kriteria']] = $pwAltModel->isDone($uid, $k['id_kriteria']);
        }

        $this->view('penilaian/dashboard_ahp', compact('title', 'kriteria', 'statusKriteria', 'statusAlt'));
    }

    /**
     * HALAMAN DETAIL PENILAIAN (PUSAT CRUD)
     * Menampilkan data mentah yang sudah diinput & tombol Edit/Hapus
     */
    public function detail()
    {
        $this->requireRole(['farmer', 'ketua']);
        $this->startSession();
        $uid = $_SESSION['user_id'];

        $data['title'] = 'Detail Penilaian Saya';

        // 1. Ambil Data Kriteria & Matriks Inputannya
        $data['kriteria'] = $this->model('KriteriaModel')->getAllKriteria();
        $data['nilai_kriteria'] = $this->model('AhpPairwiseKriteriaModel')->getMatrix($uid);

        // 2. Ambil Data Alternatif & Matriks Inputannya per Kriteria
        $data['alternatif'] = $this->model('AlternatifModel')->getAllAlternatif();
        $pwAltModel = $this->model('AhpPairwiseAlternatifModel');

        $data['nilai_alternatif'] = [];
        foreach ($data['kriteria'] as $k) {
            $idk = $k['id_kriteria'];
            $matrix = $pwAltModel->getMatrix($uid, $idk);
            if (!empty($matrix)) {
                $data['nilai_alternatif'][$idk] = $matrix;
            }
        }

        $this->view('penilaian/detail', $data);
    }

    // ============================================================
    // BAGIAN 1: PERBANDINGAN KRITERIA
    // ============================================================

    public function formKriteria()
    {
        $this->requireRole(['farmer', 'ketua']);
        $this->startSession();
        $uid = $_SESSION['user_id'];

        $kriteria = $this->model('KriteriaModel')->getAllKriteria();

        // Load data lama untuk Pre-fill (Mode Edit)
        $existing = $this->model('AhpPairwiseKriteriaModel')->getMatrix($uid);

        $title = 'Perbandingan Pasangan Kriteria';
        $this->view('penilaian/form_pairwise_kriteria', compact('title', 'kriteria', 'existing'));
    }

    public function prosesKriteria()
    {
        $this->requireRole(['farmer', 'ketua']);
        $this->startSession();
        $uid = $_SESSION['user_id'];
        $input = $_POST['nilai'] ?? [];

        if (empty($input)) {
            header('Location: ' . BASEURL . '/penilaian/formKriteria');
            exit;
        }

        // 1. Validasi Konsistensi (CR)
        $matrix = $this->buildFullMatrix($input);
        $ahp = new AhpHitungModel();
        $hasil = $ahp->hitung($matrix);

        if (!$hasil['is_valid']) {
            $this->setFlash('error', 'Inkonsisten! Nilai CR: ' . round($hasil['CR'], 3) . ' (Maks 0.1). Mohon perbaiki inputan Anda.');
            header('Location: ' . BASEURL . '/penilaian/formKriteria');
            exit;
        }

        // 2. Simpan ke Database
        $model = $this->model('AhpPairwiseKriteriaModel');
        foreach ($input as $k1 => $cols) {
            foreach ($cols as $k2 => $val) {
                // Simpan nilai input user
                $model->simpan($uid, $k1, $k2, $val);

                // Simpan nilai inversnya juga (segitiga bawah) agar data lengkap saat diload
                if ($val != 0) {
                    $model->simpan($uid, $k2, $k1, 1 / $val);
                }
            }
        }

        $this->setFlash('success', 'Penilaian Kriteria berhasil disimpan (Konsisten).');
        header('Location: ' . BASEURL . '/penilaian');
    }

    public function resetKriteria()
    {
        $this->requireRole(['farmer', 'ketua']);
        $this->startSession();
        $this->model('AhpPairwiseKriteriaModel')->deleteByUser($_SESSION['user_id']);
        $this->setFlash('success', 'Semua data penilaian kriteria telah dihapus.');
        header('Location: ' . BASEURL . '/penilaian');
    }

    // ============================================================
    // BAGIAN 2: PERBANDINGAN ALTERNATIF
    // ============================================================

    public function formAlternatif($id_kriteria)
    {
        $this->requireRole(['farmer', 'ketua']);
        $this->startSession();
        $uid = $_SESSION['user_id'];

        $kriteria = $this->model('KriteriaModel')->getKriteriaById($id_kriteria);
        $alternatif = $this->model('AlternatifModel')->getAllAlternatif();

        // Load data lama untuk Pre-fill (Mode Edit)
        $existing = $this->model('AhpPairwiseAlternatifModel')->getMatrix($uid, $id_kriteria);

        $title = 'Perbandingan Alternatif - ' . $kriteria['nama_kriteria'];
        $this->view('penilaian/form_pairwise_alternatif', compact('title', 'kriteria', 'alternatif', 'existing'));
    }

    public function prosesAlternatif()
    {
        $this->requireRole(['farmer', 'ketua']);
        $this->startSession();
        $uid = $_SESSION['user_id'];

        $id_kriteria = $_POST['id_kriteria'];
        $input = $_POST['nilai'] ?? [];

        if (empty($input)) {
            header('Location: ' . BASEURL . '/penilaian');
            exit;
        }

        // 1. Validasi Konsistensi
        $matrix = $this->buildFullMatrix($input);
        $ahp = new AhpHitungModel();
        $hasil = $ahp->hitung($matrix);

        if (!$hasil['is_valid']) {
            $this->setFlash('error', 'Inkonsisten! Nilai CR: ' . round($hasil['CR'], 3) . '. Mohon perbaiki.');
            header('Location: ' . BASEURL . '/penilaian/formAlternatif/' . $id_kriteria);
            exit;
        }

        // 2. Simpan ke Database
        $model = $this->model('AhpPairwiseAlternatifModel');
        foreach ($input as $a1 => $cols) {
            foreach ($cols as $a2 => $val) {
                $model->simpan($uid, $id_kriteria, $a1, $a2, $val);
                if ($val != 0) {
                    $model->simpan($uid, $id_kriteria, $a2, $a1, 1 / $val);
                }
            }
        }

        $this->setFlash('success', 'Penilaian alternatif untuk kriteria ini berhasil disimpan.');
        header('Location: ' . BASEURL . '/penilaian');
    }

    public function resetAlternatif($id_kriteria)
    {
        $this->requireRole(['farmer', 'ketua']);
        $this->startSession();
        $this->model('AhpPairwiseAlternatifModel')->deleteByKriteria($_SESSION['user_id'], $id_kriteria);
        $this->setFlash('success', 'Data penilaian alternatif untuk kriteria ini dihapus.');
        header('Location: ' . BASEURL . '/penilaian');
    }

    // ============================================================
    // BAGIAN 3: HITUNG HASIL AKHIR & SIMPAN
    // ============================================================

    public function simpanFinal()
    {
        $this->requireRole(['farmer', 'ketua']);
        $this->startSession();
        $uid = $_SESSION['user_id'];

        // 1. Ambil & Hitung Bobot Kriteria
        $pwKriteria = $this->model('AhpPairwiseKriteriaModel')->getMatrix($uid);
        if (empty($pwKriteria)) {
            $this->setFlash('error', 'Data Penilaian Kriteria belum lengkap!');
            header('Location: ' . BASEURL . '/penilaian');
            exit;
        }
        $ahp = new AhpHitungModel();
        $resKriteria = $ahp->hitung($pwKriteria);
        $bobotKriteria = $resKriteria['bobot'];

        // 2. Ambil & Hitung Bobot Alternatif Lokal per Kriteria
        $kriteriaAll = $this->model('KriteriaModel')->getAllKriteria();
        $alternatifAll = $this->model('AlternatifModel')->getAllAlternatif();
        $pwAltModel = $this->model('AhpPairwiseAlternatifModel');

        $bobotAltLocal = [];
        foreach ($kriteriaAll as $k) {
            $idk = $k['id_kriteria'];
            $mat = $pwAltModel->getMatrix($uid, $idk);

            if (empty($mat)) {
                $this->setFlash('error', 'Penilaian Alternatif untuk ' . $k['nama_kriteria'] . ' belum diisi.');
                header('Location: ' . BASEURL . '/penilaian');
                exit;
            }

            $resAlt = $ahp->hitung($mat);
            $bobotAltLocal[$idk] = $resAlt['bobot'];
        }

        // 3. Agregasi (Synthesis) Menjadi Skor Akhir AHP
        // Skor Global Alternatif = Sum(Bobot Kriteria * Bobot Lokal Alternatif)
        $ahpFinalModel = $this->model('NilaiAhpDmModel');

        foreach ($alternatifAll as $a) {
            $ida = $a['id_alternatif'];
            $nilai_akhir = 0;

            foreach ($bobotKriteria as $idk => $bk) {
                // Bobot lokal alternatif A pada kriteria K
                // Jika tidak ada data (misal alternatif baru ditambah tapi belum dinilai), anggap 0
                $local = $bobotAltLocal[$idk][$ida] ?? 0;
                $nilai_akhir += ($bk * $local);
            }

            // Simpan Skor Akhir ke tabel 'nilai_ahp' (untuk dipakai Borda nanti)
            $ahpFinalModel->simpanNilai($uid, $ida, $nilai_akhir);
        }

        $this->setFlash('success', 'Perhitungan AHP Selesai! Data telah disimpan untuk proses Borda.');
        header('Location: ' . BASEURL . '/laporan/hasil');
    }

    // Helper: Mengubah input array segitiga atas menjadi matriks penuh
    private function buildFullMatrix($input)
    {
        $matrix = [];
        // Isi matriks berdasarkan input user
        foreach ($input as $r => $cols) {
            $matrix[$r][$r] = 1; // Diagonal
            foreach ($cols as $c => $val) {
                $val = floatval($val);
                $matrix[$r][$c] = $val;
                $matrix[$c][$r] = ($val != 0) ? 1 / $val : 0; // Invers
            }
        }
        return $matrix;
    }
}