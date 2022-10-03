<?php

require '../function.php';
require 'function.php';

// check login
checkLogin();

$jmlData = 5;
$query = mysqli_query($conn, "SELECT * FROM pengembalian");
$totalData = mysqli_num_rows($query);
$jmlHalaman = ceil($totalData / $jmlData);
$halamanAktif = (isset($_GET['halaman'])) ? $_GET['halaman'] : 1;
$awalData = ($jmlData * $halamanAktif) - $jmlData;

$dataPengembalian = mysqli_query($conn, "SELECT * FROM pengembalian LEFT JOIN peminjaman on pengembalian.id_pinjam = peminjaman.id_pinjam LEFT JOIN buku on peminjaman.id_buku = buku.id LEFT JOIN anggota on peminjaman.id_anggota = anggota.id LIMIT $awalData, $jmlData");

if ((mysqli_num_rows($query) == 0)) {
    $_SESSION['error_message'] = "Tidak Ada Data";
}

// jika tombol cari diklik
if (isset($_GET['keyword'])) {
    $dataPengembalian = cari($_GET['keyword']);
}

// buat ID kembali
$noBaru = date('Ymd') . rand();

?>

<!-- HEADER -->
<?php
$title = 'Transaksi Pengembalian';
$style = [
    '../css/style.css',
    '../assets/bootstrap/css/bootstrap.min.css'
];
include("../layouts/header.php")
?>

<!-- ASIDE -->
<?php
$module = "dataPengembalian";
$href = [
    '../beranda/index.php',
    '../anggota/data-anggota.php',
    '../buku/data-buku.php',
    '../peminjaman/data-peminjaman.php',
    '../pengembalian/data-pengembalian.php',
    '../laporan/laporan-data-anggota.php',
    '../laporan/laporan-data-buku.php',
];

include("../layouts/aside.php");
?>

<main class="content">
    <?php include("../layouts/header-content.php") ?>
    <section class="d-flex flex-column gap-4">
        <?= responseSuccess(); ?>

        <!-- tambah -->
        <div class="d-flex justify-content-between gap-2 align-items-center">
            <a href="#" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#tambahModal">
                <img src="../assets/images/icon/plus-lg.svg" width="24" height="24" alt="">
            </a>
            <form>
            <div class="input-group gap-2">
                <input class="form-control" type="text" name="keyword" autocomplete="off" size="20" value="<?= isset($_GET['keyword']) ? $_GET['keyword'] : ''; ?>">
                <div class="input-group-append">
                    <button class="btn btn-outline-secondary" type="submit">cari</button>
                    <button class="btn btn-outline-secondary" onclick="document.location.href = '/perpus/pengembalian/data-pengembalian.php'" type="button">Reset</button>
                </div>
            </div>
            </form>
        </div>

        <div class="table-responsive">
            <?= responseError(); ?>

            <?php if (mysqli_num_rows($dataPengembalian) >= 1) { ?>

                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th scope="col">No.</th>
                            <th scope="col">Id Kembali</th>
                            <th scope="col">Nama Anggota</th>
                            <th scope="col">Judul Buku</th>
                            <th scope="col">Tanggal Peminjaman</th>
                            <th scope="col">Tanggal Pengembalian</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $i = $awalData + 1; ?>
                        <?php while ($row = mysqli_fetch_array($dataPengembalian)) : ?>
                            <tr>
                                <th><?= $i; ?></th>
                                <td><?= $row['id_kembali']; ?></td>
                                <td><?= $row['nama']; ?></td>
                                <td><?= $row['judul_buku']; ?></td>
                                <td><?= $row['tgl_pinjam']; ?></td>
                                <td><?= $row['tgl_kembali']; ?></td>
                            </tr>
                            <?php $i++; ?>
                        <?php endwhile ?>
                    </tbody>
                </table>
        </div>
        <!-- pagination -->
        <div class="d-flex justify-content-center">
            <?= pagination($jmlHalaman, $halamanAktif) ?>
        </div>    <?php } ?>
    </section>
</main>

<!-- modal tambah -->
<div class="modal fade" id="tambahModal" tabindex="-1" role="dialog" aria-labelledby="tambahModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="tambahModalLabel">Data Pengembalian</h5>
                <img src="../assets/images/icon/x-lg.svg" width="20" height="20" alt="" data-bs-dismiss="modal">
            </div>
            <div class="row">
                <div class="col-md-6">
                    <form action="function.php" method="post" enctype="multipart/form-data">
                        <div class="modal-body">
                            <div class="mb-3">
                                <label for="id_kembali" class="form-label">Id Kembali</label>
                                <input type="text" name="id_kembali" class="form-control" value="<?= $noBaru ?>" readonly>
                            </div>

                            <div class="mb-3">
                                <label for="id_pinjam" class="form-label">Id Pinjam</label>
                                <select name="id_pinjam" class="form-control" id="id_pinjam" onclick="ubahNilai(this.value)">

                                    <option value="0">--- Pilih ---</option>
                                    <?php
                                    include '../koneksi.php';
                                    $result = mysqli_query($conn, "SELECT * FROM peminjaman LEFT JOIN buku on peminjaman.id_buku = buku.id LEFT JOIN anggota on peminjaman.id_anggota = anggota.id WHERE peminjaman.status = 'Meminjam'");

                                    $listData = "let dataPinjam = new Array();";
                                    while ($row = mysqli_fetch_array($result)) {

                                        echo "<option value='$row[id_pinjam]'>$row[id_pinjam]</option>";
                                        $listData .=
                                            "dataPinjam['" . $row['id_pinjam'] . "'] = 
                                            { 
                                                judul_buku: '" . $row['judul_buku'] . "', 
                                                id_anggota: '" . $row['id_anggota'] . "',
                                                nama_anggota: '" . $row['nama'] . "', 
                                                tgl_pinjam: '" . $row['tgl_pinjam'] . "', 
                                                tgl_kembali: '" . $row['tgl_kembali'] . "'
                                            
                                            };";
                                    }
                                    ?>

                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="judul_buku" class="form-label">Judul Buku</label>
                                <input type="text" name="judul_buku" id="judul_buku" class="form-control" readonly>
                            </div>
                            <div class="mb-3">
                                <input type="hidden" name="id_anggota" id="id_anggota">
                                <label for="nama_anggota" class="form-label">Nama Anggota</label>
                                <input type="text" name="nama_anggota" id="nama_anggota" class="form-control" readonly>
                            </div>
                        </div>
                </div>
                <div class="col-md-6">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="tgl_pinjam" class="form-label">Tanggal Peminjaman</label>
                            <input type="date" name="tgl_pinjam" id="tgl_pinjam" class="form-control" readonly>
                        </div>
                        <div class="mb-3">
                            <label for="tgl_kembali" class="form-label">Tanggal Pengembalian</label>
                            <input type="date" name="tgl_kembali" id="tgl_kembali" class="form-control">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" name="submit" class="btn btn-primary">Tambah Data</button>
                </div>
                </form>
            </div>


        </div>
    </div>
</div>


<!-- FOOTER -->
<?php
$src = [
    '../js/jquery.js',
    '../js/index.js',
    '../assets/bootstrap/js/bootstrap.min.js',
    '../assets/bootstrap/js/bootstrap.bundle.min.js'
];
include('../layouts/footer.php');

?>
<script>
    <?php echo $listData; ?>

    function ubahNilai(e) {
        document.querySelector('#judul_buku').value = dataPinjam[e].judul_buku;
        document.querySelector('#id_anggota').value = dataPinjam[e].id_anggota;
        document.querySelector('#nama_anggota').value = dataPinjam[e].nama_anggota;
        document.querySelector('#tgl_pinjam').value = dataPinjam[e].tgl_pinjam;
        document.querySelector('#tgl_kembali').value = dataPinjam[e].tgl_kembali;
    };
</script>