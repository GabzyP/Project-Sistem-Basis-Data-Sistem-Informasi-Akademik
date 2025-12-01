<?php
session_start();
include 'backend/database.php'; 

$success_msg = "";
$error_msg = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $username = mysqli_real_escape_string($koneksi, trim($_POST['username']));
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $role     = $_POST['role'];
    $nama     = mysqli_real_escape_string($koneksi, trim($_POST['nama']));

    $cek_user = mysqli_query($koneksi, "SELECT * FROM users WHERE username = '$username'");
    if (mysqli_num_rows($cek_user) > 0) {
        $error_msg = "Username/ID sudah terdaftar!";
    } else {

        $query_user = "INSERT INTO users (username, password, role) VALUES ('$username', '$password', '$role')";
        
        if (mysqli_query($koneksi, $query_user)) {
            $user_id = mysqli_insert_id($koneksi); 
            $query_profil = "";

            if ($role == 'mahasiswa') {
                $query_profil = "INSERT INTO mahasiswa (nim, user_id, nama) VALUES ('$username', '$user_id', '$nama')";
            } elseif ($role == 'dosen') {
                 $query_profil = "INSERT INTO dosen (nidn, user_id, nama) VALUES ('$username', '$user_id', '$nama')";
            } elseif ($role == 'orang_tua') {
                 $query_profil = "INSERT INTO orang_tua (user_id, nama) VALUES ('$user_id', '$nama')";
            } 

            if (!empty($query_profil)) {
                mysqli_query($koneksi, $query_profil);
            }
            
            $success_msg = "Akun berhasil dibuat! Silakan Login.";
        } else {
            $error_msg = "Gagal membuat akun: " . mysqli_error($koneksi);
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Akun Baru - Satuvokasi</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap');
        body { font-family: 'Inter', sans-serif; background: #f3f4f6; }
    </style>
</head>
<body class="flex min-h-screen items-center justify-center p-4">

    <div class="w-full max-w-lg bg-white rounded-xl shadow-lg overflow-hidden">
        <div class="bg-[#2E69A3] p-6 text-white text-center">
            <h1 class="text-xl font-bold">Pendaftaran Akun Baru</h1>
            <p class="text-xs opacity-80">Tambahkan User ke Database</p>
        </div>

        <div class="p-8">
            <?php if($success_msg): ?>
                <div class="bg-green-100 text-green-700 p-3 rounded mb-4 text-sm text-center">
                    <?= $success_msg; ?> <br>
                    <a href="index.php" class="font-bold underline">Klik disini untuk Login</a>
                </div>
            <?php endif; ?>

            <?php if($error_msg): ?>
                <div class="bg-red-100 text-red-700 p-3 rounded mb-4 text-sm text-center">
                    <?= $error_msg; ?>
                </div>
            <?php endif; ?>

            <form action="" method="POST">
                
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Pilih Role</label>
                    <select name="role" class="w-full p-2 border border-gray-300 rounded focus:ring-[#2E69A3] focus:border-[#2E69A3]">
                        <option value="mahasiswa">Mahasiswa</option>
                        <option value="dosen">Dosen</option>
                        <option value="prodi">Program Studi</option>
                        <option value="orang_tua">Orang Tua</option>
                    </select>
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nama Lengkap</label>
                    <input type="text" name="nama" required class="w-full p-2 border border-gray-300 rounded focus:ring-[#2E69A3] focus:border-[#2E69A3]" placeholder="Contoh: Budi Santoso">
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Username / NIM / NIDN</label>
                    <input type="text" name="username" required class="w-full p-2 border border-gray-300 rounded focus:ring-[#2E69A3] focus:border-[#2E69A3]" placeholder="ID Unik Login">
                </div>

                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                    <input type="password" name="password" required class="w-full p-2 border border-gray-300 rounded focus:ring-[#2E69A3] focus:border-[#2E69A3]" placeholder="******">
                </div>

                <button type="submit" class="w-full py-2 px-4 rounded-lg text-white bg-[#1F3F66] hover:bg-[#2E69A3] transition">Buat Akun</button>
                
                <div class="mt-4 text-center">
                    <a href="index.php" class="text-sm text-gray-600 hover:text-[#2E69A3]">Kembali ke Login</a>
                </div>
            </form>
        </div>
    </div>

</body>
</html>