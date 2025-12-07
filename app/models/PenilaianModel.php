<?php
// app/models/PenilaianModel.php

class PenilaianModel extends Model
{
    private $table = 'penilaian';

    // REVISI: Parameter terakhir diubah dari id_subkriteria menjadi nilai (angka)
    public function savePenilaian($id_pengguna, $id_alternatif, $id_kriteria, $nilai)
    {
        // Simpan nilai input manual ke kolom 'nilai'
        // Kolom id_subkriteria kita set NULL karena inputnya manual
        $sql = "INSERT INTO {$this->table}
                    (id_pengguna, id_alternatif, id_kriteria, nilai, id_subkriteria)
                VALUES
                    (:id_pengguna, :id_alternatif, :id_kriteria, :nilai, NULL)
                ON DUPLICATE KEY UPDATE
                    nilai = VALUES(nilai),
                    id_subkriteria = NULL";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            ':id_pengguna' => $id_pengguna,
            ':id_alternatif' => $id_alternatif,
            ':id_kriteria' => $id_kriteria,
            ':nilai' => $nilai
        ]);
    }

    // REVISI: Mengambil data 'nilai' langsung, bukan bobot subkriteria
    public function getDataUntukAhp()
    {
        $sql = "SELECT
                    p.id_pengguna,
                    p.id_alternatif,
                    k.id_kriteria,
                    k.bobot_kriteria,
                    p.nilai AS nilai_input
                FROM penilaian p
                JOIN kriteria k ON k.id_kriteria = p.id_kriteria
                ORDER BY p.id_pengguna, p.id_alternatif, k.id_kriteria";
        return $this->db->query($sql)->fetchAll();
    }

    public function getAllPenilaianLengkap()
    {
        // Revisi tampilan data agar menampilkan nilai angka
        $sql = "SELECT 
                    p.id_pengguna, u.nama_pengguna, 
                    p.id_alternatif, a.nama_alternatif,
                    p.id_kriteria, k.nama_kriteria,
                    p.nilai as nilai_input
                FROM penilaian p
                JOIN pengguna u ON u.id_pengguna = p.id_pengguna
                JOIN alternatif a ON a.id_alternatif = p.id_alternatif
                JOIN kriteria k ON k.id_kriteria = p.id_kriteria
                ORDER BY u.nama_pengguna, a.id_alternatif, k.id_kriteria";
        return $this->db->query($sql)->fetchAll();
    }
}