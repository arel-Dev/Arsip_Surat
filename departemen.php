    <?php
    // Pastikan session_start() sudah dipanggil di admin.php atau file utama lainnya.
    // Jika tidak, Anda akan mendapatkan "Notice: session_start(): Ignoring session_start() because a session is already active".
    // Baris ini dihapus karena seharusnya sudah ada di admin.php
    // session_start();

    // Pengecekan akses admin
    if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== TRUE || $_SESSION['level'] !== 'admin') {
        header("Location: unauthorized.php");
        exit();
    }

    // Aktifkan pelaporan error penuh untuk debugging
    error_reporting(E_ALL);
    ini_set('display_errors', 1);

    // Inisialisasi variabel untuk form departemen
    $vnama_departemen = '';

    // Logika untuk Simpan atau Ubah Data (saat tombol 'Simpan' diklik)
    if (isset($_POST['bsimpan'])) {
        $nama_departemen = mysqli_real_escape_string($koneksi, $_POST['nama_departemen']);

        if (isset($_GET['aksi']) && $_GET['aksi'] == "edit") {
            $ubah = mysqli_query($koneksi, "UPDATE tbl_departemen SET nama_departemen = '$nama_departemen' WHERE id_departemen = '$_GET[id]'");

            if ($ubah) {
                echo "<script>
                        alert('Ubah Data Berhasil');
                        document.location='?halaman=departemen';
                      </script>";
            } else {
                echo "<script>
                        alert('Ubah Data Gagal: " . mysqli_error($koneksi) . "');
                      </script>";
            }

        } else {
            $simpan = mysqli_query($koneksi, "INSERT INTO tbl_departemen VALUES ('', '$nama_departemen')");

            if ($simpan) {
                echo "<script>
                        alert('Simpan Data Berhasil');
                        document.location='?halaman=departemen';
                      </script>";
            } else {
                echo "<script>
                        alert('Simpan Data Gagal: " . mysqli_error($koneksi) . "');
                      </script>";
            }
        }
    }

    // Logika untuk mengambil data yang akan diedit
    if (isset($_GET['aksi']) && $_GET['aksi'] == 'edit') {
        $id_departemen_edit = mysqli_real_escape_string($koneksi, $_GET['id']);
        $tampil_edit = mysqli_query($koneksi, "SELECT * FROM tbl_departemen WHERE id_departemen='$id_departemen_edit'");
        $data_edit = mysqli_fetch_array($tampil_edit);

        if ($data_edit) {
            $vnama_departemen = $data_edit['nama_departemen'];
        }
    }

    // Logika untuk menghapus data
    if (isset($_GET['aksi']) && $_GET['aksi'] == 'hapus') {
        $id_departemen_hapus = mysqli_real_escape_string($koneksi, $_GET['id']);
        $hapus = mysqli_query($koneksi, "DELETE FROM tbl_departemen WHERE id_departemen='$id_departemen_hapus'");

        if ($hapus) {
            echo "<script>
                    alert('Hapus Data Berhasil');
                    document.location='?halaman=departemen';
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
        Form Data Departemen
      </div>
      <div class="card-body">
        <form method="post" action="">
          <div class="form-group">
            <label for="nama_departemen">Nama Departemen</label>
            <input type="text" class="form-control" id="nama_departemen" name="nama_departemen"
                   value="<?php echo htmlspecialchars($vnama_departemen); ?>" required placeholder="Masukkan nama departemen">
          </div>
          <button type="submit" name="bsimpan" class="btn btn-primary">Simpan</button>
          <a href="?halaman=departemen" class="btn btn-danger">Batal</a>
        </form>
      </div>
    </div>


    <div class="card mt-3">
      <div class="card-header bg-info text-white">
        Data Departemen
      </div>
      <div class="card-body">
        <table class="table table-bordered table-hover table-striped">
          <thead>
            <tr>
              <th>No</th>
              <th>Nama Departemen</th>
              <th>Aksi</th>
            </tr>
          </thead>
          <tbody>
            <?php
            $tampil = mysqli_query($koneksi, "SELECT * FROM tbl_departemen ORDER BY id_departemen DESC");
            $no = 1;

            if (mysqli_num_rows($tampil) > 0):
              while($data = mysqli_fetch_array($tampil)):
            ?>
            <tr>
              <td><?php echo $no++; ?></td>
              <td><?php echo htmlspecialchars($data['nama_departemen']); ?></td>
              <td>
                <a href="?halaman=departemen&aksi=edit&id=<?php echo htmlspecialchars($data['id_departemen']); ?>" class="btn btn-success btn-sm">Edit</a>
                <a href="?halaman=departemen&aksi=hapus&id=<?php echo htmlspecialchars($data['id_departemen']); ?>"
                   class="btn btn-danger btn-sm"
                   onclick="return confirm('Yakin ingin menghapus data ini?')">Hapus</a>
              </td>
            </tr>
            <?php endwhile; else: ?>
            <tr>
              <td colspan="3" class="text-center">Belum ada data</td>
            </tr>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
    </div>
    