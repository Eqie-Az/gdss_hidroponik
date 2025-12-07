<?php

class AlternatifModel extends Model
{
    private $table = 'alternatif';

    public function getAllAlternatif()
    {
        return $this->db->query("SELECT * FROM " . $this->table)->fetchAll();
    }

    public function getAlternatifById($id)
    {
        $stmt = $this->db->prepare("SELECT * FROM " . $this->table . " WHERE id_alternatif = :id");
        $stmt->bindValue(':id', $id);
        $stmt->execute();
        return $stmt->fetch();
    }

    public function tambahDataAlternatif($data)
    {
        $query = "INSERT INTO " . $this->table . " (kode_alternatif, nama_alternatif, gambar) VALUES (:kode, :nama, :gambar)";
        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':kode', $data['kode_alternatif']);
        $stmt->bindValue(':nama', $data['nama_alternatif']);
        $stmt->bindValue(':gambar', $data['gambar']);
        $stmt->execute();
        return $stmt->rowCount();
    }

    public function ubahDataAlternatif($data)
    {
        $query = "UPDATE " . $this->table . " SET 
                    kode_alternatif = :kode,
                    nama_alternatif = :nama,
                    gambar = :gambar
                  WHERE id_alternatif = :id";
        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':kode', $data['kode_alternatif']);
        $stmt->bindValue(':nama', $data['nama_alternatif']);
        $stmt->bindValue(':gambar', $data['gambar']);
        $stmt->bindValue(':id', $data['id_alternatif']);
        $stmt->execute();
        return $stmt->rowCount();
    }

    public function hapusDataAlternatif($id)
    {
        $stmt = $this->db->prepare("DELETE FROM " . $this->table . " WHERE id_alternatif = :id");
        $stmt->bindValue(':id', $id);
        $stmt->execute();
        return $stmt->rowCount();
    }
}