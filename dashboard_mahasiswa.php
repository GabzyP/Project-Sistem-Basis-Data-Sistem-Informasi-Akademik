<?php

session_start();

// Ganti 'backend/database.php' dengan path yang benar jika berbeda
include 'backend/database.php'; 

// --- PERBAIKAN SESI KEAMANAN (PENTING!) ---
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'mahasiswa') {
    header("Location: index.php"); 
    exit();
}
// Pengecekan Kunci 'nim'
if (!isset($_SESSION['nim'])) {
    session_unset();
    session_destroy();
    header("Location: index.php");
    exit();
}
// --- END PERBAIKAN SESI ---

$nama_user = $_SESSION['nama'];
$inisial = strtoupper(substr($nama_user, 0, 1));
$nim_mahasiswa = $_SESSION['nim']; 

// Include file service
require_once 'backend/dashboard_service.php'; 

// Panggil semua fungsi yang diperlukan
$data_overview = getMahasiswaDashboardData($nim_mahasiswa); 
$unsubmitted_tasks = getUnsubmittedTasks($nim_mahasiswa);

// Data placeholder
$total_sks_diambil = 21; 
$ipk_semester = 3.85;

?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Mahasiswa | SATUVOKASI</title>
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
                <span class="text-sm font-bold text-gray-700"><?= htmlspecialchars($nama_user); ?></span>
                <div class="w-10 h-10 bg-[#2E69A3] rounded-full text-white flex items-center justify-center font-bold">
                    <?= htmlspecialchars($inisial); ?>
                </div>
            </div>
        </header>
        <main class="p-8 overflow-y-auto space-y-8">
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="bg-white p-6 rounded-lg shadow-md border-b-4 border-[#2E69A3]">
                    <p class="text-sm text-gray-500">IPK Semester Ini</p>
                    <p class="text-4xl font-extrabold text-[#1F3F66] mt-1"><?= $ipk_semester ?></p>
                    <p class="text-xs text-green-500 mt-2">Target Tercapai!</p>
                </div>
                <div class="bg-white p-6 rounded-lg shadow-md border-b-4 border-[#FFD700]">
                    <p class="text-sm text-gray-500">Total SKS</p>
                    <p class="text-4xl font-extrabold text-[#1F3F66] mt-1"><?= $total_sks_diambil ?></p>
                    <p class="text-xs text-gray-500 mt-2">Termasuk mata kuliah pilihan</p>
                </div>
                <div class="bg-white p-6 rounded-lg shadow-md border-b-4 border-red-500">
                    <p class="text-sm text-gray-500">Tagihan UKT</p>
                    <p class="text-2xl font-bold text-[#1F3F66] mt-1">Lunas</p>
                </div>
            </div>
            <div class="bg-white p-6 rounded-lg shadow-md border-l-4 border-red-500">
                <h3 class="text-xl font-bold text-gray-800 mb-4 border-b pb-2 flex justify-between items-center">
                    🔔 Tugas Mendekati Deadline
                    <span class="text-sm font-normal text-red-600"><?= count($unsubmitted_tasks) ?> Tugas Belum Dikumpulkan</span>
                </h3>

                <?php if (empty($unsubmitted_tasks)): ?>
                    <div class="bg-green-50 border-l-4 border-green-400 p-4">
                        <p class="text-sm text-green-700">Tidak ada tugas yang tertunda! Kerja bagus.</p>
                    </div>
                <?php else: ?>
                    <div class="space-y-3">
                        <?php foreach ($unsubmitted_tasks as $tugas): ?>
                            <div class="p-3 bg-red-50 rounded-md border border-red-200 flex justify-between items-center">
                                <div>
                                    <p class="font-semibold text-red-800"><?= htmlspecialchars($tugas->judul_tugas) ?></p>
                                    <p class="text-xs text-gray-600">Mata Kuliah: <?= htmlspecialchars($tugas->nama_mk) ?></p>
                                </div>
                                <div class="text-right">
                                    <p class="text-sm font-bold <?= $tugas->hari_tersisa <= 1 ? 'text-red-600' : 'text-orange-600' ?>">
                                        <?= $tugas->hari_tersisa < 0 ? 'TERLEWAT!' : ($tugas->hari_tersisa == 0 ? 'HARI INI!' : $tugas->hari_tersisa . ' Hari Lagi') ?>
                                    </p>
                                    <p class="text-xs text-gray-500">Deadline: <?= date('d M Y H:i', strtotime($tugas->deadline)) ?></p>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
            <div class="bg-white p-6 rounded-lg shadow-md"> 
                <h3 class="text-xl font-bold text-gray-800 mb-4 border-b pb-2">Overview Nilai & Kehadiran Semester Aktif</h3>
                
                <?php if (empty($data_overview)): ?>
                    <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4">
                        <p class="text-sm text-yellow-700">Tidak ada mata kuliah aktif yang disetujui untuk semester ini, atau data tidak tersedia.</p>
                    </div>
                <?php else: ?>
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kode MK</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Mata Kuliah</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">SKS</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kehadiran (%)</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nilai Sementara</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <?php foreach ($data_overview as $mk): ?>
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900"><?= htmlspecialchars($mk->kode_mk) ?></td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?= htmlspecialchars($mk->nama_mk) ?></td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?= $mk->sks ?></td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?= $mk->persentase_kehadiran ?>% (<?= $mk->total_hadir ?>/<?= $mk->total_sesi ?>)</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 font-bold"><?= $mk->rata_rata_nilai_sementara ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                <?php endif; ?>
            </div>
            </main>
        </div>
    </body>
</html>