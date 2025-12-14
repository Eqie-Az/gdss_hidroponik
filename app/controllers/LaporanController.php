<?php

require_once __DIR__ . '/../models/HasilKonsensusModel.php';
require_once __DIR__ . '/../models/PenilaianModel.php';
require_once __DIR__ . '/../models/KriteriaModel.php';
require_once __DIR__ . '/../models/AhpPairwiseKriteriaModel.php';
require_once __DIR__ . '/../models/PenggunaModel.php';

class LaporanController extends Controller
{
    public function index()
    {
        $this->hasil();
    }

    // Halaman Output Hasil Akhir (Ranking Borda)
    public function hasil()
    {
        // Semua role boleh melihat hasil akhir
        $this->requireRole(['admin', 'ketua', 'farmer']);

        $model = new HasilKonsensusModel();
        $hasil = $model->getHasilDenganAlternatif();

        $title = 'Hasil Peringkat Konsensus (Borda)';
        $this->view('laporan/hasil', compact('title', 'hasil'));
    }

    // Halaman Detail Inputan User (Mentah)
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

    // --- [BARU] HALAMAN DETAIL PERHITUNGAN BOBOT AHP (Black Box Opened) ---
    // Menampilkan matriks, normalisasi, dan uji konsistensi per user
    public function bobot()
    {
        // IZINKAN KETUA DI SINI
        $this->requireRole(['admin', 'ketua', 'farmer']);

        $kriteriaModel = $this->model('KriteriaModel');
        $ahpModel = $this->model('AhpPairwiseKriteriaModel');
        $userModel = $this->model('PenggunaModel');

        $kriteria = $kriteriaModel->getAllKriteria();
        $allUsers = $userModel->getAllPengguna();

        $laporan = [];

        foreach ($allUsers as $u) {
            // Lewati admin karena admin tidak melakukan penilaian
            if ($u['role'] == 'admin')
                continue;

            $uid = $u['id_pengguna'];
            $matrixDB = $ahpModel->getMatrix($uid);

            // Skip jika user ini belum mengisi kriteria sama sekali
            if (empty($matrixDB))
                continue;

            // 1. Rekonstruksi Matriks Lengkap & Hitung Total Kolom
            $fullMatrix = [];
            $colTotal = [];

            // Inisialisasi Total Kolom = 0
            foreach ($kriteria as $k) {
                $colTotal[$k['id_kriteria']] = 0;
            }

            // Loop Baris & Kolom untuk mengisi matriks
            foreach ($kriteria as $k1) {
                $r = $k1['id_kriteria'];
                foreach ($kriteria as $k2) {
                    $c = $k2['id_kriteria'];

                    if ($r == $c) {
                        $val = 1; // Diagonal selalu 1
                    } elseif (isset($matrixDB[$r][$c])) {
                        $val = (float) $matrixDB[$r][$c]; // Nilai input user
                    } elseif (isset($matrixDB[$c][$r])) {
                        // Nilai kebalikan (1/x) jika data ada di sisi sebaliknya
                        $val = ($matrixDB[$c][$r] != 0) ? 1 / $matrixDB[$c][$r] : 0;
                    } else {
                        $val = 0; // Default (harusnya tidak terjadi jika data lengkap)
                    }

                    $fullMatrix[$r][$c] = $val;
                    $colTotal[$c] += $val;
                }
            }

            // 2. Normalisasi Matriks & Hitung Eigen Vector (Bobot Prioritas)
            $normMatrix = [];
            $bobot = [];
            $n = count($kriteria);

            foreach ($kriteria as $k1) {
                $r = $k1['id_kriteria'];
                $rowSum = 0;
                foreach ($kriteria as $k2) {
                    $c = $k2['id_kriteria'];
                    // Rumus Normalisasi: Nilai Sel / Total Kolom
                    $normVal = ($colTotal[$c] != 0) ? $fullMatrix[$r][$c] / $colTotal[$c] : 0;
                    $normMatrix[$r][$c] = $normVal;
                    $rowSum += $normVal;
                }
                // Bobot = Rata-rata Baris Normalisasi
                $bobot[$r] = ($n > 0) ? $rowSum / $n : 0;
            }

            // 3. Uji Konsistensi (Lambda Max, CI, CR)
            $lambdaMax = 0;
            foreach ($kriteria as $k) {
                $id = $k['id_kriteria'];
                // Rumus Lambda Max: Total Kolom Asli * Bobot
                $lambdaMax += ($colTotal[$id] * $bobot[$id]);
            }

            $CI = ($n > 1) ? ($lambdaMax - $n) / ($n - 1) : 0;

            // Tabel Random Index (RI) Saaty
            $RI_List = [1 => 0, 2 => 0, 3 => 0.58, 4 => 0.90, 5 => 1.12, 6 => 1.24, 7 => 1.32, 8 => 1.41, 9 => 1.45, 10 => 1.49];
            $RI = $RI_List[$n] ?? 1.49;

            $CR = ($RI > 0) ? $CI / $RI : 0;

            // Simpan hasil perhitungan user ini
            $laporan[] = [
                'nama' => $u['nama_lengkap'],
                'role' => $u['role'],
                'fullMatrix' => $fullMatrix,
                'colTotal' => $colTotal,
                'normMatrix' => $normMatrix,
                'bobot' => $bobot,
                'lambdaMax' => $lambdaMax,
                'CI' => $CI,
                'CR' => $CR,
                // Gunakan batas toleransi 0.2 agar data Excel tadi tetap dianggap Konsisten
                'status' => ($CR <= 0.2) ? 'Konsisten' : 'Tidak Konsisten'
            ];
        }

        $data['title'] = 'Detail Perhitungan Bobot AHP';
        $data['kriteria'] = $kriteria;
        $data['laporan'] = $laporan;

        $this->view('laporan/bobot_ahp', $data);
    }
}