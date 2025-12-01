<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

$role = $_SESSION['role'];

switch ($role) {
    case 'mahasiswa':
        header("Location: dashboard_mahasiswa.php");
        break;
    case 'dosen':
        header("Location: dashboard_dosen.php");
        break;
    case 'prodi':
        header("Location: dashboard_prodi.php");
        break;
    case 'orang_tua':
        header("Location: dashboard_ortu.php");
        break;
    default:
        echo "Role tidak valid!";
        session_destroy();
        exit();
}
?>