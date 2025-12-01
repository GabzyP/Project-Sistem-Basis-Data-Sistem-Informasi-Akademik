<?php
// Pastikan file ini berada di 'backend/dashboard_service.php'

// Asumsi koneksi database tersedia secara global dari file database.php
// (File database.php harus di-include di dashboard_mahasiswa.php)

// Fungsi untuk Overview Nilai & Kehadiran (Sudah diuji)
function getMahasiswaDashboardData($nim) {
    global $conn; 
    
    // Pastikan koneksi tersedia
    if (!isset($conn) || $conn === null) {
        error_log("Koneksi database (\$conn) gagal diinisialisasi di getMahasiswaDashboardData.");
        return [];
    }

    // 1. Tentukan KRS ID terbaru yang disetujui
    $stmt_krs = $conn->prepare("
        SELECT id
        FROM krs
        WHERE mahasiswa_nim = ? AND status = 'disetujui'
        ORDER BY tahun_akademik DESC, semester DESC
        LIMIT 1
    ");
    $stmt_krs->bind_param("s", $nim);
    $stmt_krs->execute();
    $result_krs = $stmt_krs->get_result();
    
    if ($result_krs->num_rows === 0) {
        return [];
    }
    
    $krs_row = $result_krs->fetch_assoc();
    $krs_id_terbaru = $krs_row['id'];

    // 2. Query Utama untuk Nilai & Kehadiran
    $query = "
        SELECT
            mk.kode_mk,
            mk.nama_mk,
            mk.sks,
            COUNT(DISTINCT a.tanggal) AS total_sesi,
            SUM(CASE WHEN a.status = 'hadir' THEN 1 ELSE 0 END) AS total_hadir,
            FORMAT((SUM(CASE WHEN a.status = 'hadir' THEN 1 ELSE 0 END) * 100.0 / NULLIF(COUNT(DISTINCT a.tanggal), 0)), 2) AS persentase_kehadiran,
            FORMAT(AVG(n.nilai), 2) AS rata_rata_nilai_sementara
        FROM krs k
        JOIN krs_detail kd ON k.id = kd.krs_id
        JOIN mata_kuliah mk ON kd.mata_kuliah_id = mk.id
        LEFT JOIN absensi a ON mk.id = a.mata_kuliah_id AND a.mahasiswa_nim = k.mahasiswa_nim
        LEFT JOIN nilai n ON mk.id = n.mata_kuliah_id AND n.mahasiswa_nim = k.mahasiswa_nim
        WHERE k.id = ?
        GROUP BY mk.kode_mk, mk.nama_mk, mk.sks
        ORDER BY mk.nama_mk;
    ";

    $stmt_overview = $conn->prepare($query);
    $stmt_overview->bind_param("i", $krs_id_terbaru);
    $stmt_overview->execute();
    $result_overview = $stmt_overview->get_result();
    
    $data_overview = [];
    while ($row = $result_overview->fetch_object()) {
        $data_overview[] = $row;
    }
    
    return $data_overview;
}


// Fungsi Baru untuk Notifikasi Tugas yang Belum Dikumpulkan
function getUnsubmittedTasks($nim) {
    global $conn; 
    
    if (!isset($conn) || $conn === null) {
        error_log("Koneksi database (\$conn) gagal diinisialisasi di getUnsubmittedTasks.");
        return [];
    }

    // Query untuk mengambil semua tugas yang belum disubmit oleh mahasiswa di KRS aktif
    $query = "
        SELECT
            t.judul AS judul_tugas,
            mk.nama_mk,
            t.deadline,
            DATEDIFF(t.deadline, NOW()) AS hari_tersisa
        FROM tugas t
        JOIN mata_kuliah mk ON t.mata_kuliah_id = mk.id
        
        -- Join ke KRS Detail untuk memastikan tugas ini relevan dengan mata kuliah yang diambil
        JOIN krs_detail kd ON mk.id = kd.mata_kuliah_id
        JOIN krs k ON kd.krs_id = k.id
        
        -- Mencari TUGAS yang TIDAK memiliki SUBMISSION dari mahasiswa ini
        LEFT JOIN submission_tugas st 
            ON st.tugas_id = t.id AND st.mahasiswa_nim = ? 
        
        WHERE 
            k.mahasiswa_nim = ? 
            AND k.status = 'disetujui'
            AND st.id IS NULL -- Kunci: Hanya tampilkan jika TIDAK ada submission (st.id NULL)
            AND t.deadline >= NOW() -- Opsional: Hanya tampilkan tugas yang deadline-nya belum terlewat
        
        GROUP BY t.id, t.judul, mk.nama_mk, t.deadline
        ORDER BY t.deadline ASC;
    ";

    $stmt_tasks = $conn->prepare($query);
    $stmt_tasks->bind_param("ss", $nim, $nim); // NIM digunakan dua kali untuk filter
    $stmt_tasks->execute();
    $result_tasks = $stmt_tasks->get_result();
    
    $data_tasks = [];
    while ($row = $result_tasks->fetch_object()) {
        $data_tasks[] = $row;
    }
    
    return $data_tasks;
}

?>