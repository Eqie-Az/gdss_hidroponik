<?php
// DETEKSI BASEURL OTOMATIS (SUPAYA CSS TIDAK ERROR)
$protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https://' : 'http://';
$host = $_SERVER['HTTP_HOST'];
// Ambil path folder tempat script ini berada (biasanya /.../public)
$path = str_replace(basename($_SERVER['SCRIPT_NAME']), '', $_SERVER['SCRIPT_NAME']);
// Hapus slash di akhir agar rapi
define('BASEURL', rtrim($protocol . $host . $path, '/'));

ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/../app/core/App.php';
require_once __DIR__ . '/../app/core/Controller.php';
require_once __DIR__ . '/../app/core/Model.php';

$app = new App();