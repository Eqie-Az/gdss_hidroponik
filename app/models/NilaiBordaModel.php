<?php

class NilaiBordaModel extends Model
{
    private $table = 'nilai_borda';

    public function simpanBorda($id_pengguna, $id_alternatif, $rank, $poin)
    {
        // PERBAIKAN: Kolom 'ranking' (bukan rank)
        $query = "INSERT INTO " . $this->table . " 
                  (id_pengguna, id_alternatif, ranking, poin_borda) 
                  VALUES (:idp, :ida, :rank, :poin)";

        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':idp', $id_pengguna);
        $stmt->bindValue(':ida', $id_alternatif);
        $stmt->bindValue(':rank', $rank);
        $stmt->bindValue(':poin', $poin);

        $stmt->execute();
        return $stmt->rowCount();
    }

    public function getLaporanBorda()
    {
        // PERBAIKAN: 
        // 1. Ganti 'u.nama_pengguna' -> 'u.nama_lengkap'
        // 2. Ganti 'n.rank' -> 'n.ranking'
        $query = "SELECT 
                    n.*, 
                    u.nama_lengkap, 
                    a.nama_alternatif 
                  FROM " . $this->table . " n
                  JOIN pengguna u ON n.id_pengguna = u.id_pengguna
                  JOIN alternatif a ON n.id_alternatif = a.id_alternatif
                  ORDER BY u.nama_lengkap ASC, n.ranking ASC";

        return $this->db->query($query)->fetchAll();
    }

    public function hitungHasilKonsensus()
    {
        // 1. Bersihkan tabel hasil lama
        $this->db->query("DELETE FROM hasil_konsensus");

        // 2. Hitung Total Poin Borda per Alternatif
        // (Dijumlahkan dari semua juri)
        $query = "SELECT 
                    id_alternatif, 
                    SUM(poin_borda) as total_poin 
                  FROM " . $this->table . " 
                  GROUP BY id_alternatif 
                  ORDER BY total_poin DESC";

        $hasil = $this->db->query($query)->fetchAll();

        // 3. Simpan ke tabel hasil_konsensus dengan Ranking Baru
        $rank = 1;
        $stmt = $this->db->prepare("INSERT INTO hasil_konsensus (id_alternatif, total_poin, ranking_final) VALUES (:ida, :total, :rank)");

        foreach ($hasil as $row) {
            $stmt->bindValue(':ida', $row['id_alternatif']);
            $stmt->bindValue(':total', $row['total_poin']);
            $stmt->bindValue(':rank', $rank);
            $stmt->execute();
            $rank++;
        }
    }
}