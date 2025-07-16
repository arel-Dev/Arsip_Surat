<?php

if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== TRUE || $_SESSION['level'] !== 'admin') {
    header("Location: unauthorized.php"); 
    exit();
}

error_reporting(E_ALL); 
ini_set('display_errors', 1); 

$vnama_pengirim = '';

error_reporting(E_ALL); 
ini_set('display_errors', 1); 

$vnama_pengirim = '';
$valamat = '';
$vno_hp = '';
$vemail = '';


if (isset($_POST['bsimpan'])) {
    $nama_pengirim = mysqli_real_escape_string($koneksi, $_POST['nama_pengirim']);
    $alamat        = mysqli_real_escape_string($koneksi, $_POST['alamat']);
    $no_hp         = mysqli_real_escape_string($koneksi, $_POST['no_hp']);
    $email         = mysqli_real_escape_string($koneksi, $_POST['email']);

    if (isset($_GET['aksi']) && $_GET['aksi'] == "edit") {
        $id_pengiriman_surat_edit = mysqli_real_escape_string($koneksi, $_GET['id']);
        $ubah = mysqli_query($koneksi, "UPDATE tbl_pengiriman_surat
            SET nama_pengirim = '$nama_pengirim', alamat = '$alamat', no_hp = '$no_hp', email = '$email'
            WHERE id_pengiriman_surat = '$id_pengiriman_surat_edit'");

        if ($ubah) {
            echo "<script>
                    alert('Ubah Data Berhasil');
                    document.location='?halaman=pengirim_surat';
                  </script>";
        } else {
            echo "<script>
                    alert('Ubah Data Gagal: " . mysqli_error($koneksi) . "');
                  </script>";
        }

    } else {
        
        $simpan = mysqli_query($koneksi, "
            INSERT INTO tbl_pengiriman_surat (nama_pengirim, alamat, no_hp, email)
            VALUES ('$nama_pengirim', '$alamat', '$no_hp', '$email')");

        if ($simpan) {
            echo "<script>alert('Simpan Data Berhasil'); location.href='?halaman=pengirim_surat';</script>";
        } else {
            echo "<script>alert('Simpan Data Gagal: " . mysqli_error($koneksi) . "');</script>";
        }
    }
}


if (isset($_GET['aksi']) && $_GET['aksi'] == 'edit') {
    $id_pengiriman_surat_edit = mysqli_real_escape_string($koneksi, $_GET['id']);
    $tampil_edit = mysqli_query($koneksi, "SELECT * FROM tbl_pengiriman_surat WHERE id_pengiriman_surat='$id_pengiriman_surat_edit'");

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
        echo "<script>alert('Data tidak ditemukan untuk diedit.'); document.location='?halaman=pengirim_surat';</script>";
    }
}


if (isset($_GET['aksi']) && $_GET['aksi'] == 'hapus') {
    $id_pengiriman_surat_hapus = mysqli_real_escape_string($koneksi, $_GET['id']);
    $hapus = mysqli_query($koneksi, "DELETE FROM tbl_pengiriman_surat WHERE id_pengiriman_surat='$id_pengiriman_surat_hapus'");

    if ($hapus) {
        echo "<script>alert('Hapus Data Berhasil'); document.location='?halaman=pengirim_surat';</script>";
    } else {
        echo "<script>alert('Hapus Data Gagal: " . mysqli_error($koneksi) . "');</script>";
    }
}
?>

<div class="card mt-3">
    <div class="card-header bg-primary text-white">
        Form Data Pengirim Surat
    </div>
    <div class="card-body">
        <form method="POST" action="">
            <div class="mb-3">
                <label for="nama_pengirim" class="form-label">Nama Pengirim</label>
                <input type="text" class="form-control" id="nama_pengirim" name="nama_pengirim" value="<?php echo htmlspecialchars($vnama_pengirim); ?>" required>
            </div>
            <div class="mb-3">
                <label for="alamat" class="form-label">Alamat</label>
                <textarea class="form-control" id="alamat" name="alamat" required><?php echo htmlspecialchars($valamat); ?></textarea>
            </div>
            <div class="mb-3">
                <label for="no_hp" class="form-label">No HP</label>
                <input type="text" class="form-control" id="no_hp" name="no_hp" value="<?php echo htmlspecialchars($vno_hp); ?>" required>
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control" id="email" name="email" value="<?php echo htmlspecialchars($vemail); ?>" required>
            </div>
            <button type="submit" name="bsimpan" class="btn btn-primary">Simpan</button>
            <a href="?halaman=pengirim_surat" class="btn btn-danger">Batal</a>
        </form>
    </div>
</div>

<div class="card mt-3">
    <div class="card-header bg-success text-white">
        Data Pengirim Surat
    </div>
    <div class="card-body">
        <table class="table table-bordered table-hover table-striped">
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
                        <a href="?halaman=pengirim_surat&aksi=edit&id=<?php echo htmlspecialchars($data['id_pengiriman_surat']); ?>" class="btn btn-success btn-sm">Edit</a>
                        <a href="?halaman=pengirim_surat&aksi=hapus&id=<?php echo htmlspecialchars($data['id_pengiriman_surat']); ?>" class="btn btn-danger btn-sm"
                           onclick="return confirm('Apakah Anda yakin ingin menghapus data ini?')">Hapus</a>
                    </td>
                </tr>
                <?php
                  endwhile;
                else:
                ?>
                <tr>
                    <td colspan="6">Belum ada data Pengirim Surat.</td>
                </tr>
                <?php
                endif;
                ?>
            </tbody>
        </table>
    </div>
</div>