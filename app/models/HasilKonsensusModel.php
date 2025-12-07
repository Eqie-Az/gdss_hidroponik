<?php
// app/models/HasilKonsensusModel.php

class HasilKonsensusModel extends Model
{
    private $table = 'hasil_konsensus';

    /**
     * Mengambil hasil akhir beserta nama & gambar alternatif
     */
    public function getHasilDenganAlternatif()
    {
        // PERBAIKAN: Mengganti 'h.peringkat_akhir' menjadi 'h.ranking_final'
        $sql = "SELECT h.*, a.nama_alternatif, a.gambar
                FROM {$this->table} h
                JOIN alternatif a ON a.id_alternatif = h.id_alternatif
                ORDER BY h.ranking_final ASC";

        // Menggunakan PDO query standard
        return $this->db->query($sql)->fetchAll();
    }

    /**
     * Menghapus semua data hasil konsensus.
     */
    public function hapusSemuaHasil()
    {
        $sql = "DELETE FROM {$this->table}";
        $this->db->query($sql);
    }
}