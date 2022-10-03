<?php 

require '../function.php';
require 'function.php';

// check login
checkLogin();

?>

<!-- HEADER -->
<?php
$title = 'Laporan Data Buku';
$style = [
    '../css/style.css',
    '../assets/bootstrap/css/bootstrap.min.css'
];
include("../layouts/header.php")
?>

<!-- ASIDE -->
<?php
$module = "lapDataBuku";
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

    <section>
        <div class="row d-flex">
            <div class="col-md-6">
                <form action="" method="post" enctype="multipart/form-data" target="_BLANK">
                    <div class="container form-laporan bg-white p-4 rounded">
                        <div class="mb-3">
                            <label for="tgl_awal" class="form-label">Tanggal Awal</label>
                            <input type="date" name="tgl_awal" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="tgl_akhir" class="form-label">Tanggal Akhir</label>
                            <input type="date" name="tgl_akhir" class="form-control" required>
                        </div>
                        <button class="btn btn-primary" type="submit" name="buku">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </section>
</main>


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