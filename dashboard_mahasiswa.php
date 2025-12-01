<?php
session_start();
include 'backend/database.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

if ($_SESSION['role'] != 'mahasiswa') {
    echo "Anda tidak memiliki akses ke halaman ini!";
    exit();
}

$nama_user = $_SESSION['nama'];
$inisial = strtoupper(substr($nama_user, 0, 1));
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <title>Dashboard Mahasiswa</title>
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
            <a href="mahasiswa_krs.php" class="flex items-center space-x-3 p-3 text-gray-300 hover:bg-[#2E69A3] hover:text-white rounded-lg transition">
                <span>📚</span> <span>KRS & KHS</span>
            </a>
            <a href="#" class="flex items-center space-x-3 p-3 text-gray-300 hover:bg-[#2E69A3] hover:text-white rounded-lg transition">
                <span>📝</span> <span>Tugas & Quiz</span>
            </a>
        </nav>
        <div class="p-4 border-t border-[#2E69A3]">
            <a href="logout.php" class="flex items-center space-x-3 p-3 text-red-300 hover:bg-red-600 hover:text-white rounded-lg transition">
                <span>🚪</span> <span>Keluar</span>
            </a>
        </div>
    </div>

    <div class="flex-1 flex flex-col overflow-hidden">
        <header class="h-16 bg-white shadow-sm flex items-center justify-between px-8">
            <h2 class="text-xl font-bold text-gray-800">Dashboard Mahasiswa</h2>
            <div class="flex items-center gap-4">
                <span class="text-sm font-bold text-gray-700"><?= $nama_user; ?></span>
                <div class="w-10 h-10 bg-[#2E69A3] rounded-full text-white flex items-center justify-center font-bold">
                    <?= $inisial; ?>
                </div>
            </div>
        </header>

        <main class="p-8 overflow-y-auto">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <div class="bg-white p-6 rounded-lg shadow-md border-b-4 border-[#2E69A3]">
                    <p class="text-sm text-gray-500">IPK Semester Ini</p>
                    <p class="text-4xl font-extrabold text-[#1F3F66] mt-1">3.85</p>
                    <p class="text-xs text-green-500 mt-2">Target Tercapai!</p>
                </div>
                <div class="bg-white p-6 rounded-lg shadow-md border-b-4 border-[#FFD700]">
                    <p class="text-sm text-gray-500">Total SKS</p>
                    <p class="text-4xl font-extrabold text-[#1F3F66] mt-1">21</p>
                </div>
                <div class="bg-white p-6 rounded-lg shadow-md border-b-4 border-red-500">
                    <p class="text-sm text-gray-500">Tagihan UKT</p>
                    <p class="text-2xl font-bold text-[#1F3F66] mt-1">Lunas</p>
                </div>
            </div>
        </main>
    </div>
</body>
</html>