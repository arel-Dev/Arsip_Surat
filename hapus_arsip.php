<?php
session_start(); 


if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== TRUE || $_SESSION['level'] !== 'admin') {
    header("Location: unauthorized.php"); 
    exit();
}

error_reporting(E_ALL); 
ini_set('display_errors', 1); 

$vnama_pengirim = '';

error_reporting(E_ALL);
ini_set('display_errors', 1);

if (isset($_GET['id']) && !empty($_GET['id'])) {
    $id_hapus = mysqli_real_escape_string($koneksi, $_GET['id']);

    $query_get_file = mysqli_query($koneksi, "SELECT file FROM tbl_arsip WHERE id_arsip = '$id_hapus'");
    $data_file = mysqli_fetch_assoc($query_get_file);
    $nama_file_lama = $data_file['file'] ?? ''; 

    $hapus = mysqli_query($koneksi, "DELETE FROM tbl_arsip WHERE id_arsip = '$id_hapus'");

    if ($hapus) {
        if (!empty($nama_file_lama) && file_exists("file_arsip/" . $nama_file_lama)) {
            unlink("file_arsip/" . $nama_file_lama);
        }
        echo "<script>alert('Hapus Data Berhasil!');</script>";
        echo "<script>document.location='?halaman=arsip_surat';</script>";
    } else {
        echo "<script>alert('Hapus Data Gagal: " . mysqli_error($koneksi) . "');</script>";
        echo "<script>document.location='?halaman=arsip_surat';</script>"; 
    }
} else {
    echo "<script>alert('ID Arsip tidak ditemukan untuk dihapus!');</script>";
    echo "<script>document.location='?halaman=arsip_surat';</script>"; 
}
?>