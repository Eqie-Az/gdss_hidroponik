<?php

class PenggunaModel extends Model
{
    private $table = 'pengguna';
    // HAPUS baris 'private $db;' karena sudah ada 'protected $db' di parent class (Model)

    // HAPUS __construct() agar otomatis menggunakan koneksi dari parent

    public function getAllPengguna()
    {
        // Gunakan prepare() milik PDO
        $stmt = $this->db->prepare("SELECT * FROM " . $this->table);
        $stmt->execute();
        return $stmt->fetchAll(); // fetchAll untuk banyak data
    }

    public function getPenggunaById($id)
    {
        $stmt = $this->db->prepare("SELECT * FROM " . $this->table . " WHERE id_pengguna = :id");
        $stmt->bindValue(':id', $id);
        $stmt->execute();
        return $stmt->fetch(); // fetch untuk satu data
    }

    /**
     * Method Khusus Login
     */
    public function findByLoginName($username)
    {
        // Menggunakan syntax PDO murni
        $stmt = $this->db->prepare("SELECT * FROM " . $this->table . " WHERE username = :user");
        $stmt->bindValue(':user', $username);
        $stmt->execute();
        return $stmt->fetch();
    }

    /**
     * Method Tambah Data / Register
     */
    public function tambahDataPengguna($data)
    {
        $query = "INSERT INTO " . $this->table . " 
                    (nama_lengkap, username, password, role)
                    VALUES
                    (:nama, :user, :pass, :role)";

        $stmt = $this->db->prepare($query);

        $stmt->bindValue(':nama', $data['nama_lengkap']);
        $stmt->bindValue(':user', $data['username']);

        // Hash password sebelum simpan
        $stmt->bindValue(':pass', password_hash($data['password'], PASSWORD_DEFAULT));
        $stmt->bindValue(':role', $data['role']);

        $stmt->execute();
        return $stmt->rowCount(); // Mengembalikan jumlah baris yang terpengaruh
    }

    public function hapusDataPengguna($id)
    {
        $query = "DELETE FROM " . $this->table . " WHERE id_pengguna = :id";
        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':id', $id);

        $stmt->execute();
        return $stmt->rowCount();
    }

    public function ubahDataPengguna($data)
    {
        if (!empty($data['password'])) {
            $query = "UPDATE " . $this->table . " SET 
                        nama_lengkap = :nama,
                        username = :user,
                        password = :pass,
                        role = :role
                      WHERE id_pengguna = :id";
        } else {
            $query = "UPDATE " . $this->table . " SET 
                        nama_lengkap = :nama,
                        username = :user,
                        role = :role
                      WHERE id_pengguna = :id";
        }

        $stmt = $this->db->prepare($query);

        $stmt->bindValue(':nama', $data['nama_lengkap']);
        $stmt->bindValue(':user', $data['username']);
        $stmt->bindValue(':role', $data['role']);
        $stmt->bindValue(':id', $data['id_pengguna']);

        if (!empty($data['password'])) {
            $stmt->bindValue(':pass', password_hash($data['password'], PASSWORD_DEFAULT));
        }

        $stmt->execute();
        return $stmt->rowCount();
    }

    public function cariDataPengguna()
    {
        $keyword = $_POST['keyword'];
        $query = "SELECT * FROM " . $this->table . " WHERE nama_lengkap LIKE :keyword OR username LIKE :keyword";

        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':keyword', "%$keyword%");
        $stmt->execute();
        return $stmt->fetchAll();
    }
}