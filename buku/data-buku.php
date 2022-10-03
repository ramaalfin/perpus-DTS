<?php

require '../function.php';
require 'function.php';

// check login
checkLogin();

// pagination
$jmlData = 5;
$query = mysqli_query($conn, "SELECT * FROM buku");
$totalData = mysqli_num_rows($query);
$jmlHalaman = ceil($totalData / $jmlData);
$halamanAktif = (isset($_GET['halaman'])) ? $_GET['halaman'] : 1;
$awalData = ($jmlData * $halamanAktif) - $jmlData;

$dataBuku = mysqli_query($conn, "SELECT * FROM buku LIMIT $awalData, $jmlData");

if ((mysqli_num_rows($query) == 0)) {
    $_SESSION['error_message'] = "Tidak Ada Data";
}

// jika tombol cari diklik
if (isset($_GET['keyword'])) {
    $dataBuku = cari($_GET['keyword']);
}
?>

<!-- HEADER -->
<?php
$title = 'Data Buku';
$style = [
    '../css/style.css',
    '../assets/bootstrap/css/bootstrap.min.css'
];
include("../layouts/header.php")
?>

<!-- ASIDE -->
<?php
$module = "dataBuku";
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
    <!-- Header Content -->
    <?php include("../layouts/header-content.php") ?>

    <section class="d-flex flex-column gap-4">
        <?= responseSuccess(); ?>

        <!-- tambahModal -->
        <div class="d-flex justify-content-between gap-2 align-items-center">
            <a href="#" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#tambahModal">
                <img src="../assets/images/icon/plus-lg.svg" width="24" height="24" alt="">
            </a>
            <form>
                <div class="input-group gap-2">
                    <input class="form-control" type="text" name="keyword" autocomplete="off" size="20"  value="<?= isset($_GET['keyword']) ? $_GET['keyword'] : ''; ?>">
                    <div class="input-group-append">
                        <button class="btn btn-outline-secondary" type="submit">cari</button>
                        <button class="btn btn-outline-secondary" onclick="document.location.href = '/perpus/buku/data-buku.php'" type="button">Reset</button>
                    </div>
                </div>
            </form>
        </div>

        <div class="table-responsive">
            <?= responseError(); ?>


            <?php if (mysqli_num_rows($dataBuku) >= 1) { ?>
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th scope="col">No.</th>
                            <th scope="col">Judul Buku</th>
                            <th scope="col">Penulis</th>
                            <th scope="col">Penerbit</th>
                            <th scope="col">Tahun Terbit</th>
                            <th scope="col">ISBN</th>
                            <th scope="col">Stok</th>
                            <th scope="col" class="text-center">Manage</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $i = $awalData + 1; ?>
                        <?php while ($data = mysqli_fetch_array($dataBuku)) : ?>
                            <tr>
                                <th><?= $i; ?>.</th>
                                <td><?= $data['judul_buku']; ?></td>
                                <td><?= $data['penulis']; ?></td>
                                <td><?= $data['penerbit']; ?></td>
                                <td><?= $data['tahun_terbit']; ?></td>
                                <td><?= $data['ISBN']; ?></td>
                                <td><?= $data['stok']; ?></td>
                                <td class="d-flex">

                                    <a href="javascript:void(0)" class="btn btn-info mx-1  detail_buku" data-id_buku="<?= $data['id'] ?>" data-judul_buku="<?= $data['judul_buku'] ?>" data-penulis="<?= $data['penulis'] ?>" data-penerbit="<?= $data['penerbit'] ?>" data-tahun_terbit="<?= $data['tahun_terbit'] ?>" data-isbn="<?= $data['ISBN'] ?>" data-stok="<?= $data['stok'] ?>" data-gambar="<?= $data['gambar'] ?>">
                                        <img width="20" he="20" src="../assets/images/icon/eye.svg" alt="">
                                    </a>

                                    <a href="javascript:void(0)" class="btn btn-warning mx-1  ubah_buku" data-id_buku="<?= $data['id'] ?>" data-judul_buku="<?= $data['judul_buku'] ?>" data-penulis="<?= $data['penulis'] ?>" data-penerbit="<?= $data['penerbit'] ?>" data-tahun_terbit="<?= $data['tahun_terbit'] ?>" data-isbn="<?= $data['ISBN'] ?>" data-stok="<?= $data['stok'] ?>" data-gambar="<?= $data['gambar'] ?>">
                                        <img width="20" he="20" src="../assets/images/icon/pen.svg" alt="">
                                    </a>

                                    <a href="javascript:void(0" class="btn btn-danger hapus_buku" data-id_buku="<?= $data['id'] ?>" data-judul_buku="<?= $data['judul_buku'] ?>" data-gambar="<?= $data['gambar'] ?>">
                                        <img width="20" he="20" src="../assets/images/icon/trash3-fill.svg" alt="">
                                    </a>
                                </td>
                            </tr>

                            <?php $i++; ?>

                        <?php endwhile; ?>
                    </tbody>
                </table>
        </div>

        <!-- pagination -->
        <div class="d-flex justify-content-center">
            <?= pagination($jmlHalaman, $halamanAktif) ?>
        </div>
    <?php } ?>
    </section>
</main>

<!-- modal tambah -->
<div class="modal fade" id="tambahModal" tabindex="-1" role="dialog" aria-labelledby="tambahModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="tambahModalLabel">Tambah Buku Baru</h5>
                <img src="../assets/images/icon/x-lg.svg" width="20" height="20" alt="" data-bs-dismiss="modal">
            </div>
            <form action="function.php" method="post" enctype="multipart/form-data">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="judul_buku" class="form-label">Judul Buku</label>
                        <input type="text" name="judul_buku" class="form-control" id="judul_buku" required>
                    </div>
                    <div class="mb-3">
                        <label for="penulis" class="form-label">Penulis</label>
                        <input type="text" name="penulis" class="form-control" id="penulis" required>
                    </div>
                    <div class="mb-3">
                        <label for="penerbit" class="form-label">Penerbit</label>
                        <input type="text" name="penerbit" class="form-control" id="penerbit" required>
                    </div>
                    <div class="mb-3">
                        <label for="tahun_terbit" class="form-label">Tahun Terbit</label>
                        <input type="number" name="tahun_terbit" class="form-control" id="tahun_terbit" required>
                    </div>
                    <div class="mb-3">
                        <label for="isbn" class="form-label">ISBN</label>
                        <input type="text" name="isbn" class="form-control" id="isbn" required>
                    </div>
                    <div class="mb-3">
                        <label for="stok" class="form-label">stok</label>
                        <input type="number" name="stok" class="form-control" id="stok" required>
                    </div>
                    <div class="mb-3">
                        <label for="gambar" class="form-label">Gambar (Maks. 2 Mb)</label>
                        <input type="file" name="gambar" class="form-control" id="gambar" required>
                    </div>
                    <input type="hidden" name="tgl_input" value="<?= date('Ymd') ?>">
                </div>
                <div class="modal-footer">
                    <button type="submit" name="tambah" class="btn btn-primary">Tambah Buku</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Ubah-->
<div class="modal fade" id="ubahModal" tabindex="-1" role="dialog" aria-labelledby="ubahModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="ubahModalLabel">Ubah Data Buku</h5>
                <img src="../assets/images/icon/x-lg.svg" width="22" height="22" alt="" data-bs-dismiss="modal">
            </div>
            <div class="modal-body">
                <form action="" method="post" enctype="multipart/form-data">
                    <input type="hidden" name="id" id="ubah_id_buku">
                    <input type="hidden" name="gambarLama" id="ubah_gambarLama">
                    <div class="mb-3">
                        <label for="judul_buku" class="form-label">Judul Buku</label>
                        <input type="text" name="judul_buku" class="form-control" id="ubah_judul_buku" required>
                    </div>
                    <div class="mb-3">
                        <label for="penulis" class="form-label">Penulis</label>
                        <input type="text" name="penulis" class="form-control" id="ubah_penulis" required>
                    </div>
                    <div class="mb-3">
                        <label for="penerbit" class="form-label">Penerbit</label>
                        <input type="text" name="penerbit" class="form-control" id="ubah_penerbit" required>
                    </div>
                    <div class="mb-3">
                        <label for="tahun_terbit" class="form-label">Tahun Terbit</label>
                        <input type="number" name="tahun_terbit" class="form-control" id="ubah_tahun_terbit" required>
                    </div>
                    <div class="mb-3">
                        <label for="isbn" class="form-label">ISBN</label>
                        <input type="text" name="ISBN" class="form-control" id="ubah_isbn" required>
                    </div>
                    <div class="mb-3">
                        <label for="stok" class="form-label">stok</label>
                        <input type="number" name="stok" class="form-control" id="ubah_stok" required>
                    </div>
                    <div class="mb-3">
                        <label for="gambar" class="form-label">Gambar (Maks. 2 Mb)</label><br>
                        <img id="ubah_gambar_buku" alt="" width="300" class="my-md-4 d-flex m-auto"">
                        <input type="file" name="gambar" class="form-control mt-4">
                    </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary" name="ubah">Ubah Data</button>
            </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Hapus-->
<div class="modal fade" id="hapusModal" tabindex="-1" role="dialog" aria-labelledby="hapusModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="hapusModalLabel">Hapus Data Buku</h5>
                <img src="../assets/images/icon/x-lg.svg" width="20" height="20" alt="" data-bs-dismiss="modal">
            </div>
            <div class="modal-body">
                <h5 class="text-center mb-4">Apakah Anda yakin ingin menghapus data?</h5>
                <form action="" method="post" enctype="multipart/form-data">
                    <input type="hidden" name="id" readonly class="form-control" id="hapus_id_buku" required>

                    <div class="mb-3 text-center">
                        <img id="hapus_gambar_buku" alt="" width="200" class="my-md-4">
                    </div>

                    <div class="mb-3">
                        <label for="judul_buku" class="form-label">Judul Buku</label>
                        <input type="text" name="judul_buku" class="form-control" id="hapus_judul_buku" readonly>
                    </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-danger" name="hapus">Hapus Data</button>
            </div>
            </form>
        </div>
    </div>
</div>


<!-- detail modal -->
<div class="modal fade" id="detailModal" tabindex="-1" role="dialog" aria-labelledby="detailModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="detailModalLabel">Detail Buku</h5>
                <img src="../assets/images/icon/x-lg.svg" width="20" height="20" alt="" data-bs-dismiss="modal">
            </div>
            <div class="modal-body">
                <div class="col text-center">
                    <img id="detail_gambar_buku" alt="" width="250" class="mt-md-4 mb-4">
                </div>
                <div class="col">
                    <div class="mb-3">
                        <label for="judul_buku" class="form-label">Judul Buku</label>
                        <input type="text" name="judul_buku" readonly class="form-control" id="detail_judul_buku">
                    </div>
                    <div class="mb-3">
                        <label for="penulis" class="form-label">Penulis</label>
                        <input type="text" name="penulis" readonly class="form-control" id="detail_penulis">
                    </div>
                    <div class="mb-3">
                        <label for="penerbit" class="form-label">Penerbit</label>
                        <input type="text" name="penerbit" readonly class="form-control" id="detail_penerbit">
                    </div>
                    <div class="mb-3">
                        <label for="tahun_terbit" class="form-label">Tahun Terbit</label>
                        <input type="number" name="tahun_terbit" readonly class="form-control" id="detail_tahun_terbit">
                    </div>
                    <div class="mb-3">
                        <label for="isbn" class="form-label">ISBN</label>
                        <input type="text" name="ISBN" readonly class="form-control" id="detail_isbn">
                    </div>
                    <div class="mb-3">
                        <label for="stok" class="form-label">Stok</label>
                        <input type="text" name="stok" readonly class="form-control" id="detail_stok">
                    </div>
                </div>
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

$script = "
<script>
    $(document).ready(function() {
        $('.hapus_buku').click(function(){
            const id_buku = $(this).data('id_buku');
            const judul_buku = $(this).data('judul_buku');
            const gambar_buku = '../assets/images/foto-buku/' + $(this).data('gambar');

            $('#hapus_id_buku').val(id_buku);
            $('#hapus_judul_buku').val(judul_buku);
            $('#hapus_gambar_buku').attr('src', gambar_buku);

            $('#hapusModal').modal('show');
        });

        $('.ubah_buku').click(function(){
            const id_buku = $(this).data('id_buku');
            const judul_buku = $(this).data('judul_buku');
            const penulis = $(this).data('penulis');
            const penerbit = $(this).data('penerbit');
            const tahun_terbit = $(this).data('tahun_terbit');
            const isbn = $(this).data('isbn');
            const stok = $(this).data('stok');
            const gambar_buku = $(this).data('gambar');
            const url = '../assets/images/foto-buku/' + gambar_buku;


            $('#ubah_id_buku').val(id_buku);
            $('#ubah_judul_buku').val(judul_buku);
            $('#ubah_penulis').val(penulis);
            $('#ubah_penerbit').val(penerbit);
            $('#ubah_tahun_terbit').val(tahun_terbit);
            $('#ubah_isbn').val(isbn);
            $('#ubah_stok').val(stok);
            $('#ubah_gambarLama').val(gambar_buku);
            $('#ubah_gambar_buku').attr('src',url);

            $('#ubahModal').modal('show');
        });

        $('.detail_buku').click(function(){
            const id_buku = $(this).data('id_buku');
            const judul_buku = $(this).data('judul_buku');
            const penulis = $(this).data('penulis');
            const penerbit = $(this).data('penerbit');
            const tahun_terbit = $(this).data('tahun_terbit');
            const isbn = $(this).data('isbn');
            const stok = $(this).data('stok');
            const gambar_buku = $(this).data('gambar');
            const url = '../assets/images/foto-buku/' + gambar_buku;


            $('#detail_id_buku').val(id_buku);
            $('#detail_judul_buku').val(judul_buku);
            $('#detail_penulis').val(penulis);
            $('#detail_penerbit').val(penerbit);
            $('#detail_tahun_terbit').val(tahun_terbit);
            $('#detail_isbn').val(isbn);
            $('#detail_stok').val(stok);
            $('#detail_gambar_buku').attr('src', url);

            $('#detailModal').modal('show');
        });
    });
</script>
";

include('../layouts/footer.php');

?>