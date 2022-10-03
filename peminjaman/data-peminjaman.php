<?php

require '../function.php';
require 'function.php';

// check login
checkLogin();

// pagination
$jmlData = 5;
$query = mysqli_query($conn, "SELECT * FROM peminjaman");
$totalData = mysqli_num_rows($query);
$jmlHalaman = ceil($totalData / $jmlData);
$halamanAktif = (isset($_GET['halaman'])) ? $_GET['halaman'] : 1;
$awalData = ($jmlData * $halamanAktif) - $jmlData;

$dataPeminjaman = mysqli_query($conn, "SELECT id_pinjam, id_buku, judul_buku, id_anggota, nama, tgl_pinjam, peminjaman.status FROM peminjaman LEFT JOIN buku on peminjaman.id_buku = buku.id LEFT JOIN anggota on peminjaman.id_anggota = anggota.id ORDER BY tgl_pinjam DESC LIMIT $awalData, $jmlData");

if ((mysqli_num_rows($query) == 0)) {
    $_SESSION['error_message'] = "Tidak Ada Data";
}

// jika tombol cari diklik
if (isset($_GET['keyword'])) {
    $dataPeminjaman = cari($_GET['keyword']);
}

// BUAT ID Pinjam 
$noBaru = date('Ymd') . rand();


?>

<!-- HEADER -->
<?php
$title = 'Transaksi Peminjaman';
$style = [
    '../css/style.css',
    '../assets/bootstrap/css/bootstrap.min.css'
];
include("../layouts/header.php")
?>

<!-- ASIDE -->
<?php
$module = "dataPeminjaman";
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

<!-- MAIN -->
<main class="content">
    <!-- Header Content -->
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
                    <button class="btn btn-outline-secondary" onclick="document.location.href = '/perpus/peminjaman/data-peminjaman.php'" type="button">Reset</button>
                </div>
            </div>
            </form>
        </div>

        <div class="table-responsive">
            <?= responseError(); ?>

            <?php if (mysqli_num_rows($dataPeminjaman) >= 1) { ?>
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th scope="col">No.</th>
                            <th scope="col">Id Pinjam</th>
                            <th scope="col">Judul Buku</th>
                            <th scope="col">Tanggal Peminjaman</th>
                            <th scope="col">Nama Anggota</th>
                            <th scope="col">status</th>
                            <th scope="col" class="text-center">Manage</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $i = $awalData + 1; ?>
                        <?php while ($row = mysqli_fetch_array($dataPeminjaman)) : ?>
                            <tr>
                                <th><?= $i; ?>.</th>
                                <td><?= $row['id_pinjam']; ?></td>
                                <td><?= $row['judul_buku']; ?></td>
                                <td><?= $row['tgl_pinjam']; ?></td>
                                <td><?= $row['nama']; ?></td>
                                <td><?= $row['status']; ?></td>

                                <!-- jika status = 'Mengembalikan' hilangkan tombol edit -->
                                <?php if ($row['status'] == 'Meminjam') { ?>
                                    <td class="d-flex justify-content-center">
                                        <a href="javascript:void(0)" class="btn btn-warning mx-1 pinjam" data-id_pinjam="<?= $row['id_pinjam']; ?>" data-id_buku="<?= $row['id_buku']; ?>" data-tgl_pinjam="<?= $row['tgl_pinjam']; ?>"  data-id_anggota="<?= $row['id_anggota']; ?>">
                                            <img width="20" he="20" src="../assets/images/icon/pen.svg" alt="">
                                        </a>
                                    </td>
                                <?php } else { ?>
                                    <td class="d-flex justify-content-center"></td>
                                <?php } ?>
                            </tr>

                            <?php $i++; ?>

                        <?php endwhile; ?>
                    </tbody>
                </table>
        </div>

        <!-- pagination -->
        <div class="d-flex justify-content-center">
            <?= pagination($jmlHalaman, $halamanAktif) ?>
        </div> <?php } ?>
    </section>
</main>

<!-- modal tambah -->
<div class="modal fade" id="tambahModal" tabindex="-1" role="dialog" aria-labelledby="tambahModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="tambahModalLabel">Tambah Data Peminjaman</h5>
                <img src="../assets/images/icon/x-lg.svg" width="20" height="20" alt="" data-bs-dismiss="modal">
            </div>
            <form action="function.php" method="post" enctype="multipart/form-data">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="id_pinjam" class="form-label">Id Pinjam</label>
                        <input type="text" name="id_pinjam" class="form-control" value="<?= $noBaru ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="id_buku" class="form-label">Judul Buku</label>
                        <?php
                        include '../koneksi.php';
                        echo "<select class='form-control' name='id_buku'>";
                        echo "<option selected> --- Pilih Judul Buku ---</option>";
                        $bukus = mysqli_query($conn, "SELECT * FROM buku WHERE stok > 0");
                        while ($data = mysqli_fetch_array($bukus)) {
                            echo "<option value=$data[id]>$data[judul_buku]</option>";
                        }
                        echo "</select>";

                        ?>
                    </div>
                    <div class="mb-3">
                        <label for="tgl_pinjam" class="form-label">Tanggal Peminjaman</label>
                        <input type="date" name="tgl_pinjam" class="form-control" id="tgl_pinjam" required>
                    </div>
                    <div class="mb-3">
                        <label for="id" class="form-label">Nama Anggota</label>
                        <?php
                        include '../koneksi.php';
                        echo "<select class='form-control' name='id'>";
                        echo "<option selected> --- Pilih Nama Anggota ---</option>";
                        $anggotas = mysqli_query($conn, "SELECT * FROM anggota");
                        while ($row = mysqli_fetch_array($anggotas)) {
                            echo "<option value=$row[id]>$row[nama]</option>";
                        }
                        echo "</select>";
                        ?>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="submit" name="tambah" class="btn btn-primary">Tambah Data</button>
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
                <img src="../assets/images/icon/x-lg.svg" width="20" height="20" alt="" data-bs-dismiss="modal">
            </div>
            <form action="" method="post" enctype="multipart/form-data">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="id_pinjam" class="form-label">Id Pinjam</label>
                        <input type="text" name="id_pinjam" id="ubah_id_pinjam" class="form-control" readonly>
                    </div>
                    <!-- buku yang dipilih di select menu, akan mengurangi jumlah stok yang ada
                    buku yang ga dipilih di select menu, akan menambahkan jumlah stok yang ada -->
                    <div class="mb-3">
                        <label for="id_buku" class="form-label">Judul Buku</label>
                        <select class='form-control' name='id_buku' id="ubah_id_buku">
                            <option value="0">--- Pilih ---</option>
                            <?php
                            include '../koneksi.php';
                            $bukus = mysqli_query($conn, "SELECT * FROM buku");
                            while ($data = mysqli_fetch_array($bukus)) {
                                echo "<option value=$data[id]>$data[judul_buku]</option>";
                            }
                            ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="tgl_pinjam" class="form-label">Tanggal Peminjaman</label>
                        <input type="date" name="tgl_pinjam" class="form-control" id="ubah_tgl_pinjam" required>
                    </div>
                    <div class="mb-3">
                        <label for="id" class="form-label">Nama Anggota</label>
                        <select class='form-control' name='id_anggota' id="ubah_id_anggota">
                            <option value="0">--- Pilih ---</option>

                            <?php
                            include '../koneksi.php';
                            $anggotas = mysqli_query($conn, "SELECT * FROM anggota");
                            while ($data = mysqli_fetch_array($anggotas)) {
                                echo "<option value=$data[id]>$data[nama]</option>";
                            }
                            ?>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary" name="ubah">Ubah Data</button>
                </div>
            </form>
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
        $('.pinjam').click(function(){
            const id_pinjam = $(this).data('id_pinjam');
            const id_buku = $(this).data('id_buku');
            const tgl_pinjam = $(this).data('tgl_pinjam');
            const id_anggota = $(this).data('id_anggota');

            $('#ubah_id_pinjam').val(id_pinjam);
            $('#ubah_id_buku').val(id_buku);
            $('#ubah_tgl_pinjam').val(tgl_pinjam);
            $('#ubah_id_anggota').val(id_anggota);

            $('#ubahModal').modal('show');
        })
    });
</script>
";
include('../layouts/footer.php');

?>