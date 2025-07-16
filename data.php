<?php

if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== TRUE || $_SESSION['level'] !== 'admin') {
    header("Location: unauthorized.php"); 
    exit();
}

error_reporting(E_ALL); 
ini_set('display_errors', 1);

$vnama_pengirim = '';

?>
<div class="card mt-3">
    <div class="card-header bg-info text-white">
        <h5 class="mb-0">Data Arsip Surat</h5>
    </div>
    <div class="card-body">
        <a href="?halaman=arsip_surat&hal=tambahdata" class="btn btn-info mb-3">
            <i class="bi bi-plus-circle me-1"></i> Tambah Data
        </a>

        <div class="table-responsive">
            <table class="table table-hover table-bordered">
                <thead>
                    <tr>
                        <th class="text-center text-nowrap">No</th>
                        <th class="text-nowrap">No Surat</th>
                        <th class="text-nowrap">Tanggal Surat</th>
                        <th class="text-nowrap">Tanggal Diterima</th>
                        <th class="text-nowrap">Perihal</th> <th class="text-nowrap">Departemen</th>
                        <th class="text-nowrap">Pengirim</th>
                        <th class="text-nowrap">No HP</th>
                        <th class="text-nowrap">File</th>
                        <th class="text-nowrap text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                   
                    $keyword = "";
                    if (isset($_GET['cari'])) {
                        $keyword = mysqli_real_escape_string($koneksi, $_GET['cari']);
                    }

                    // Menggunakan INNER JOIN eksplisit untuk readability dan memastikan relasi yang benar
                    $query_sql = "SELECT
                                        ta.*,
                                        td.nama_departemen,
                                        tps.nama_pengirim,
                                        tps.no_hp
                                FROM
                                        tbl_arsip AS ta
                                INNER JOIN
                                        tbl_departemen AS td ON ta.id_departemen = td.id_departemen
                                INNER JOIN
                                        tbl_pengiriman_surat AS tps ON ta.id_pengirim = tps.id_pengiriman_surat";

                    if (!empty($keyword)) {
                        $query_sql .= " WHERE ta.no_surat LIKE '%$keyword%'
                                        OR ta.prihal LIKE '%$keyword%'
                                        OR td.nama_departemen LIKE '%$keyword%'
                                        OR tps.nama_pengirim LIKE '%$keyword%'";
                    }
                    $query_sql .= " ORDER BY ta.id_arsip DESC"; // Urutkan data terbaru dulu

                    $tampil = mysqli_query($koneksi, $query_sql);

                    if (!$tampil) {
                        die("Query error: " . mysqli_error($koneksi) . "<br>SQL yang Gagal: " . $query_sql);
                    }

                    $no = 1;

                    if (mysqli_num_rows($tampil) > 0) :
                        while ($data = mysqli_fetch_array($tampil)) :
                    ?>
                            <tr>
                                <td class="text-center align-middle"><?php echo $no++; ?></td>
                                <td class="align-middle"><?php echo htmlspecialchars($data['no_surat'] ?? ''); ?></td>
                                <td class="align-middle text-nowrap"><?php echo htmlspecialchars($data['tanggal_surat'] ?? ''); ?></td>
                                <td class="align-middle text-nowrap"><?php echo htmlspecialchars($data['tanggal_diterima'] ?? ''); ?></td>
                                <td class="align-middle" style="min-width: 150px;"><?php echo htmlspecialchars($data['prihal'] ?? ''); ?></td> <td class="align-middle"><?php echo htmlspecialchars($data['nama_departemen'] ?? ''); ?></td>
                                <td class="align-middle"><?php echo htmlspecialchars($data['nama_pengirim'] ?? ''); ?></td>
                                <td class="align-middle"><?php echo htmlspecialchars($data['no_hp'] ?? ''); ?></td>
                                <td class="align-middle"><?php echo htmlspecialchars($data['file'] ?? ''); ?></td>
                                <td class="text-center align-middle text-nowrap">
                                    <a href="?halaman=arsip_surat&hal=edit&id=<?php echo htmlspecialchars($data['id_arsip'] ?? ''); ?>" class="btn btn-success btn-sm me-1">
                                        <i class="bi bi-pencil-square"></i> Edit
                                    </a>
                                    <a href="?halaman=arsip_surat&hal=hapus&id=<?php echo htmlspecialchars($data['id_arsip'] ?? ''); ?>" class="btn btn-danger btn-sm" onclick="return confirm('Apakah Anda yakin ingin menghapus data ini?')">
                                        <i class="bi bi-trash"></i> Hapus
                                    </a>
                                </td>
                            </tr>
                        <?php
                        endwhile;
                    else :
                        ?>
                        <tr>
                            <td colspan="10" class="text-center text-muted align-middle">Tidak ada data arsip surat yang ditemukan.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>