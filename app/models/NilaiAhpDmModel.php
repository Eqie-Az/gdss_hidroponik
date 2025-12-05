<?php
// app/models/NilaiAhpDmModel.php

class NilaiAhpDmModel extends Model
{
    private $table = 'nilai_ahp_dm';

    public function simpanNilai($id_pengguna, $id_alternatif, $nilai_ahp)
    {
        $sql = "INSERT INTO {$this->table}
                    (id_pengguna, id_alternatif, nilai_ahp)
                VALUES
                    (:id_pengguna, :id_alternatif, :nilai_ahp)
                ON DUPLICATE KEY UPDATE
                    nilai_ahp = VALUES(nilai_ahp)";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            ':id_pengguna'   => $id_pengguna,
            ':id_alternatif' => $id_alternatif,
            ':nilai_ahp'     => $nilai_ahp,
        ]);
    }

    public function getSemuaNilaiTergrup()
    {
        $sql = "SELECT id_pengguna, id_alternatif, nilai_ahp
                FROM {$this->table}
                ORDER BY id_pengguna, nilai_ahp DESC";
        $rows = $this->db->query($sql)->fetchAll();

        $data = [];
        foreach ($rows as $row) {
            $idp = $row['id_pengguna'];
            $ida = $row['id_alternatif'];
            $val = $row['nilai_ahp'];

            if (!isset($data[$idp])) {
                $data[$idp] = [];
            }
            $data[$idp][$ida] = $val;
        }
        return $data;
    }
    // ... method sebelumnya ...

    // REVISI 3: Mengambil hasil AHP per DM untuk ditampilkan
    public function getLaporanAhp()
    {
        $sql = "SELECT n.*, u.nama_pengguna, a.nama_alternatif 
                FROM nilai_ahp_dm n
                JOIN pengguna u ON u.id_pengguna = n.id_pengguna
                JOIN alternatif a ON a.id_alternatif = n.id_alternatif
                ORDER BY u.nama_pengguna, n.nilai_ahp DESC";
        return $this->db->query($sql)->fetchAll();
    }
}
