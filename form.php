<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

// --- KODE KONEKSI DATABASE DIMULAI DI SINI ---
// Pastikan Anda mengganti nilai-nilai berikut sesuai dengan konfigurasi database Anda
$host = "localhost"; // Biasanya 'localhost' jika database berada di server yang sama
$user = "root";      // Username database Anda (contoh: 'root' untuk XAMPP/WAMP default)
$pass = "";          // Password database Anda (kosongkan jika tidak ada password)
$db   = "dbarsip";   // GANTI INI DENGAN NAMA DATABASE ANDA (sesuai gambar: dbarsip)

$koneksi = mysqli_connect($host, $user, $pass, $db);

// Periksa koneksi
if (mysqli_connect_errno()) {
    echo "Gagal terhubung ke MySQL: " . mysqli_connect_error();
    exit(); // Hentikan eksekusi skrip jika koneksi gagal
}
// --- KODE KONEKSI DATABASE BERAKHIR DI SINI ---

// Inisialisasi semua variabel yang akan digunakan di form, untuk menghindari "Undefined variable" warnings
$vnama_pengirim = '';
$valamat = '';
$vno_hp = '';
$vemail = '';

// Logika untuk Simpan atau Ubah Data (saat tombol 'Simpan' diklik)
if (isset($_POST['bsimpan'])) {
    $nama_pengirim = mysqli_real_escape_string($koneksi, $_POST['nama_pengirim']);
    $alamat        = mysqli_real_escape_string($koneksi, $_POST['alamat']);
    $no_hp         = mysqli_real_escape_string($koneksi, $_POST['no_hp']);
    $email         = mysqli_real_escape_string($koneksi, $_POST['email']);

    if (isset($_GET['aksi']) && $_GET['aksi'] == "edit") {
        // Mode Edit: Lakukan UPDATE
        $id_pengiriman_surat_edit = mysqli_real_escape_string($koneksi, $_GET['id']);
        $ubah = mysqli_query($koneksi, "UPDATE tbl_pengiriman_surat
            SET nama_pengirim = '$nama_pengirim', alamat = '$alamat', no_hp = '$no_hp', email = '$email'
            WHERE id_pengiriman_surat = '$id_pengiriman_surat_edit'");

        if ($ubah) {
            echo "<script>
                    alert('Ubah Data Berhasil');
                    document.location='?halaman=pengirim_surat'; // <-- DIKOREKSI ke pengirim_surat
                  </script>";
        } else {
            echo "<script>
                    alert('Ubah Data Gagal: " . mysqli_error($koneksi) . "');
                  </script>";
        }

    } else {
        // Mode Simpan Baru: Lakukan INSERT
        $simpan = mysqli_query($koneksi, "
            INSERT INTO tbl_pengiriman_surat (nama_pengirim, alamat, no_hp, email)
            VALUES ('$nama_pengirim', '$alamat', '$no_hp', '$email')");

        if ($simpan) {
            echo "<script>alert('Simpan Data Berhasil'); location.href='?halaman=pengirim_surat';</script>"; // <-- DIKOREKSI ke pengirim_surat
        } else {
            echo "<script>alert('Simpan Data Gagal: " . mysqli_error($koneksi) . "');</script>";
        }
    }
}


// Logika untuk Mengisi Form Saat Aksi Edit (saat link 'Edit' di tabel diklik)
if (isset($_GET['aksi']) && $_GET['aksi'] == 'edit') {
    $id_pengiriman_surat_edit = mysqli_real_escape_string($koneksi, $_GET['id']);
    $tampil_edit = mysqli_query($koneksi, "SELECT * FROM tbl_pengiriman_surat WHERE id_pengiriman_surat='$id_pengiriman_surat_edit'");

    // Penanganan jika kueri SELECT gagal
    if (!$tampil_edit) {
        die("Query error: " . mysqli_error($koneksi));
    }

    $data_edit = mysqli_fetch_array($tampil_edit);

    if ($data_edit) {
        $vnama_pengirim = $data_edit['nama_pengirim'];
        $valamat = $data_edit['alamat'];
        $vno_hp = $data_edit['no_hp'];
        $vemail = $data_edit['email'];
    } else {
        // Jika data tidak ditemukan untuk ID yang diberikan
        echo "<script>alert('Data tidak ditemukan untuk diedit.'); document.location='?halaman=pengirim_surat';</script>"; // <-- DIKOREKSI ke pengirim_surat
    }
}


// Logika untuk Hapus Data (saat link 'Hapus' di tabel diklik)
if (isset($_GET['aksi']) && $_GET['aksi'] == 'hapus') {
    $id_pengiriman_surat_hapus = mysqli_real_escape_string($koneksi, $_GET['id']);
    $hapus = mysqli_query($koneksi, "DELETE FROM tbl_pengiriman_surat WHERE id_pengiriman_surat='$id_pengiriman_surat_hapus'");

    if ($hapus) {
        echo "<script>
                alert('Hapus Data Berhasil');
                document.location='?halaman=pengirim_surat'; // <-- DIKOREKSI ke pengirim_surat
              </script>";
    } else {
        echo "<script>
                alert('Hapus Data Gagal: " . mysqli_error($koneksi) . "');
              </script>";
    }
}
?>

<div class="card mt-3">
  <div class="card-header bg-info text-white">
    Form Data Arsip Surat
  </div>
  <div class="card-body">
    <form method="post" action="">
      <div class="form-group row mb-3">
        <label for="nama_pengirim" class="col-sm-2 col-form-label">No. Surat</label>
        <div class="col-sm-10">
          <input type="text" class="form-control" id="nama_pengirim" name="nama_pengirim"
                 value="<?php echo htmlspecialchars($vnama_pengirim); ?>" placeholder="Masukkan nama pengirim" required>
        </div>
      </div>

      <div class="form-group row mb-3">
        <label for="alamat" class="col-sm-2 col-form-label">Tanggal Surat</label>
        <div class="col-sm-10">
          <input type="text" class="form-control" id="alamat" name="alamat"
                 value="<?php echo htmlspecialchars($valamat); ?>" placeholder="Masukkan alamat" required>
        </div>
      </div>

      <div class="form-group row mb-3">
        <label for="no_hp" class="col-sm-2 col-form-label">Tanggal Diterima</label>
        <div class="col-sm-10">
          <input type="text" class="form-control" id="no_hp" name="no_hp"
                 value="<?php echo htmlspecialchars($vno_hp); ?>" placeholder="Masukkan No HP" required>
        </div>
      </div>

      <div class="form-group row mb-3">
        <label for="email" class="col-sm-2 col-form-label">Email</label>
        <div class="col-sm-10">
          <input type="email" class="form-control" id="email" name="email"
                 value="<?php echo htmlspecialchars($vemail); ?>" placeholder="Masukkan Email" required>
        </div>
      </div>

      <div class="form-group row">
        <div class="col-sm-10 offset-sm-2">
          <button type="submit" name="bsimpan" class="btn btn-primary">Simpan</button>
          <a href="?halaman=pengirim_surat" class="btn btn-danger">Batal</a> </div>
      </div>
    </form>
  </div>
</div>


<div class="card mt-3">
  <div class="card-header bg-info text-white">
    Data Pengirim Surat
  </div>
  <div class="card-body">
    <a href="?halaman=pengirim_surat" class="btn btn-success mb-3">Tambah Data</a> <table class="table table-bordered table-hover table-striped">
      <thead>
        <tr>
          <th>No</th>
          <th>Nama Pengirim</th>
          <th>Alamat</th>
          <th>No HP</th>
          <th>Email</th>
          <th>Aksi</th>
        </tr>
      </thead>
      <tbody>
        <?php
     
 
        $tampil = mysqli_query($koneksi, "SELECT * FROM tbl_pengiriman_surat ORDER BY id_pengiriman_surat DESC");

        if (!$tampil) {
            die("Query error: " . mysqli_error($koneksi));
        }

        $no = 1;
        if (mysqli_num_rows($tampil) > 0):
          while($data = mysqli_fetch_array($tampil)):
        ?>
        <tr>
          <td><?php echo $no++; ?></td>
          <td><?php echo htmlspecialchars($data['nama_pengirim']); ?></td>
          <td><?php echo htmlspecialchars($data['alamat']); ?></td>
          <td><?php echo htmlspecialchars($data['no_hp']); ?></td>
          <td><?php echo htmlspecialchars($data['email']); ?></td>
          <td>
            <a href="?halaman=pengirim_surat&aksi=edit&id=<?php echo htmlspecialchars($data['id_pengiriman_surat']); ?>" class="btn btn-success btn-sm">Edit</a> <a href="?halaman=pengirim_surat&aksi=hapus&id=<?php echo htmlspecialchars($data['id_pengiriman_surat']); ?>" class="btn btn-danger btn-sm"
               onclick="return confirm('Apakah Anda yakin ingin menghapus data ini?')">Hapus</a>
          </td>
        </tr>
        <?php endwhile; else: ?>
        <tr>
          <td colspan="6" class="text-center">Belum ada data</td>
        </tr>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
</div>