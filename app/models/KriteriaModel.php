<?php

class KriteriaModel extends Model
{
    private $table = 'kriteria';

    public function getAllKriteria()
    {
        return $this->db->query("SELECT * FROM " . $this->table)->fetchAll();
    }

    public function getKriteriaById($id)
    {
        $stmt = $this->db->prepare("SELECT * FROM " . $this->table . " WHERE id_kriteria = :id");
        $stmt->bindValue(':id', $id);
        $stmt->execute();
        return $stmt->fetch();
    }

    public function tambahDataKriteria($data)
    {
        $query = "INSERT INTO " . $this->table . " (kode_kriteria, nama_kriteria, bobot_kriteria) VALUES (:kode, :nama, :bobot)";
        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':kode', $data['kode_kriteria']);
        $stmt->bindValue(':nama', $data['nama_kriteria']);
        $stmt->bindValue(':bobot', $data['bobot_kriteria']);
        $stmt->execute();
        return $stmt->rowCount();
    }

    public function ubahDataKriteria($data)
    {
        $query = "UPDATE " . $this->table . " SET 
                    kode_kriteria = :kode,
                    nama_kriteria = :nama,
                    bobot_kriteria = :bobot
                  WHERE id_kriteria = :id";
        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':kode', $data['kode_kriteria']);
        $stmt->bindValue(':nama', $data['nama_kriteria']);
        $stmt->bindValue(':bobot', $data['bobot_kriteria']);
        $stmt->bindValue(':id', $data['id_kriteria']);
        $stmt->execute();
        return $stmt->rowCount();
    }

    public function hapusDataKriteria($id)
    {
        $stmt = $this->db->prepare("DELETE FROM " . $this->table . " WHERE id_kriteria = :id");
        $stmt->bindValue(':id', $id);
        $stmt->execute();
        return $stmt->rowCount();
    }
}