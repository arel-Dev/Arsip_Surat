<?php
session_start(); 


if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== TRUE) {
    header("Location: login.php");
    exit();
}


if ($_SESSION['level'] !== 'admin') {
    header("Location: unauthorized.php"); 
    exit();
}

include "config/koneksi.php";
include "template/header.php";
include "content.php";
include "template/footer.php";
?>