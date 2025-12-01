<?php
session_start();
include 'backend/database.php';

if ($_SESSION['role'] != 'prodi') { header("Location: index.php"); exit(); }

$query = "SELECT * FROM mata_kuliah";
$result = mysqli_query($koneksi, $query);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <title>Manajemen Mata Kuliah</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 p-8">
    <div class="max-w-4xl mx-auto bg-white p-6 rounded shadow">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-bold text-gray-800">Manajemen Mata Kuliah</h2>
            <a href="dashboard_prodi.php" class="text-blue-600 hover:underline">&larr; Kembali ke Dashboard</a>
        </div>

        <table class="w-full border-collapse border border-gray-300">
            <thead>
                <tr class="bg-gray-200">
                    <th class="border p-2">Kode MK</th>
                    <th class="border p-2">Nama Mata Kuliah</th>
                    <th class="border p-2">SKS</th>
                    <th class="border p-2">Semester</th>
                </tr>
            </thead>
            <tbody>
                <?php while($row = mysqli_fetch_assoc($result)): ?>
                <tr class="text-center">
                    <td class="border p-2"><?= $row['kode_mk']; ?></td>
                    <td class="border p-2 text-left"><?= $row['nama_mk']; ?></td>
                    <td class="border p-2"><?= $row['sks']; ?></td>
                    <td class="border p-2"><?= $row['semester']; ?></td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
        <div class="mt-4 p-4 bg-blue-50 text-blue-800 text-sm rounded">
            * Fitur Tambah/Edit/Hapus dalam proses.
        </div>
    </div>
</body>
</html>