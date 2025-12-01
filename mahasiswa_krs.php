<?php
session_start();
include 'backend/database.php';

if ($_SESSION['role'] != 'mahasiswa') { header("Location: index.php"); exit(); }

$nim = $_SESSION['nama']; 
$msg = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $semester_aktif = 3;
    $tahun_ajar = "2025/2026";
    
    $query_krs = "INSERT INTO krs (mahasiswa_nim, semester, tahun_akademik, status) VALUES ('$nim', '$semester_aktif', '$tahun_ajar', 'diajukan')";
    if (mysqli_query($koneksi, $query_krs)) {
        $krs_id = mysqli_insert_id($koneksi);
        
        if (!empty($_POST['mk'])) {
            foreach ($_POST['mk'] as $mk_id) {
                mysqli_query($koneksi, "INSERT INTO krs_detail (krs_id, mata_kuliah_id) VALUES ('$krs_id', '$mk_id')");
            }
            $msg = "KRS Berhasil diajukan!";
        }
    } else {
        $msg = "Gagal membuat KRS: " . mysqli_error($koneksi);
    }
}

$matkul = mysqli_query($koneksi, "SELECT * FROM mata_kuliah ORDER BY semester ASC");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <title>Pengisian KRS</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 p-8">
    <div class="max-w-4xl mx-auto bg-white p-6 rounded shadow">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-bold text-gray-800">Kartu Rencana Studi (KRS)</h2>
            <a href="dashboard_mahasiswa.php" class="text-blue-600 hover:underline">&larr; Dashboard</a>
        </div>

        <?php if($msg): ?>
            <div class="bg-green-100 text-green-700 p-3 rounded mb-4"><?= $msg; ?></div>
        <?php endif; ?>

        <form method="POST">
            <div class="mb-4 bg-yellow-50 p-4 border-l-4 border-yellow-400">
                <p class="font-bold">Panduan:</p>
                <p class="text-sm">Centang mata kuliah yang ingin diambil pada semester ini.</p>
            </div>

            <table class="w-full border-collapse border border-gray-300">
                <thead class="bg-gray-800 text-white">
                    <tr>
                        <th class="p-2">Pilih</th>
                        <th class="p-2">Kode</th>
                        <th class="p-2 text-left">Mata Kuliah</th>
                        <th class="p-2">SKS</th>
                        <th class="p-2">Smt</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while($row = mysqli_fetch_assoc($matkul)): ?>
                    <tr class="hover:bg-gray-50 border-b">
                        <td class="text-center p-2">
                            <input type="checkbox" name="mk[]" value="<?= $row['id']; ?>" class="w-5 h-5 text-blue-600">
                        </td>
                        <td class="text-center"><?= $row['kode_mk']; ?></td>
                        <td class="p-2"><?= $row['nama_mk']; ?></td>
                        <td class="text-center"><?= $row['sks']; ?></td>
                        <td class="text-center"><?= $row['semester']; ?></td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>

            <div class="mt-6 text-right">
                <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700 font-bold">
                    Simpan KRS
                </button>
            </div>
        </form>
    </div>
</body>
</html>