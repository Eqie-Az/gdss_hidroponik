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
        $sql = "SELECT h.*, a.nama_alternatif, a.gambar
                FROM {$this->table} h
                JOIN alternatif a ON a.id_alternatif = h.id_alternatif
                ORDER BY h.peringkat_akhir ASC";

        return $this->db->query($sql)->fetchAll();
    }

    /**
     * Menghapus semua data hasil konsensus.
     * Dipakai ketika tombol "Hitung Ulang" ditekan
     * dan data penilaian kosong, atau saat reset hasil lama.
     */
    public function hapusSemuaHasil()
    {
        // Pakai DELETE agar aman jika ada constraint foreign key
        $sql = "DELETE FROM {$this->table}";
        $this->db->query($sql);
    }

    /*
    // OPTIONAL: nanti kalau sudah siap logika hitung ulang,
    // kamu bisa tambahkan fungsi seperti ini:

    public function generateHasilAkhirDariBorda()
    {
        // Contoh pseudo-code: ambil data dari tabel nilai_borda,
        // hitung total poin, ranking, dan insert ke $this->table
    }
    */
}
