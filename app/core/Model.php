<?php
class Model
{
    protected $db;
    public function __construct()
    {
        $config = require __DIR__ . '/../config/database.php';
        $dsn = 'mysql:host=' . $config['db_host'] . ';dbname=' . $config['db_name'] . ';charset=utf8mb4';
        try {
            $this->db = new PDO($dsn, $config['db_user'], $config['db_pass'], [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
            ]);
        } catch (PDOException $e) {
            die('Koneksi DB Gagal: ' . $e->getMessage());
        }
    }
}