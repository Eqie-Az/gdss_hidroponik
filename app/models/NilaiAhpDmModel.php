<?php

class NilaiAhpDmModel extends Model
{
    // PERBAIKAN: Nama tabel disesuaikan dengan database baru
    private $table = 'nilai_ahp';

    /**
     * Menyimpan nilai hasil perhitungan AHP per user
     */
    public function simpanNilai($id_pengguna, $id_alternatif, $nilai_akhir)
    {
        // 1. Cek apakah sudah ada data (agar tidak duplikat)
        $check = "SELECT id_nilai_ahp FROM " . $this->table . " 
                  WHERE id_pengguna = :idp AND id_alternatif = :ida";
        $stmtCheck = $this->db->prepare($check);
        $stmtCheck->bindValue(':idp', $id_pengguna);
        $stmtCheck->bindValue(':ida', $id_alternatif);
        $stmtCheck->execute();

        if ($stmtCheck->rowCount() > 0) {
            // Update jika ada
            $query = "UPDATE " . $this->table . " 
                      SET nilai_akhir = :val 
                      WHERE id_pengguna = :idp AND id_alternatif = :ida";
        } else {
            // Insert jika belum ada
            $query = "INSERT INTO " . $this->table . " (id_pengguna, id_alternatif, nilai_akhir) 
                      VALUES (:idp, :ida, :val)";
        }

        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':idp', $id_pengguna);
        $stmt->bindValue(':ida', $id_alternatif);
        $stmt->bindValue(':val', $nilai_akhir);
        $stmt->execute();
    }

    /**
     * Mengambil semua nilai untuk perhitungan Borda
     * Format return: [id_pengguna => [id_alternatif => nilai]]
     */
    public function getSemuaNilaiTergrup()
    {
        $query = "SELECT * FROM " . $this->table;
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        $result = $stmt->fetchAll();

        $data = [];
        foreach ($result as $row) {
            $data[$row['id_pengguna']][$row['id_alternatif']] = $row['nilai_akhir'];
        }

        return $data;
    }

    /**
     * Mengambil data untuk Laporan (Halaman Perhitungan)
     * Join dengan tabel Pengguna dan Alternatif untuk menampilkan nama
     */
    public function getLaporanAhp()
    {
        // PERBAIKAN: Query disesuaikan dengan nama kolom baru (nama_lengkap)
        $query = "SELECT n.*, u.nama_lengkap, a.nama_alternatif 
                  FROM " . $this->table . " n
                  JOIN pengguna u ON n.id_pengguna = u.id_pengguna
                  JOIN alternatif a ON n.id_alternatif = a.id_alternatif
                  ORDER BY u.nama_lengkap ASC, n.nilai_akhir DESC";

        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    /**
     * Menghapus semua data (Reset)
     */
    public function truncate()
    {
        $this->db->query("TRUNCATE TABLE " . $this->table);
    }
}