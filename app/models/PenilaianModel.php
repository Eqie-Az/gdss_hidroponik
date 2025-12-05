<?php
// app/models/PenilaianModel.php

class PenilaianModel extends Model
{
    private $table = 'penilaian';

    public function savePenilaian($id_pengguna, $id_alternatif, $id_kriteria, $id_subkriteria)
    {
        $sql = "INSERT INTO {$this->table}
                    (id_pengguna, id_alternatif, id_kriteria, id_subkriteria)
                VALUES
                    (:id_pengguna, :id_alternatif, :id_kriteria, :id_subkriteria)
                ON DUPLICATE KEY UPDATE
                    id_subkriteria = VALUES(id_subkriteria)";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            ':id_pengguna' => $id_pengguna,
            ':id_alternatif' => $id_alternatif,
            ':id_kriteria' => $id_kriteria,
            ':id_subkriteria' => $id_subkriteria,
        ]);
    }

    public function getDataUntukAhp()
    {
        $sql = "SELECT
                    p.id_pengguna,
                    p.id_alternatif,
                    k.id_kriteria,
                    k.bobot_kriteria,
                    s.bobot_subkriteria
                FROM penilaian p
                JOIN kriteria k ON k.id_kriteria = p.id_kriteria
                JOIN subkriteria s ON s.id_subkriteria = p.id_subkriteria
                ORDER BY p.id_pengguna, p.id_alternatif, k.id_kriteria";
        return $this->db->query($sql)->fetchAll();
    }
    // ... method sebelumnya ...

    // REVISI 2: Mengambil semua penilaian lengkap dengan nama penilai, kriteria, dll
    public function getAllPenilaianLengkap()
    {
        $sql = "SELECT 
                    p.id_pengguna, u.nama_pengguna, 
                    p.id_alternatif, a.nama_alternatif,
                    p.id_kriteria, k.nama_kriteria,
                    s.nama_subkriteria, s.bobot_subkriteria
                FROM penilaian p
                JOIN pengguna u ON u.id_pengguna = p.id_pengguna
                JOIN alternatif a ON a.id_alternatif = p.id_alternatif
                JOIN kriteria k ON k.id_kriteria = p.id_kriteria
                JOIN subkriteria s ON s.id_subkriteria = p.id_subkriteria
                ORDER BY u.nama_pengguna, a.id_alternatif, k.id_kriteria";
        return $this->db->query($sql)->fetchAll();
    }
}
