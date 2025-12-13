<?php

class AhpPairwiseAlternatifModel extends Model
{
    private $table = 'ahp_pairwise_alternatif';

    public function simpan($id_pengguna, $id_kriteria, $a1, $a2, $nilai)
    {
        $cek = $this->db->prepare("SELECT id_pa FROM {$this->table} WHERE id_pengguna=:u AND id_kriteria=:ik AND alternatif_1=:a1 AND alternatif_2=:a2");
        $cek->execute([':u' => $id_pengguna, ':ik' => $id_kriteria, ':a1' => $a1, ':a2' => $a2]);

        if ($cek->rowCount() > 0) {
            $stmt = $this->db->prepare("UPDATE {$this->table} SET nilai=:n WHERE id_pengguna=:u AND id_kriteria=:ik AND alternatif_1=:a1 AND alternatif_2=:a2");
        } else {
            $stmt = $this->db->prepare("INSERT INTO {$this->table} (id_pengguna, id_kriteria, alternatif_1, alternatif_2, nilai) VALUES (:u, :ik, :a1, :a2, :n)");
        }

        $stmt->execute([':u' => $id_pengguna, ':ik' => $id_kriteria, ':a1' => $a1, ':a2' => $a2, ':n' => $nilai]);
    }

    public function getMatrix($id_pengguna, $id_kriteria)
    {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE id_pengguna = :u AND id_kriteria = :ik");
        $stmt->execute([':u' => $id_pengguna, ':ik' => $id_kriteria]);
        $rows = $stmt->fetchAll();

        $matrix = [];
        foreach ($rows as $r) {
            $matrix[$r['alternatif_1']][$r['alternatif_2']] = $r['nilai'];
        }
        return $matrix;
    }

    // Cek apakah kriteria ini sudah dinilai oleh user?
    public function isDone($id_pengguna, $id_kriteria)
    {
        $stmt = $this->db->prepare("SELECT id_pa FROM {$this->table} WHERE id_pengguna = :u AND id_kriteria = :ik LIMIT 1");
        $stmt->execute([':u' => $id_pengguna, ':ik' => $id_kriteria]);
        return $stmt->rowCount() > 0;
    }

    public function deleteByKriteria($id_pengguna, $id_kriteria)
    {
        $stmt = $this->db->prepare("DELETE FROM {$this->table} WHERE id_pengguna = :u AND id_kriteria = :ik");
        $stmt->execute([':u' => $id_pengguna, ':ik' => $id_kriteria]);
    }
}