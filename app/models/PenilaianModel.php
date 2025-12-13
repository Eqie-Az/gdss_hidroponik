<?php

class PenilaianModel extends Model
{
    private $table = 'penilaian';

    /**
     * Mengambil daftar kriteria (Helper)
     */
    public function getKriteriaUntukForm()
    {
        return $this->db->query("SELECT * FROM kriteria")->fetchAll();
    }

    /**
     * [BARU] Menyimpan Nilai Rating (Satu per satu berdasarkan kriteria)
     * Digunakan oleh PenilaianController -> prosesAlternatif (Metode Rating)
     */
    public function simpanPerKriteria($id_pengguna, $id_alternatif, $id_kriteria, $nilai)
    {
        // 1. Cek apakah data penilaian untuk kombinasi ini sudah ada?
        $queryCek = "SELECT id_penilaian FROM " . $this->table . " 
                     WHERE id_pengguna = :u 
                     AND id_alternatif = :a 
                     AND id_kriteria = :k";

        $stmtCek = $this->db->prepare($queryCek);
        $stmtCek->execute([
            ':u' => $id_pengguna,
            ':a' => $id_alternatif,
            ':k' => $id_kriteria
        ]);

        if ($stmtCek->rowCount() > 0) {
            // UPDATE: Jika data sudah ada, kita update nilainya
            $sql = "UPDATE " . $this->table . " 
                    SET nilai_input = :n 
                    WHERE id_pengguna = :u AND id_alternatif = :a AND id_kriteria = :k";
        } else {
            // INSERT: Jika belum ada, kita buat baru
            $sql = "INSERT INTO " . $this->table . " (id_pengguna, id_alternatif, id_kriteria, nilai_input) 
                    VALUES (:u, :a, :k, :n)";
        }

        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            ':u' => $id_pengguna,
            ':a' => $id_alternatif,
            ':k' => $id_kriteria,
            ':n' => $nilai
        ]);

        return $stmt->rowCount();
    }

    /**
     * [BARU] Menghapus data penilaian berdasarkan Kriteria tertentu (untuk satu user)
     * Digunakan saat user menekan tombol 'Reset' di dashboard untuk kriteria tertentu.
     */
    public function hapusPerKriteria($id_pengguna, $id_kriteria)
    {
        $sql = "DELETE FROM " . $this->table . " 
                WHERE id_pengguna = :u AND id_kriteria = :k";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            ':u' => $id_pengguna,
            ':k' => $id_kriteria
        ]);

        return $stmt->rowCount();
    }

    /**
     * [LEGACY] Simpan Bulk (Hapus dulu baru Insert semua)
     * Mungkin masih digunakan oleh fitur lama, biarkan saja agar aman.
     */
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

    /**
     * Mengambil data untuk Proses Perhitungan AHP (Join dengan Bobot Kriteria)
     * Digunakan oleh: ProsesController -> jalankan()
     */
    public function getDataUntukAhp()
    {
        $query = "SELECT p.*, k.bobot_kriteria 
                  FROM penilaian p
                  JOIN kriteria k ON p.id_kriteria = k.id_kriteria";
        return $this->db->query($query)->fetchAll();
    }

    /**
     * Mengambil data lengkap untuk Laporan/Detail View
     * Digunakan oleh: PenilaianController -> detail() & Dashboard
     */
    public function getAllPenilaianLengkap()
    {
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