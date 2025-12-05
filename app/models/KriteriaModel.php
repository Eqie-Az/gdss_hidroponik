<?php
// app/models/KriteriaModel.php

class KriteriaModel extends Model
{
    private $table = 'kriteria';

    public function all()
    {
        $sql = "SELECT * FROM {$this->table} ORDER BY id_kriteria ASC";
        return $this->db->query($sql)->fetchAll();
    }

    public function findById($id)
    {
        $sql = "SELECT * FROM {$this->table} WHERE id_kriteria = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id' => $id]);
        return $stmt->fetch();
    }

    public function create($kode, $nama, $deskripsi, $bobot)
    {
        $sql = "INSERT INTO {$this->table} (kode_kriteria, nama_kriteria, deskripsi_kriteria, bobot_kriteria) 
                VALUES (:kode, :nama, :desk, :bobot)";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            ':kode' => $kode,
            ':nama' => $nama,
            ':desk' => $deskripsi,
            ':bobot' => $bobot,
        ]);
    }

    public function update($id, $kode, $nama, $deskripsi, $bobot)
    {
        $sql = "UPDATE {$this->table} 
                SET kode_kriteria = :kode, 
                    nama_kriteria = :nama, 
                    deskripsi_kriteria = :desk, 
                    bobot_kriteria = :bobot 
                WHERE id_kriteria = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            ':kode' => $kode,
            ':nama' => $nama,
            ':desk' => $deskripsi,
            ':bobot' => $bobot,
            ':id' => $id
        ]);
    }

    public function delete($id)
    {
        $sql = "DELETE FROM {$this->table} WHERE id_kriteria = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id' => $id]);
    }
}