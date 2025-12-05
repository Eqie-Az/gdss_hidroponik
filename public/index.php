<?php
// Tentukan BASEURL sesuai environment (Laragon/Localhost)
define('BASEURL', 'http://gdss-hidroponik.test');

ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/../app/core/App.php';
require_once __DIR__ . '/../app/core/Controller.php';
require_once __DIR__ . '/../app/core/Model.php';

$app = new App();