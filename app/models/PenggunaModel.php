<?php
// app/models/PenggunaModel.php

class PenggunaModel extends Model
{
    private $table = 'pengguna';

    public function findByLoginName($username)
    {
        $sql = "SELECT * FROM {$this->table} WHERE nama_pengguna_login = :u LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':u' => $username]);
        return $stmt->fetch();
    }

    public function create($nama, $username, $password, $peran = 'dm')
    {
        // Catatan: Sebaiknya gunakan password_hash(), tapi untuk menjaga konsistensi dengan kode awal Anda yang plain text saat login,
        // saya simpan apa adanya. Jika ingin aman, ganti logic login juga.
        $sql = "INSERT INTO {$this->table} (nama_pengguna, nama_pengguna_login, kata_sandi_hash, peran) 
                VALUES (:nama, :user, :pass, :peran)";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            ':nama' => $nama,
            ':user' => $username,
            ':pass' => $password,
            ':peran' => $peran,
        ]);
    }

    public function all()
    {
        $sql = "SELECT * FROM {$this->table} ORDER BY id_pengguna ASC";
        return $this->db->query($sql)->fetchAll();
    }

    public function findById($id)
    {
        $sql = "SELECT * FROM {$this->table} WHERE id_pengguna = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id' => $id]);
        return $stmt->fetch();
    }

    public function update($id, $nama, $username, $peran, $password = null)
    {
        if (!empty($password)) {
            $sql = "UPDATE {$this->table} 
                    SET nama_pengguna = :nama, nama_pengguna_login = :user, kata_sandi_hash = :pass, peran = :peran 
                    WHERE id_pengguna = :id";
            $this->db->prepare($sql)->execute([
                ':nama' => $nama,
                ':user' => $username,
                ':pass' => $password,
                ':peran' => $peran,
                ':id' => $id
            ]);
        } else {
            $sql = "UPDATE {$this->table} 
                    SET nama_pengguna = :nama, nama_pengguna_login = :user, peran = :peran 
                    WHERE id_pengguna = :id";
            $this->db->prepare($sql)->execute([
                ':nama' => $nama,
                ':user' => $username,
                ':peran' => $peran,
                ':id' => $id
            ]);
        }
    }

    public function delete($id)
    {
        $sql = "DELETE FROM {$this->table} WHERE id_pengguna = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id' => $id]);
    }
}