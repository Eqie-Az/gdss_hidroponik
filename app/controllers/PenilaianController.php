<?php

require_once __DIR__ . '/../models/AhpHitungModel.php';
require_once __DIR__ . '/../models/AhpPairwiseKriteriaModel.php';

class PenilaianController extends Controller
{
    public function index()
    {
        $this->requireRole(['farmer', 'ketua']);
        $this->startSession();
        $uid = $_SESSION['user_id'];

        $title = 'Dashboard Penilaian';

        $kriteria = $this->model('KriteriaModel')->getAllKriteria();
        $alternatif = $this->model('AlternatifModel')->getAllAlternatif();
        $jmlKriteria = count($kriteria);

        // Status Kriteria
        $pwKriteria = $this->model('AhpPairwiseKriteriaModel')->getMatrix($uid);
        $statusKriteria = !empty($pwKriteria);

        // Status Alternatif
        $rawPenilaian = $this->model('PenilaianModel')->getAllPenilaianLengkap();
        $countPerAlt = [];
        foreach ($rawPenilaian as $row) {
            if ($row['nama_lengkap'] === $_SESSION['user_name']) {
                foreach ($alternatif as $a) {
                    if ($a['nama_alternatif'] == $row['nama_alternatif']) {
                        if (!isset($countPerAlt[$a['id_alternatif']]))
                            $countPerAlt[$a['id_alternatif']] = 0;
                        $countPerAlt[$a['id_alternatif']]++;
                        break;
                    }
                }
            }
        }

        $statusAlt = [];
        foreach ($alternatif as $a) {
            $ida = $a['id_alternatif'];
            $terisi = $countPerAlt[$ida] ?? 0;
            $statusAlt[$ida] = ($terisi >= $jmlKriteria && $jmlKriteria > 0);
        }

        $this->view('penilaian/dashboard_ahp', compact('title', 'kriteria', 'alternatif', 'statusKriteria', 'statusAlt'));
    }

    public function detail()
    {
        $this->requireRole(['farmer', 'ketua']);
        $this->startSession();
        $uid = $_SESSION['user_id'];

        $data['title'] = 'Detail Penilaian Saya';
        $data['kriteria'] = $this->model('KriteriaModel')->getAllKriteria();
        $data['nilai_kriteria'] = $this->model('AhpPairwiseKriteriaModel')->getMatrix($uid);
        $data['alternatif'] = $this->model('AlternatifModel')->getAllAlternatif();

        $rawPenilaian = $this->model('PenilaianModel')->getAllPenilaianLengkap();
        $mapAlt = [];
        foreach ($data['alternatif'] as $a)
            $mapAlt[$a['nama_alternatif']] = $a['id_alternatif'];
        $mapKrit = [];
        foreach ($data['kriteria'] as $k)
            $mapKrit[$k['nama_kriteria']] = $k['id_kriteria'];

        $groupedValues = [];
        foreach ($rawPenilaian as $row) {
            if ($row['nama_lengkap'] === $_SESSION['user_name']) {
                $ida = $mapAlt[$row['nama_alternatif']] ?? null;
                $idk = $mapKrit[$row['nama_kriteria']] ?? null;
                if ($ida && $idk) {
                    $groupedValues[$ida][$idk] = $row['nilai_input'];
                }
            }
        }
        $data['nilai_alternatif'] = $groupedValues;

        $this->view('penilaian/detail', $data);
    }

    // ============================================================
    // BAGIAN 1: KRITERIA
    // ============================================================
    public function formKriteria()
    {
        $this->requireRole(['farmer', 'ketua']);
        $this->startSession();
        $uid = $_SESSION['user_id'];
        $kriteria = $this->model('KriteriaModel')->getAllKriteria();
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

        $matrix = $this->buildFullMatrix($input);
        $ahp = new AhpHitungModel();
        $hasil = $ahp->hitung($matrix);

        if (!$hasil['is_valid']) {
            $this->setFlash('error', 'Inkonsisten! CR: ' . round($hasil['CR'], 3));
            header('Location: ' . BASEURL . '/penilaian/formKriteria');
            exit;
        }

        $model = $this->model('AhpPairwiseKriteriaModel');
        foreach ($input as $k1 => $cols) {
            foreach ($cols as $k2 => $val) {
                $model->simpan($uid, $k1, $k2, $val);
                if ($val != 0)
                    $model->simpan($uid, $k2, $k1, 1 / $val);
            }
        }
        $this->setFlash('success', 'Penilaian Kriteria disimpan.');
        header('Location: ' . BASEURL . '/penilaian');
    }

    public function resetKriteria()
    {
        $this->startSession();
        $this->model('AhpPairwiseKriteriaModel')->deleteByUser($_SESSION['user_id']);
        header('Location: ' . BASEURL . '/penilaian');
    }

    // ============================================================
    // BAGIAN 2: ALTERNATIF (VALIDASI DITAMBAHKAN DI SINI)
    // ============================================================

    public function formAlternatif($id_alternatif)
    {
        $this->requireRole(['farmer', 'ketua']);
        $this->startSession();
        $uid = $_SESSION['user_id'];

        // --- [BARU] VALIDASI LANGKAH 1 ---
        // Cek apakah user sudah mengisi Kriteria (Langkah 1)
        $pwKriteria = $this->model('AhpPairwiseKriteriaModel')->getMatrix($uid);
        if (empty($pwKriteria)) {
            // Jika belum, lempar balik ke form Kriteria dengan pesan error
            $this->setFlash('error', 'Anda harus menginput data pada langkah 1 terlebih dahulu!');
            header('Location: ' . BASEURL . '/penilaian/formKriteria');
            exit;
        }
        // ---------------------------------

        $targetAlternatif = $this->model('AlternatifModel')->getAlternatifById($id_alternatif);
        $kriteriaList = $this->model('KriteriaModel')->getAllKriteria();
        $rawPenilaian = $this->model('PenilaianModel')->getAllPenilaianLengkap();

        $existing = [];
        $mapKrit = [];
        foreach ($kriteriaList as $k)
            $mapKrit[$k['nama_kriteria']] = $k['id_kriteria'];

        foreach ($rawPenilaian as $row) {
            if (
                $row['nama_lengkap'] === $_SESSION['user_name'] &&
                $row['nama_alternatif'] === $targetAlternatif['nama_alternatif']
            ) {
                $namaKrit = $row['nama_kriteria'];
                if (isset($mapKrit[$namaKrit])) {
                    $idK = $mapKrit[$namaKrit];
                    $existing[$idK] = $row['nilai_input'];
                }
            }
        }

        $title = 'Input Nilai: ' . $targetAlternatif['nama_alternatif'];
        $this->view('penilaian/form_pairwise_alternatif', compact('title', 'targetAlternatif', 'kriteriaList', 'existing'));
    }

    public function prosesAlternatif()
    {
        $this->requireRole(['farmer', 'ketua']);
        $this->startSession();
        $uid = $_SESSION['user_id'];

        $id_alternatif = $_POST['id_alternatif'];
        $input_nilai = $_POST['nilai'] ?? [];

        if (empty($input_nilai)) {
            $this->setFlash('error', 'Data kosong.');
            header('Location: ' . BASEURL . '/penilaian');
            exit;
        }

        $penilaianModel = $this->model('PenilaianModel');
        foreach ($input_nilai as $id_kriteria => $nilai) {
            $penilaianModel->simpanPerKriteria($uid, $id_alternatif, $id_kriteria, $nilai);
        }

        $this->setFlash('success', 'Nilai untuk alternatif ini berhasil disimpan.');
        header('Location: ' . BASEURL . '/penilaian');
    }

    public function simpanFinal()
    {
        $this->requireRole(['farmer', 'ketua']);
        $this->startSession();
        $uid = $_SESSION['user_id'];

        $pwKriteria = $this->model('AhpPairwiseKriteriaModel')->getMatrix($uid);
        if (empty($pwKriteria)) {
            header('Location: ' . BASEURL . '/penilaian');
            exit;
        }

        $ahp = new AhpHitungModel();
        $res = $ahp->hitung($pwKriteria);
        if (!$res['is_valid']) {
            $this->setFlash('error', 'Penilaian Kriteria tidak konsisten. Perbaiki Langkah 1.');
            header('Location: ' . BASEURL . '/penilaian');
            exit;
        }
        $bobotKriteria = $res['bobot'];

        $raw = $this->model('PenilaianModel')->getAllPenilaianLengkap();
        $alts = $this->model('AlternatifModel')->getAllAlternatif();
        $krits = $this->model('KriteriaModel')->getAllKriteria();

        $mapK = [];
        foreach ($krits as $k)
            $mapK[$k['nama_kriteria']] = $k['id_kriteria'];
        $mapA = [];
        foreach ($alts as $a)
            $mapA[$a['nama_alternatif']] = $a['id_alternatif'];

        $ratings = [];
        foreach ($raw as $r) {
            if ($r['nama_lengkap'] == $_SESSION['user_name']) {
                $ida = $mapA[$r['nama_alternatif']] ?? 0;
                $idk = $mapK[$r['nama_kriteria']] ?? 0;
                if ($ida && $idk)
                    $ratings[$ida][$idk] = $r['nilai_input'];
            }
        }

        $finalModel = $this->model('NilaiAhpDmModel');
        foreach ($alts as $a) {
            $ida = $a['id_alternatif'];
            $total = 0;
            foreach ($bobotKriteria as $idk => $bk) {
                $val = $ratings[$ida][$idk] ?? 0;
                $total += ($bk * $val);
            }
            $finalModel->simpanNilai($uid, $ida, $total);
        }

        $this->setFlash('success', 'Perhitungan Selesai.');
        header('Location: ' . BASEURL . '/laporan/hasil');
    }

    private function buildFullMatrix($input)
    {
        $matrix = [];
        foreach ($input as $r => $cols) {
            $matrix[$r][$r] = 1;
            foreach ($cols as $c => $val) {
                $val = floatval($val);
                $matrix[$r][$c] = $val;
                $matrix[$c][$r] = ($val != 0) ? 1 / $val : 0;
            }
        }
        return $matrix;
    }
}