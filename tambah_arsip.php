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


$query_departemen = mysqli_query($koneksi, "SELECT id_departemen, nama_departemen FROM tbl_departemen ORDER BY nama_departemen ASC");
if (!$query_departemen) {
    die("Query error untuk departemen: " . mysqli_error($koneksi));
}

$query_pengirim = mysqli_query($koneksi, "SELECT id_pengiriman_surat, nama_pengirim, no_hp FROM tbl_pengiriman_surat ORDER BY nama_pengirim ASC");
if (!$query_pengirim) {
    die("Query error untuk pengirim: " . mysqli_error($koneksi));
}


if (isset($_POST['simpan'])) {

    $no_surat         = mysqli_real_escape_string($koneksi, $_POST['no_surat']);
    $tanggal_surat    = mysqli_real_escape_string($koneksi, $_POST['tanggal_surat']);
    $tanggal_diterima = mysqli_real_escape_string($koneksi, $_POST['tanggal_diterima']);
    $prihal           = mysqli_real_escape_string($koneksi, $_POST['prihal']);
    $id_departemen    = mysqli_real_escape_string($koneksi, $_POST['id_departemen']);
    $id_pengirim      = mysqli_real_escape_string($koneksi, $_POST['id_pengirim']);

    $nama_file = ""; 

 
    if (isset($_FILES['file']) && $_FILES['file']['error'] == UPLOAD_ERR_OK) {
      
        $target_dir = __DIR__ . '/../../file_arsip/';

  
        if (!is_dir($target_dir)) {
            mkdir($target_dir, 0777, true); 
        }

     
        if (!is_writable($target_dir)) {
            echo "<script>alert('Error: Folder tujuan tidak memiliki izin tulis. Mohon periksa izin folder " . htmlspecialchars($target_dir) . "');</script>";
            $uploadOk = 0; 
        } else {
            $uploadOk = 1; 
        }

        $nama_file_asli = basename($_FILES["file"]["name"]);
        $ekstensi_file = strtolower(pathinfo($nama_file_asli, PATHINFO_EXTENSION));
        $nama_file_unik = uniqid('file_') . '.' . $ekstensi_file; 
        $target_file = $target_dir . $nama_file_unik;

        
        if ($_FILES["file"]["size"] > 5000000) {
            echo "<script>alert('Maaf, ukuran file terlalu besar (Maks 5MB).');</script>";
            $uploadOk = 0;
        }

        
        $allowed_types = array("pdf", "jpg", "jpeg", "png", "doc", "docx", "xls", "xlsx");
        if (!in_array($ekstensi_file, $allowed_types)) {
            echo "<script>alert('Maaf, hanya format PDF, JPG, JPEG, PNG, DOC, DOCX, XLS, XLSX yang diizinkan.');</script>";
            $uploadOk = 0;
        }

        if ($uploadOk == 0) {
            echo "<script>alert('Maaf, file Anda tidak terunggah.');</script>";
            $nama_file = ""; // Reset nama file jika gagal upload
        } else {
            if (move_uploaded_file($_FILES["file"]["tmp_name"], $target_file)) {
                $nama_file = $nama_file_unik; // Gunakan nama file unik yang berhasil diunggah
            } else {
                // Pesan error lebih spesifik jika move_uploaded_file gagal
                echo "<script>alert('Maaf, ada error saat mengunggah file Anda. Pastikan folder " . htmlspecialchars($target_dir) . " ada dan memiliki izin tulis.');</script>";
                $nama_file = ""; // Reset nama file jika gagal upload
            }
        }
    }
    // === AKHIR BAGIAN PENTING UNTUK UPLOAD FILE ===

    // Kueri INSERT menggunakan Prepared Statements untuk keamanan
    $query_insert = "INSERT INTO tbl_arsip (no_surat, tanggal_surat, tanggal_diterima, prihal, id_departemen, id_pengirim, file)
                     VALUES (?, ?, ?, ?, ?, ?, ?)";

    $stmt = mysqli_prepare($koneksi, $query_insert);

    if ($stmt) {
        // 'ssssiis' -> s=string, i=integer (sesuai tipe data kolom Anda)
        mysqli_stmt_bind_param($stmt, "ssssiis", $no_surat, $tanggal_surat, $tanggal_diterima, $prihal, $id_departemen, $id_pengirim, $nama_file);
        $simpan = mysqli_stmt_execute($stmt);

        if ($simpan) {
            echo "<script>alert('Data arsip berhasil disimpan!');</script>";
            echo "<script>document.location='?halaman=arsip_surat';</script>"; // Redirect ke halaman tampilan arsip
        } else {
            echo "<script>alert('Data arsip gagal disimpan: " . mysqli_error($koneksi) . "');</script>";
        }
        mysqli_stmt_close($stmt);
    } else {
        echo "<script>alert('Prepare statement gagal: " . mysqli_error($koneksi) . "');</script>";
    }
}
?>

<!-- STYLE CSS DASAR UNTUK MERAPIKAN FORM TANPA BOOTSTRAP -->
<style>
    body {
        font-family: Arial, sans-serif;
        margin: 20px;
        background-color: #f4f4f4;
    }
    .container-form {
        background-color: #fff;
        padding: 25px;
        border-radius: 8px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        max-width: 600px; /* Lebar maksimum form */
        margin: 20px auto; /* Tengahkan form */
    }
    .form-header {
        background-color: #17a2b8; /* Warna info */
        color: white;
        padding: 15px;
        border-radius: 8px 8px 0 0;
        margin: -25px -25px 20px -25px; /* Sesuaikan margin untuk header */
        font-size: 1.2em;
        font-weight: bold;
        text-align: center;
    }
    .form-group {
        margin-bottom: 15px; /* Spasi antar grup input */
    }
    .form-group label {
        display: block; /* Membuat label berada di atas input */
        margin-bottom: 5px; /* Spasi antara label dan input */
        font-weight: bold;
        color: #333;
    }
    .form-group input[type="text"],
    .form-group input[type="date"],
    .form-group select,
    .form-group textarea {
        width: calc(100% - 20px); /* Lebar input, dikurangi padding */
        padding: 10px;
        border: 1px solid #ccc;
        border-radius: 4px;
        box-sizing: border-box; /* Padding dan border tidak menambah lebar */
        font-size: 1em;
        color: gray; /* Warna teks input */
    }
    .form-group input[type="file"] {
        width: calc(100% - 20px);
        padding: 10px;
        border: 1px solid #ccc;
        border-radius: 4px;
        box-sizing: border-box;
        background-color: #f8f9fa; /* Warna latar belakang untuk input file */
        cursor: pointer;
        color: gray; /* Warna teks input file */
    }
    .btn-custom {
        display: inline-block;
        padding: 10px 20px;
        margin-right: 10px;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        font-size: 1em;
        text-decoration: none; /* Untuk link */
        color: white;
        text-align: center;
    }
    .btn-primary-custom {
        background-color: #007bff; /* Warna biru */
    }
    .btn-secondary-custom {
        background-color: #6c757d; /* Warna abu-abu */
    }
    .btn-custom:hover {
        opacity: 0.9; /* Efek hover sederhana */
    }
</style>

<div class="container-form">
    <div class="form-header">
        Form Tambah Data Arsip Surat
    </div>
    <div class="form-body">
        <form action="" method="POST" enctype="multipart/form-data"> <!-- PASTIKAN enctype INI ADA! -->
            <div class="form-group">
                <label for="no_surat">No Surat</label>
                <input type="text" id="no_surat" name="no_surat" required>
            </div>
            <div class="form-group">
                <label for="tanggal_surat">Tanggal Surat</label>
                <input type="date" id="tanggal_surat" name="tanggal_surat" required>
            </div>
            <div class="form-group">
                <label for="tanggal_diterima">Tanggal Diterima</label>
                <input type="date" id="tanggal_diterima" name="tanggal_diterima" required>
            </div>
            <div class="form-group">
                <label for="prihal">Prihal</label>
                <input type="text" id="prihal" name="prihal" required>
            </div>

            <div class="form-group">
                <label for="id_departemen">Departemen</label>
                <select id="id_departemen" name="id_departemen" required>
                    <option value=""> Pilih Departemen </option>
                    <?php while ($dep = mysqli_fetch_assoc($query_departemen)) : ?>
                        <option value="<?php echo htmlspecialchars($dep['id_departemen']); ?>">
                            <?php echo htmlspecialchars($dep['nama_departemen']); ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>

            <div class="form-group">
                <label for="id_pengirim">Pengirim</label>
                <select id="id_pengirim" name="id_pengirim" required>
                    <option value=""> Pilih Pengirim </option>
                    <?php while ($peng = mysqli_fetch_assoc($query_pengirim)) : ?>
                        <option value="<?php echo htmlspecialchars($peng['id_pengiriman_surat']); ?>">
                            <?php echo htmlspecialchars($peng['nama_pengirim']) . " (" . htmlspecialchars($peng['no_hp'] ?? '') . ")"; ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>

            <div class="form-group">
                <label for="file">Upload File (PDF/Gambar/Dokumen)</label>
                <input type="file" id="file" name="file"> <!-- PASTIKAN name="file" INI ADA! -->
            </div>

            <button type="submit" name="simpan" class="btn-custom btn-primary-custom">Simpan Data</button>
            <a href="?halaman=arsip_surat" class="btn-custom btn-secondary-custom">Batal</a>
        </form>
    </div>
</div>
