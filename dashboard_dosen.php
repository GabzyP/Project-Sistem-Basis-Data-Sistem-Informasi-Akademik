<?php
session_start();
include 'backend/database.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

if ($_SESSION['role'] != 'dosen') {
    echo "Akses ditolak! Halaman ini khusus Dosen.";
    exit();
}

$nama_user = $_SESSION['nama'];
$inisial   = strtoupper(substr($nama_user, 0, 1));
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <title>Dashboard Dosen - Satuvokasi</title>
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
                <span>📅</span> <span>Jadwal Mengajar</span>
            </a>
            <a href="dosen_nilai.php" class="flex items-center space-x-3 p-3 text-gray-300 hover:bg-[#2E69A3] hover:text-white rounded-lg transition">
                <span>✍️</span> <span>Input Nilai</span>
            </a>
            <a href="#" class="flex items-center space-x-3 p-3 text-gray-300 hover:bg-[#2E69A3] hover:text-white rounded-lg transition">
                <span>👥</span> <span>Mahasiswa Wali</span>
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
            <h2 class="text-xl font-bold text-gray-800">Dashboard Dosen</h2>
            <div class="flex items-center gap-4">
                <span class="text-sm font-bold text-gray-700"><?= $nama_user; ?></span>
                <div class="w-10 h-10 bg-[#2E69A3] rounded-full text-white flex items-center justify-center font-bold">
                    <?= $inisial; ?>
                </div>
            </div>
        </header>

        <main class="p-8 overflow-y-auto">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                <div class="bg-white p-6 rounded-lg shadow-md border-l-4 border-[#2E69A3]">
                    <p class="text-sm text-gray-500">Mata Kuliah Diampu</p>
                    <p class="text-3xl font-bold text-[#1F3F66]">3 Matkul</p>
                    <button class="mt-2 text-sm text-[#2E69A3] underline">Lihat Detail</button>
                </div>
                <div class="bg-white p-6 rounded-lg shadow-md border-l-4 border-green-500">
                    <p class="text-sm text-gray-500">Mahasiswa Bimbingan</p>
                    <p class="text-3xl font-bold text-[#1F3F66]">12 Org</p>
                    <button class="mt-2 text-sm text-[#2E69A3] underline">Cek Progress</button>
                </div>
            </div>

            <div class="bg-white p-6 rounded shadow">
                <h3 class="font-bold text-lg mb-4">Aksi Cepat</h3>
                <div class="grid grid-cols-2 gap-4">
                    <a href="#" class="p-4 bg-gray-50 rounded hover:bg-gray-100 text-center border transition">
                        <span class="block text-2xl mb-2">📝</span>
                        <span class="text-sm font-bold text-gray-700">Input Nilai Semester</span>
                    </a>
                    <a href="#" class="p-4 bg-gray-50 rounded hover:bg-gray-100 text-center border transition">
                        <span class="block text-2xl mb-2">✅</span>
                        <span class="text-sm font-bold text-gray-700">Validasi KRS Mahasiswa</span>
                    </a>
                </div>
            </div>
        </main>
    </div>
</body>
</html>