<?php

require '../function.php';
require '../koneksi.php';

// check login
checkLogin();

// JUMLAH DATA
$jml_anggota = mysqli_query($conn, "SELECT COUNT(anggota.id) FROM anggota");

$jml_buku = mysqli_query($conn, "SELECT COUNT(buku.id) FROM buku");

$jml_buku_pinjam = mysqli_query($conn, "SELECT COUNT(peminjaman.id_pinjam) FROM peminjaman WHERE(peminjaman.status = 'Meminjam') ORDER BY id_pinjam LIMIT 1");

$jml_buku_kembali = mysqli_query($conn, "SELECT COUNT(pengembalian.id_kembali) FROM pengembalian ORDER BY id_kembali LIMIT 1");

$bukus = mysqli_query($conn, "SELECT peminjaman.id_buku, buku.judul_buku as judul_buku, buku.penulis as penulis, buku.penerbit as penerbit, buku.tahun_terbit as tahun_terbit, buku.gambar as gambar, buku.ISBN as isbn, COUNT(*) as total FROM peminjaman LEFT JOIN buku on peminjaman.id_buku = buku.id WHERE (peminjaman.status = 'Meminjam' OR peminjaman.status = 'Mengembalikan') GROUP BY peminjaman.id_buku ORDER BY COUNT(*) DESC");

if (isset($_SESSION['sesi'])) {
?>

    <!-- HEADER -->
    <?php
    $title = 'Beranda';
    $style = [
        '../css/style.css',
        '../assets/bootstrap/css/bootstrap.min.css',
        '../assets/vendor/owlcarousel/dist/assets/owl.carousel.min.css',
        '../assets/vendor/owlcarousel/dist/assets/owl.theme.default.min.css'
    ];
    include("../layouts/header.php")
    ?>

    <!-- ASIDE -->
    <?php

    $module = 'beranda';

    $href = [
        '../beranda/index.php',
        '../anggota/data-anggota.php',
        '../buku/data-buku.php',
        '../peminjaman/data-peminjaman.php',
        '../pengembalian/data-pengembalian.php',
        '../laporan/laporan-data-anggota.php',
        '../laporan/laporan-data-buku.php'
    ];

    include("../layouts/aside.php");
    ?>

    <main class="content">
        <section class="header-content">
            <!-- Header Content -->
            <?php include("../layouts/header-content.php") ?>
            <p class="mt-2 text-success">Hi <?php echo $_SESSION['sesi']; ?>!</p>

        </section>
        <section class="main-content mt-2">

            <div class="item-content d-flex align-items-start">
                <div class="row col-lg-12">
                    <div class="col-lg-3">
                        <div class="cards mb-3">
                            <p class="title-cards">Jumlah Anggota</p>
                            <?php while ($data = mysqli_fetch_array($jml_anggota)) : ?>
                                <p class="number"><?= $data[0] ?></p>
                            <?php endwhile; ?>
                        </div>
                    </div>
                    <div class="col-lg-3">
                        <div class="cards  mb-3">
                            <p class="title-cards">Jumlah buku</p>
                            <?php while ($data = mysqli_fetch_array($jml_buku)) : ?>
                                <p class="number"><?= $data[0] ?></p>
                            <?php endwhile; ?>
                        </div>
                    </div>
                    <div class="col-lg-3">
                        <div class="cards mb-3">
                            <p class="title-cards">Total Peminjaman Buku</p>
                            <?php while ($data = mysqli_fetch_array($jml_buku_pinjam)) : ?>
                                <p class="number"><?= $data[0] ?></p>
                            <?php endwhile; ?>
                        </div>
                    </div>
                    <div class="col-lg-3">
                        <div class="cards mb-3">
                            <p class="title-cards">Total Pengembalian Buku</p>
                            <?php while ($data = mysqli_fetch_array($jml_buku_kembali)) : ?>
                                <p class="number"><?= $data[0] ?></p>
                            <?php endwhile; ?>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <section>
            <h5>Kumpulan buku favorit</h5>

            <div class="container-books mt-4">
                <div class="row flex-wrap ">
                    <!-- <div class="owl-carousel"> -->

                    <?php $i = 1 ?>
                    <?php while ($data = mysqli_fetch_array($bukus)) : ?>
                        <div class="col-md-4 col-lg-3">
                            <div class="card-book mb-3 ">
                                <div class="gambar text-center">
                                    <img src="../assets/images/foto-buku/<?= $data['gambar'] ?>" alt="gambar buku">
                                    <div class="overlay"><img data-bs-toggle="modal" data-bs-target="#detailModal<?= $i ?>" width="30" height="30" src="../assets/images/icon/eye.svg" alt="" class="lihat-detail"></div>
                                </div>
                                <div class="detail">
                                    <p class="judul_buku"><?= $data['judul_buku']; ?></p>
                                </div>
                            </div>
                        </div>

                        <!-- MODAL DETAIL BUKU -->
                        <div class="modal fade" id="detailModal<?= $i ?>" tabindex="-1" role="dialog" aria-labelledby="detailModalLabel" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="detailModalLabel">Detail Buku</h5>
                                        <img src="../assets/images/icon/x-lg.svg" width="20" height="20" alt="" data-bs-dismiss="modal">
                                    </div>
                                    <div class="modal-body">
                                        <div class="col text-center">
                                            <img src="../assets/images/foto-buku/<?= $data['gambar'] ?>" alt="" width="250" class="mt-md-4 mb-4">
                                        </div>
                                        <div class="col">
                                            <div class="mb-3">
                                                <label for="judul_buku" class="form-label">Judul Buku</label>
                                                <input type="text" name="judul_buku" class="form-control" id="judul_buku" value="<?= $data['judul_buku'] ?>" readonly>
                                            </div>
                                            <div class="mb-3">
                                                <label for="penulis" class="form-label">Penulis</label>
                                                <input type="text" name="penulis" class="form-control" id="penulis" value="<?= $data['penulis'] ?>" readonly>
                                            </div>
                                            <div class="mb-3">
                                                <label for="penerbit" class="form-label">Penerbit</label>
                                                <input type="text" name="penerbit" class="form-control" id="penerbit" value="<?= $data['penerbit'] ?>" readonly>
                                            </div>
                                            <div class="mb-3">
                                                <label for="tahun_terbit" class="form-label">Tahun Terbit</label>
                                                <input type="number" name="tahun_terbit" class="form-control" id="tahun_terbit" value="<?= $data['tahun_terbit'] ?>" readonly>
                                            </div>
                                            <div class="mb-3">
                                                <label for="isbn" class="form-label">ISBN</label>
                                                <input type="text" name="ISBN" class="form-control" id="isbn" value="<?= $data['isbn'] ?>" readonly>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php $i++ ?>
                    <?php endwhile; ?>

                </div>
            </div>

        </section>
    </main>

    <!-- FOOTER -->
    <?php
    $src = [
        '../js/jquery.js',
        '../assets/bootstrap/js/bootstrap.min.js',
        '../assets/bootstrap/js/bootstrap.bundle.min.js',
        '../assets/vendor/owlcarousel/dist/owl.carousel.min.js',
        '../js/index.js'
    ];
    include('../layouts/footer.php');

    ?>

<?php } ?>