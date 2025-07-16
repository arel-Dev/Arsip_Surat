<?php

       @$halaman = $_GET['halaman'];
       if($halaman == "departemen")
       {
              //echo "Tampil Halaman Modul Departemen";
              include "modul/departemen/departemen.php";
       }
       elseif ($halaman == "pengirim_surat") {
              include "modul/Pengirim_surat/Pengirim_surat.php"; // Ini akan menampilkan data dan form Pengirim
       }
      elseif ($halaman == "arsip_surat") {

             if(isset($_GET['hal'])){
                if($_GET['hal'] == "tambahdata"){
                    // Jika aksi adalah 'tambahdata', include form untuk TAMBAH ARSIP
                    include "modul/arsip/tambah_arsip.php"; // <-- Pastikan ini mengarah ke file baru
                } elseif ($_GET['hal'] == "edit"){
                    // Jika aksi adalah 'edit', include form untuk EDIT ARSIP
                    include "modul/arsip/edit_arsip.php"; // <-- Anda perlu membuat file ini
                } elseif ($_GET['hal'] == "hapus"){
                    // Jika aksi adalah 'hapus', include file untuk HAPUS ARSIP
                    include "modul/arsip/hapus_arsip.php"; // <-- Anda perlu membuat file ini
                } else {
                    // Jika 'hal' ada tapi tidak cocok dengan aksi di atas, tampilkan data arsip
                    include "modul/arsip/data.php";
                }
             } else {
                // Jika tidak ada parameter 'hal', tampilkan data arsip
                include "modul/arsip/data.php";
             }
       }
       else
       {
              //echo "Tampil Halaman Home";
              include "modul/home.php";
       }
?>