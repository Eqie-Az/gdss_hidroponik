<?php
// app/models/SubkriteriaModel.php

class SubkriteriaModel extends Model
{
    private $table = 'subkriteria';

    public function allWithKriteria()
    {
        $sql = "SELECT s.*, k.nama_kriteria 
                FROM {$this->table} s
                JOIN kriteria k ON k.id_kriteria = s.id_kriteria
                ORDER BY s.id_kriteria, s.id_subkriteria";
        return $this->db->query($sql)->fetchAll();
    }

    public function findByKriteria($id_kriteria)
    {
        $sql = "SELECT * FROM {$this->table} WHERE id_kriteria = :id ORDER BY id_subkriteria ASC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id' => $id_kriteria]);
        return $stmt->fetchAll();
    }

    public function findById($id)
    {
        $sql = "SELECT * FROM {$this->table} WHERE id_subkriteria = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id' => $id]);
        return $stmt->fetch();
    }

    public function create($id_kriteria, $kode, $nama, $bobot)
    {
        $sql = "INSERT INTO {$this->table} (id_kriteria, kode_subkriteria, nama_subkriteria, bobot_subkriteria) 
                VALUES (:id_kriteria, :kode, :nama, :bobot)";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            ':id_kriteria' => $id_kriteria,
            ':kode' => $kode,
            ':nama' => $nama,
            ':bobot' => $bobot,
        ]);
    }

    public function update($id, $id_kriteria, $kode, $nama, $bobot)
    {
        $sql = "UPDATE {$this->table} 
                SET id_kriteria = :id_kriteria,
                    kode_subkriteria = :kode, 
                    nama_subkriteria = :nama, 
                    bobot_subkriteria = :bobot 
                WHERE id_subkriteria = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            ':id_kriteria' => $id_kriteria,
            ':kode' => $kode,
            ':nama' => $nama,
            ':bobot' => $bobot,
            ':id' => $id
        ]);
    }

    public function delete($id)
    {
        $sql = "DELETE FROM {$this->table} WHERE id_subkriteria = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id' => $id]);
    }
}