<?php

if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== TRUE || $_SESSION['level'] !== 'admin') {
    header("Location: unauthorized.php");
    exit();
}


$query_total_arsip = mysqli_query($koneksi, "SELECT COUNT(id_arsip) AS total_arsip FROM tbl_arsip");
$data_total_arsip = mysqli_fetch_assoc($query_total_arsip);
$total_arsip = $data_total_arsip['total_arsip'];


$query_total_departemen = mysqli_query($koneksi, "SELECT COUNT(id_departemen) AS total_departemen FROM tbl_departemen");
$data_total_departemen = mysqli_fetch_assoc($query_total_departemen);
$total_departemen = $data_total_departemen['total_departemen'];


$query_total_pengirim = mysqli_query($koneksi, "SELECT COUNT(id_pengiriman_surat) AS total_pengirim FROM tbl_pengiriman_surat");
$data_total_pengirim = mysqli_fetch_assoc($query_total_pengirim);
$total_pengirim = $data_total_pengirim['total_pengirim'];


$query_recent_arsip = mysqli_query($koneksi, "SELECT ta.no_surat, ta.tanggal_diterima, ta.prihal, td.nama_departemen, tps.nama_pengirim
                                            FROM tbl_arsip AS ta
                                            INNER JOIN tbl_departemen AS td ON ta.id_departemen = td.id_departemen
                                            INNER JOIN tbl_pengiriman_surat AS tps ON ta.id_pengirim = tps.id_pengiriman_surat
                                            ORDER BY ta.tanggal_diterima DESC LIMIT 5");
?>

<div class="jumbotron mt-3">
  <h1 class="display-4"><strong>Selamat Datang Admin!</strong></h1>
  <p class="lead">E-Arsip adalah sistem manajemen surat elektronik yang dirancang khusus untuk membantu Anda mengelola surat masuk dan keluar dengan lebih efisien. Dengan fitur-fitur lengkap, Anda dapat mengarsipkan, mencari, dan melacak setiap dokumen penting dengan mudah. Program ini terus dikembangkan untuk memenuhi kebutuhan administrasi modern Anda.</p>
  <hr class="my-4">
  <p>Anda dapat menggunakan menu-menu navigasi di bagian atas halaman untuk mengakses berbagai fitur pengelolaan data, seperti data departemen, data pengirim surat, dan arsip surat. Kami berkomitmen untuk terus meningkatkan fungsionalitas dan kemudahan penggunaan sistem ini. Terima kasih atas kepercayaan Anda.</p>

</div>

<div class="container mt-4">
    <h2 class="mb-4">Ringkasan Data</h2>
    <div class="row">
        <div class="col-md-4 mb-3">
            <div class="card text-white bg-primary">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="card-title">Total Arsip Surat</h5>
                            <p class="card-text display-4"><?= htmlspecialchars($total_arsip) ?></p>
                        </div>
                        <i class="fas fa-envelope fa-3x"></i> 
                    </div>
                    <a href="?halaman=arsip_surat" class="text-white stretched-link">Lihat Detail <i class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-3">
            <div class="card text-white bg-success">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="card-title">Total Departemen</h5>
                            <p class="card-text display-4"><?= htmlspecialchars($total_departemen) ?></p>
                        </div>
                        <i class="fas fa-building fa-3x"></i> 
                    </div>
                    <a href="?halaman=departemen" class="text-white stretched-link">Lihat Detail <i class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-3">
            <div class="card text-white bg-info">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="card-title">Total Pengirim Surat</h5>
                            <p class="card-text display-4"><?= htmlspecialchars($total_pengirim) ?></p>
                        </div>
                        <i class="fas fa-users fa-3x"></i> 
                    </div>
                    <a href="?halaman=pengirim_surat" class="text-white stretched-link">Lihat Detail <i class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>
        </div>
    </div>

    <h2 class="mb-4 mt-5">Arsip Surat Terbaru</h2>
    <div class="table-responsive">
        <table class="table table-bordered table-hover table-striped">
            <thead>
                <tr>
                    <th>No Surat</th>
                    <th>Tanggal Diterima</th>
                    <th>Prihal</th>
                    <th>Departemen</th>
                    <th>Pengirim</th>
                </tr>
            </thead>
            <tbody>
                <?php if (mysqli_num_rows($query_recent_arsip) > 0): ?>
                    <?php while($data_recent = mysqli_fetch_assoc($query_recent_arsip)): ?>
                        <tr>
                            <td><?= htmlspecialchars($data_recent['no_surat'] ?? '') ?></td>
                            <td><?= htmlspecialchars($data_recent['tanggal_diterima'] ?? '') ?></td>
                            <td><?= htmlspecialchars($data_recent['prihal'] ?? '') ?></td>
                            <td><?= htmlspecialchars($data_recent['nama_departemen'] ?? '') ?></td>
                            <td><?= htmlspecialchars($data_recent['nama_pengirim'] ?? '') ?></td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="5" class="text-center">Belum ada arsip surat terbaru.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
