<?php
// app/models/NilaiBordaModel.php

class NilaiBordaModel extends Model
{
    private $table = 'nilai_borda';

    public function simpanBorda($id_pengguna, $id_alternatif, $peringkat, $poin_borda)
    {
        $sql = "INSERT INTO {$this->table}
                    (id_pengguna, id_alternatif, peringkat, poin_borda)
                VALUES
                    (:id_pengguna, :id_alternatif, :peringkat, :poin_borda)
                ON DUPLICATE KEY UPDATE
                    peringkat = VALUES(peringkat),
                    poin_borda = VALUES(poin_borda)";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            ':id_pengguna' => $id_pengguna,
            ':id_alternatif' => $id_alternatif,
            ':peringkat' => $peringkat,
            ':poin_borda' => $poin_borda,
        ]);
    }

    public function hitungHasilKonsensus()
    {
        $this->db->exec("DELETE FROM hasil_konsensus");

        $sql = "
        SELECT 
            nb.id_alternatif,
            SUM(nb.poin_borda)                          AS total_poin_borda,
            SUM(ROUND(na.nilai_ahp, 2) * nb.poin_borda) AS skor_akhir
        FROM nilai_borda nb
        JOIN nilai_ahp_dm na 
          ON na.id_pengguna   = nb.id_pengguna
         AND na.id_alternatif = nb.id_alternatif
        GROUP BY nb.id_alternatif
        ORDER BY skor_akhir DESC
    ";

        $rows = $this->db->query($sql)->fetchAll();

        $peringkat = 1;
        $stmt = $this->db->prepare("
        INSERT INTO hasil_konsensus
            (id_alternatif, total_poin_borda, skor_akhir, peringkat_akhir)
        VALUES
            (:id_alternatif, :total_poin_borda, :skor_akhir, :peringkat_akhir)
    ");

        foreach ($rows as $row) {
            $stmt->execute([
                ':id_alternatif' => $row['id_alternatif'],
                ':total_poin_borda' => $row['total_poin_borda'],
                ':skor_akhir' => $row['skor_akhir'],
                ':peringkat_akhir' => $peringkat,
            ]);
            $peringkat++;
        }
    }

    // ... method sebelumnya ...

    // REVISI 3: Mengambil hasil Poin Borda per DM
    public function getLaporanBorda()
    {
        $sql = "SELECT n.*, u.nama_pengguna, a.nama_alternatif 
                FROM nilai_borda n
                JOIN pengguna u ON u.id_pengguna = n.id_pengguna
                JOIN alternatif a ON a.id_alternatif = n.id_alternatif
                ORDER BY u.nama_pengguna, n.poin_borda DESC";
        return $this->db->query($sql)->fetchAll();
    }
}
