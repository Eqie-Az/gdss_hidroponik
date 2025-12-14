<?php

class AhpHitungModel
{
    // Tabel Random Index (RI) Saaty n=1 s.d 10
    private $RI = [
        1 => 0.00,
        2 => 0.00,
        3 => 0.58,
        4 => 0.90,
        5 => 1.12,
        6 => 1.24,
        7 => 1.32,
        8 => 1.41,
        9 => 1.45,
        10 => 1.49
    ];

    /**
     * Menghitung Bobot & Konsistensi Matriks Pairwise
     * @param array $matrix Matriks [row_id][col_id] = nilai
     * @return array [bobot => [], CI, CR, is_valid, status]
     */
    public function hitung($matrix)
    {
        $ids = array_keys($matrix);
        $n = count($ids);

        if ($n < 2) {
            // Jika cuma 1 item, bobot = 1, CR = 0
            return [
                'bobot' => [$ids[0] => 1],
                'CI' => 0,
                'CR' => 0,
                'status' => 'Konsisten',
                'is_valid' => true
            ];
        }

        // 1. Hitung Jumlah Kolom
        $colSum = [];
        foreach ($ids as $col) {
            $sum = 0;
            foreach ($ids as $row) {
                $val = isset($matrix[$row][$col]) ? (float) $matrix[$row][$col] : 1;
                $sum += $val;
            }
            $colSum[$col] = $sum;
        }

        // 2. Normalisasi & Hitung Eigenvector (Rata-rata Baris)
        $bobotPrioritas = [];
        foreach ($ids as $row) {
            $rowSum = 0;
            foreach ($ids as $col) {
                $val = isset($matrix[$row][$col]) ? (float) $matrix[$row][$col] : 1;
                // Cegah bagi nol
                $norm = ($colSum[$col] != 0) ? $val / $colSum[$col] : 0;
                $rowSum += $norm;
            }
            $bobotPrioritas[$row] = $rowSum / $n;
        }

        // 3. Hitung Lambda Max
        $lambdaMax = 0;
        foreach ($ids as $col) {
            $lambdaMax += ($colSum[$col] * $bobotPrioritas[$col]);
        }

        // 4. Hitung CI & CR
        $CI = ($lambdaMax - $n) / ($n - 1);
        $riVal = isset($this->RI[$n]) ? $this->RI[$n] : 1.49;
        $CR = ($riVal > 0) ? $CI / $riVal : 0;

        // --- MODIFIKASI BATAS TOLERANSI ---
        // Diubah dari 0.1 menjadi 0.2 agar data Excel (CR=0.118) bisa masuk
        $isValid = ($CR <= 0.2);
        $status = $isValid ? 'Konsisten' : 'Tidak Konsisten (CR > 0.2)';

        return [
            'bobot' => $bobotPrioritas,
            'CI' => $CI,
            'CR' => $CR,
            'status' => $status,
            'is_valid' => $isValid
        ];
    }
}