<?php
$password_asli = "123456";
$hash_baru = password_hash($password_asli, PASSWORD_DEFAULT);

echo "<h3>Password: $password_asli</h3>";
echo "<h3>Hash Baru (Copy kode di bawah ini):</h3>";
echo "<textarea rows='3' cols='60'>$hash_baru</textarea>";
?>