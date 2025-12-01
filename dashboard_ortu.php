<?php
session_start();
include 'backend/database.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

if ($_SESSION['role'] != 'orang_tua') {
    echo "Akses ditolak! Halaman ini khusus Orang Tua.";
    exit();
}

$nama_user = $_SESSION['nama'];
$inisial   = strtoupper(substr($nama_user, 0, 1));
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <title>Dashboard Orang Tua - Satuvokasi</title>
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
                <span>💰</span> <span>Tagihan UKT</span>
            </a>
            <a href="#" class="flex items-center space-x-3 p-3 text-gray-300 hover:bg-[#2E69A3] hover:text-white rounded-lg transition">
                <span>📊</span> <span>Nilai Anak</span>
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
            <h2 class="text-xl font-bold text-gray-800">Dashboard Orang Tua</h2>
            <div class="flex items-center gap-4">
                <span class="text-sm font-bold text-gray-700"><?= $nama_user; ?></span>
                <div class="w-10 h-10 bg-[#2E69A3] rounded-full text-white flex items-center justify-center font-bold">
                    <?= $inisial; ?>
                </div>
            </div>
        </header>

        <main class="p-8 overflow-y-auto">
            <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 mb-6 rounded-r">
                <div class="flex">
                    <div class="ml-3">
                        <p class="text-sm text-yellow-700 font-bold">
                            Informasi Pembayaran
                        </p>
                        <p class="text-sm text-yellow-700 mt-1">
                            Pembayaran UKT Semester Ganjil 2025/2026 untuk anak Anda sudah <b>LUNAS</b>.
                        </p>
                    </div>
                </div>
            </div>

            <div class="bg-white p-6 rounded shadow mb-6">
                <h3 class="font-bold text-lg mb-4 text-[#1F3F66]">Pantauan Akademik</h3>
                
                <div class="flex items-center mb-6">
                    <div class="w-16 h-16 bg-gray-200 rounded-full flex items-center justify-center text-2xl font-bold text-gray-500 mr-4">
                        BS
                    </div>
                    <div>
                        <p class="font-bold text-lg">Budi Santoso</p>
                        <p class="text-sm text-gray-500">Mahasiswa - Semester 3</p>
                    </div>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="border p-4 rounded-lg">
                        <p class="text-sm text-gray-500">Kehadiran Semester Ini</p>
                        <p class="text-xl font-bold text-green-600">95% (Sangat Baik)</p>
                    </div>
                    <div class="border p-4 rounded-lg">
                        <p class="text-sm text-gray-500">IPK Terakhir</p>
                        <p class="text-xl font-bold text-[#1F3F66]">3.45</p>
                    </div>
                </div>
            </div>
        </main>
    </div>
</body>
</html>