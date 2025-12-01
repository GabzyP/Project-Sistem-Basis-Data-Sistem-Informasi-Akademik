<?php
session_start();
include 'backend/database.php';

if ($_SESSION['role'] != 'dosen') { header("Location: index.php"); exit(); }

$msg = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $mhs_nim = $_POST['nim'];
    $mk_id   = $_POST['mk_id'];
    $nilai   = $_POST['nilai']; 

    $query_nilai = "INSERT INTO nilai (mahasiswa_nim, mata_kuliah_id, jenis_nilai, nilai) VALUES ('$mhs_nim', '$mk_id', 'uas', '$nilai')";
    
    if (mysqli_query($koneksi, $query_nilai)) {
        $msg = "Nilai berhasil disimpan!";
    } else {
        $msg = "Gagal: " . mysqli_error($koneksi);
    }
}

$mk_id_aktif = 1; 
$mk_info = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT * FROM mata_kuliah WHERE id='$mk_id_aktif'"));

$query_mhs = "
    SELECT m.nama, m.nim 
    FROM krs_detail kd
    JOIN krs k ON k.id = kd.krs_id
    JOIN mahasiswa m ON m.nim = k.mahasiswa_nim
    WHERE kd.mata_kuliah_id = '$mk_id_aktif'
";
$list_mhs = mysqli_query($koneksi, $query_mhs);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <title>Input Nilai</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 p-8">
    <div class="max-w-4xl mx-auto bg-white p-6 rounded shadow">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-bold text-gray-800">Input Nilai Mahasiswa</h2>
            <a href="dashboard_dosen.php" class="text-blue-600 hover:underline">&larr; Dashboard</a>
        </div>

        <div class="bg-blue-50 p-4 rounded mb-6">
            <p>Mata Kuliah: <b><?= $mk_info['nama_mk']; ?></b> (<?= $mk_info['kode_mk']; ?>)</p>
        </div>

        <?php if($msg): ?> <div class="bg-green-100 text-green-700 p-3 rounded mb-4"><?= $msg; ?></div> <?php endif; ?>

        <table class="w-full border-collapse border border-gray-300">
            <thead class="bg-gray-800 text-white">
                <tr>
                    <th class="p-2">NIM</th>
                    <th class="p-2 text-left">Nama Mahasiswa</th>
                    <th class="p-2">Input Nilai (UAS)</th>
                    <th class="p-2">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                if(mysqli_num_rows($list_mhs) > 0):
                    while($mhs = mysqli_fetch_assoc($list_mhs)): 
                ?>
                <tr class="border-b">
                    <form method="POST">
                        <td class="text-center p-2"><?= $mhs['nim']; ?></td>
                        <td class="p-2"><?= $mhs['nama']; ?></td>
                        <td class="text-center p-2">
                            <input type="hidden" name="nim" value="<?= $mhs['nim']; ?>">
                            <input type="hidden" name="mk_id" value="<?= $mk_id_aktif; ?>">
                            <input type="number" name="nilai" class="border p-1 w-20 text-center rounded" placeholder="0-100" required>
                        </td>
                        <td class="text-center p-2">
                            <button type="submit" class="bg-green-600 text-white px-3 py-1 rounded text-sm hover:bg-green-700">Simpan</button>
                        </td>
                    </form>
                </tr>
                <?php endwhile; 
                else: ?>
                <tr>
                    <td colspan="4" class="p-4 text-center text-gray-500">Belum ada mahasiswa yang mengambil mata kuliah ini di KRS.</td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</body>
</html>