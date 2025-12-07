<?php

class PenilaianModel extends Model
{
    private $table = 'penilaian';

    public function getKriteriaUntukForm()
    {
        // Menggunakan fetchAll() karena Model Anda pakai PDO murni
        return $this->db->query("SELECT * FROM kriteria")->fetchAll();
    }

    public function simpanPenilaian($id_pengguna, $id_alternatif, $data_nilai)
    {
        // 1. Hapus nilai lama jika ada (Reset) untuk user & alternatif tersebut
        $sqlHapus = "DELETE FROM " . $this->table . " WHERE id_pengguna = :idp AND id_alternatif = :ida";
        $stmt = $this->db->prepare($sqlHapus);
        $stmt->bindValue(':idp', $id_pengguna);
        $stmt->bindValue(':ida', $id_alternatif);
        $stmt->execute();

        // 2. Insert nilai baru
        $sqlInsert = "INSERT INTO " . $this->table . " (id_pengguna, id_alternatif, id_kriteria, nilai_input) VALUES (:idp, :ida, :idk, :val)";
        $stmtInsert = $this->db->prepare($sqlInsert);

        foreach ($data_nilai as $id_kriteria => $nilai) {
            $stmtInsert->bindValue(':idp', $id_pengguna);
            $stmtInsert->bindValue(':ida', $id_alternatif);
            $stmtInsert->bindValue(':idk', $id_kriteria);
            $stmtInsert->bindValue(':val', $nilai);
            $stmtInsert->execute();
        }

        return true;
    }

    public function getDataUntukAhp()
    {
        // Query untuk perhitungan (Join Kriteria)
        $query = "SELECT p.*, k.bobot_kriteria 
                  FROM penilaian p
                  JOIN kriteria k ON p.id_kriteria = k.id_kriteria";
        return $this->db->query($query)->fetchAll();
    }

    public function getAllPenilaianLengkap()
    {
        // PERBAIKAN DI SINI:
        // 1. Ganti 'u.nama_pengguna' menjadi 'u.nama_lengkap'
        // 2. Gunakan JOIN kriteria (bukan sub_kriteria)

        $query = "SELECT 
                    p.id_penilaian,
                    u.nama_lengkap, 
                    a.nama_alternatif,
                    k.nama_kriteria,
                    p.nilai_input
                  FROM penilaian p
                  JOIN pengguna u ON p.id_pengguna = u.id_pengguna
                  JOIN alternatif a ON p.id_alternatif = a.id_alternatif
                  JOIN kriteria k ON p.id_kriteria = k.id_kriteria
                  ORDER BY u.nama_lengkap ASC, a.nama_alternatif ASC, k.id_kriteria ASC";

        return $this->db->query($query)->fetchAll();
    }
}