<?php
$host = "localhost";
$user = "root";
$pass = "";
$db   = "siakad_ti"; 

$koneksi = mysqli_connect($host, $user, $pass, $db);

if (!$koneksi) {
    die("Koneksi Gagal: " . mysqli_connect_error());
}
?>