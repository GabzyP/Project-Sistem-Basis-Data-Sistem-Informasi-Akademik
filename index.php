<?php
session_start();
include 'backend/database.php';

if (isset($_SESSION['user_id'])) {
    header("Location: dashboard.php");
    exit();
}

$error_msg = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = mysqli_real_escape_string($koneksi, $_POST['username']);
    $password = $_POST['password'];
    $role     = $_POST['role']; 

    $query = "SELECT * FROM users WHERE username = '$username' AND role = '$role'";
    $result = mysqli_query($koneksi, $query);
    $user = mysqli_fetch_assoc($result);

    if ($user) {
        if (password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['nama']    = $user['nama_lengkap'] ?? $user['username']; 
            $_SESSION['role']    = $user['role'];

            header("Location: dashboard.php");
            exit();
        } else {
            $error_msg = "Password salah!";
        }
    } else {
        $error_msg = "Akun tidak ditemukan pada role $role!";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masuk - Satuvokasi</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap');
        body { font-family: 'Inter', sans-serif; background: linear-gradient(135deg, #1F3F66 0%, #2E69A3 100%); }
        .tab-active { background-color: #2E69A3; color: white; border-bottom: 3px solid #FFD700; }
        .tab-inactive { color: #4B5563; background-color: #F3F4F6; }
    </style>
</head>
<body class="flex min-h-screen items-center justify-center p-4">
    <div class="w-full max-w-lg bg-white rounded-xl shadow-2xl overflow-hidden">
        <div class="bg-[#1F3F66] p-6 text-white text-center">
            <h1 class="text-2xl font-bold">SIAKAD D3 TI Vokasi</h1>
            <p class="text-xs opacity-80">Sistem Informasi Akademik</p>
        </div>

        <div class="p-8">
            <h2 class="text-xl font-semibold text-gray-800 mb-6 text-center">Masuk ke Akun Anda</h2>
            
            <?php if($error_msg): ?>
                <div class="bg-red-100 text-red-700 p-3 rounded mb-4 text-sm text-center">
                    <?= $error_msg; ?>
                </div>
            <?php endif; ?>

            <form action="" method="POST">
 <div class="flex justify-between mb-6 shadow-sm rounded-lg">
                    <input type="hidden" name="role" id="selected-role" value="mahasiswa">
                    <button type="button" onclick="setRole('mahasiswa', this)" class="role-btn flex-1 py-2 text-xs sm:text-sm font-medium rounded-l-lg tab-active border-r border-gray-200">
                        Mahasiswa
                    </button>
                    <button type="button" onclick="setRole('dosen', this)" class="role-btn flex-1 py-2 text-xs sm:text-sm font-medium tab-inactive border-r border-gray-200">
                        Dosen
                    </button>
                    <button type="button" onclick="setRole('prodi', this)" class="role-btn flex-1 py-2 text-xs sm:text-sm font-medium tab-inactive border-r border-gray-200">
                        Prodi
                    </button>
                    <button type="button" onclick="setRole('orang_tua', this)" class="role-btn flex-1 py-2 text-xs sm:text-sm font-medium rounded-r-lg tab-inactive">
                        Orang Tua
                    </button>
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">Username / NIM</label>
                    <input type="text" name="username" required class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-[#2E69A3] focus:border-[#2E69A3]">
                </div>

                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700">Password</label>
                    <input type="password" name="password" required class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-[#2E69A3] focus:border-[#2E69A3]">
                </div>

                <button type="submit" class="w-full py-2 px-4 rounded-lg text-white bg-[#2E69A3] hover:bg-[#1F3F66] transition">Masuk</button>
                <div class="mt-4 text-center">
                    <p class="text-sm text-gray-600">Belum punya akun? 
                        <a href="register.php" class="font-medium text-[#2E69A3] hover:underline">Daftar disini</a>
                    </p>
                </div>
            </form>
        </div>
    </div>

    <script>
        function setRole(role, btnElement) {
            document.getElementById('selected-role').value = role;
            document.querySelectorAll('.role-btn').forEach(btn => {
                btn.classList.remove('tab-active');
                btn.classList.add('tab-inactive');
            });
            btnElement.classList.remove('tab-inactive');
            btnElement.classList.add('tab-active');
        }
    </script>
</body>
</html>