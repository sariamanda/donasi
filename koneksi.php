<?php
$dbserver = 'localhost';
$dbname = 'donasi';
$dbuser = 'root';
$dbpassword = '';
$dsn = "mysql:host={$dbserver};dbname={$dbname}";

$connection = null;
try {
    $connection = new PDO($dsn, $dbuser, $dbpassword);
    echo "koneksi sukses";
} catch (Exception $exception) {
    die("Terjadi error: {$exception->getMessage()}");
}
