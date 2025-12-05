<?php
// app/models/AlternatifModel.php

class AlternatifModel extends Model
{
    private $table = 'alternatif';

    public function all()
    {
        $sql = "SELECT * FROM {$this->table} ORDER BY id_alternatif ASC";
        return $this->db->query($sql)->fetchAll();
    }

    public function findById($id_alternatif)
    {
        $sql = "SELECT * FROM {$this->table} WHERE id_alternatif = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id' => $id_alternatif]);
        return $stmt->fetch();
    }

    public function create($kode, $nama, $gambar = null)
    {
        $sql = "INSERT INTO {$this->table} (kode_alternatif, nama_alternatif, gambar) 
                VALUES (:kode, :nama, :gambar)";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            ':kode' => $kode,
            ':nama' => $nama,
            ':gambar' => $gambar,
        ]);
    }

    public function update($id, $kode, $nama, $gambar)
    {
        $sql = "UPDATE {$this->table} 
                SET kode_alternatif = :kode, 
                    nama_alternatif = :nama, 
                    gambar = :gambar 
                WHERE id_alternatif = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            ':kode' => $kode,
            ':nama' => $nama,
            ':gambar' => $gambar,
            ':id' => $id
        ]);
    }

    public function delete($id)
    {
        $sql = "DELETE FROM {$this->table} WHERE id_alternatif = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id' => $id]);
    }
}