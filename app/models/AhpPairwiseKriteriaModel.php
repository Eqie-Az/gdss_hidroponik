<?php

class AhpPairwiseKriteriaModel extends Model
{
    private $table = 'ahp_pairwise_kriteria';

    // Simpan atau Update nilai perbandingan
    public function simpan($id_pengguna, $k1, $k2, $nilai)
    {
        // Cek apakah data sudah ada?
        $cek = $this->db->prepare("SELECT id_pk FROM {$this->table} WHERE id_pengguna=:u AND kriteria_1=:k1 AND kriteria_2=:k2");
        $cek->execute([':u' => $id_pengguna, ':k1' => $k1, ':k2' => $k2]);
        
        if ($cek->rowCount() > 0) {
            // Update
            $stmt = $this->db->prepare("UPDATE {$this->table} SET nilai=:n WHERE id_pengguna=:u AND kriteria_1=:k1 AND kriteria_2=:k2");
        } else {
            // Insert
            $stmt = $this->db->prepare("INSERT INTO {$this->table} (id_pengguna, kriteria_1, kriteria_2, nilai) VALUES (:u, :k1, :k2, :n)");
        }
        
        $stmt->execute([':u' => $id_pengguna, ':k1' => $k1, ':k2' => $k2, ':n' => $nilai]);
    }

    // Ambil matriks nilai untuk ditampilkan kembali di form
    public function getMatrix($id_pengguna)
    {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE id_pengguna = :u");
        $stmt->execute([':u' => $id_pengguna]);
        $rows = $stmt->fetchAll();

        $matrix = [];
        foreach ($rows as $r) {
            $matrix[$r['kriteria_1']][$r['kriteria_2']] = $r['nilai'];
        }
        return $matrix;
    }

    // Hapus data (Reset)
    public function deleteByUser($id_pengguna)
    {
        $stmt = $this->db->prepare("DELETE FROM {$this->table} WHERE id_pengguna = :u");
        $stmt->execute([':u' => $id_pengguna]);
    }
}