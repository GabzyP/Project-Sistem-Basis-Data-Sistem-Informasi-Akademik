<?php
session_start();
include 'backend/database.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

if ($_SESSION['role'] != 'prodi') {
    echo "Akses ditolak! Halaman ini khusus Admin Prodi.";
    exit();
}

$nama_user = $_SESSION['nama'];
$inisial   = strtoupper(substr($nama_user, 0, 1));

$total_mhs   = mysqli_num_rows(mysqli_query($koneksi, "SELECT * FROM users WHERE role='mahasiswa'"));
$total_dosen = mysqli_num_rows(mysqli_query($koneksi, "SELECT * FROM users WHERE role='dosen'"));
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <title>Dashboard Prodi - Satuvokasi</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap');
        body { font-family: 'Inter', sans-serif; background-color: #f3f4f6; }
    </style>
</head>
<body class="flex h-screen overflow-hidden">

    <div class="w-64 bg-[#1F3F66] text-white flex flex-col shadow-xl">
        <div class="h-16 flex items-center justify-center font-bold text-xl border-b border-[#2E69A3]">
            SATUVOKASI
        </div>
        <nav class="flex-1 p-4 space-y-2">
            <a href="#" class="flex items-center space-x-3 p-3 bg-[#2E69A3] rounded-lg text-white">
                <span>🏠</span> <span class="font-medium">Dashboard</span>
            </a>
            <a href="#" class="flex items-center space-x-3 p-3 text-gray-300 hover:bg-[#2E69A3] hover:text-white rounded-lg transition">
                <span>👥</span> <span>Data Mahasiswa</span>
            </a>
            <a href="#" class="flex items-center space-x-3 p-3 text-gray-300 hover:bg-[#2E69A3] hover:text-white rounded-lg transition">
                <span>👨‍🏫</span> <span>Data Dosen</span>
            </a>
            <a href="#" class="flex items-center space-x-3 p-3 text-gray-300 hover:bg-[#2E69A3] hover:text-white rounded-lg transition">
                <span>📚</span> <span>Kurikulum</span>
            </a>
        </nav>
        <div class="p-4 border-t border-[#2E69A3]">
            <a href="logout.php" onclick="return confirm('Keluar dari sistem?')" class="flex items-center space-x-3 p-3 text-red-300 hover:bg-red-600 hover:text-white rounded-lg transition">
                <span>🚪</span> <span>Keluar</span>
            </a>
        </div>
    </div>

    <div class="flex-1 flex flex-col overflow-hidden">
        <header class="h-16 bg-white shadow-sm flex items-center justify-between px-8">
            <h2 class="text-xl font-bold text-gray-800">Admin Program Studi</h2>
            <div class="flex items-center gap-4">
                <span class="text-sm font-bold text-gray-700"><?= $nama_user; ?></span>
                <div class="w-10 h-10 bg-[#2E69A3] rounded-full text-white flex items-center justify-center font-bold">
                    <?= $inisial; ?>
                </div>
            </div>
        </header>

        <main class="p-8 overflow-y-auto">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <div class="bg-[#1F3F66] text-white p-6 rounded-lg shadow-md">
                    <p class="text-sm opacity-80">Total Mahasiswa</p>
                    <p class="text-4xl font-bold mt-1"><?= $total_mhs; ?></p>
                    <p class="text-xs mt-2 opacity-60">Terdaftar di sistem</p>
                </div>
                <div class="bg-[#2E69A3] text-white p-6 rounded-lg shadow-md">
                    <p class="text-sm opacity-80">Total Dosen</p>
                    <p class="text-4xl font-bold mt-1"><?= $total_dosen; ?></p>
                    <p class="text-xs mt-2 opacity-60">Aktif mengajar</p>
                </div>
                <div class="bg-white p-6 rounded-lg shadow-md border border-gray-200">
                    <p class="text-sm text-gray-500">Tahun Akademik</p>
                    <p class="text-2xl font-bold text-[#1F3F66] mt-1">2025/2026</p>
                    <span class="bg-green-100 text-green-800 text-xs px-2 py-1 rounded">Semester Ganjil</span>
                </div>
            </div>

            <div class="bg-white p-6 rounded shadow">
                <h3 class="font-bold text-lg mb-4">Panel Administrasi</h3>
                <ul class="space-y-2">
                    <li><a href="register.php" class="block p-3 bg-gray-50 hover:bg-gray-100 rounded flex justify-between group">
                        <span class="font-medium group-hover:text-[#2E69A3]">➕ Tambah User Baru</span> 
                        <span>&rarr;</span>
                    </a></li>
                    <li><a href="prodi_matakuliah.php" class="block p-3 bg-gray-50 hover:bg-gray-100 rounded flex justify-between group">
                        <span class="font-medium group-hover:text-[#2E69A3]">📅 Atur Jadwal Kuliah</span> 
                        <span>&rarr;</span>
                    </a></li>
                </ul>
            </div>
        </main>
    </div>
</body>
</html>