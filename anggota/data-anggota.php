<?php

require '../function.php';
require 'function.php';

// check login
checkLogin();

// pagination
$jmlData = 5;
$query = mysqli_query($conn, "SELECT * FROM anggota");
$totalData = mysqli_num_rows($query);
$jmlHalaman = ceil($totalData / $jmlData);
$halamanAktif = (isset($_GET['halaman'])) ? $_GET['halaman'] : 1;
$awalData = ($jmlData * $halamanAktif) - $jmlData;

$dataAnggota = mysqli_query($conn, "SELECT * FROM anggota LIMIT $awalData, $jmlData");

if ((mysqli_num_rows($query) == 0)) {
    $_SESSION['error_message'] = "Tidak Ada Data";
}

// jika tombol cari diklik
if (isset($_GET['keyword'])) {
    $dataAnggota = cari($_GET['keyword']);
}


?>

<!-- HEADER -->
<?php
$title = 'Data Anggota';
$style = [
    '../css/style.css',
    '../assets/bootstrap/css/bootstrap.min.css'
];
include("../layouts/header.php")
?>

<!-- ASIDE -->
<?php
$module = "dataAnggota";
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
    <!-- Header Content -->
    <?php include("../layouts/header-content.php") ?>

    <section class="d-flex flex-column gap-4">
        <?= responseSuccess(); ?>

        <!-- tambahModal -->
        <div class="d-flex justify-content-between align-items-center gap-2 ">
            <a href="#" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#tambahModal">
                <img src="../assets/images/icon/plus-lg.svg" width="24" height="24" alt="">
            </a>
            <form>
                <div class="input-group gap-2">
                    <input class="form-control" type="text" name="keyword" autocomplete="off" size="20" value="<?= isset($_GET['keyword']) ? $_GET['keyword'] : ''; ?>">
                    <div class="input-group-append">

                        <button class="btn btn-outline-secondary" type="submit">cari</button>
                        <button class="btn btn-outline-secondary" onclick="document.location.href = '/perpus/anggota/data-anggota.php'" type="button">Reset</button>
                    </div>
                </div>
            </form>

        </div>
        <div class="table-responsive">
            <?= responseError(); ?>

            <?php if (mysqli_num_rows($dataAnggota) >= 1) { ?>

                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th scope="col">No.</th>
                            <th scope="col">Nama Anggota</th>
                            <th scope="col">Email</th>
                            <th scope="col">Jenis Kelamin</th>
                            <th scope="col">Alamat</th>
                            <th scope="col">status</th>
                            <th scope="col" class="text-center">Manage</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $i = $awalData + 1; ?>
                        <?php while ($data = mysqli_fetch_array($dataAnggota)) : ?>
                            <tr>
                                <th><?= $i; ?>.</th>
                                <td><?= $data['nama']; ?></td>
                                <td><?= $data['email']; ?></td>
                                <td><?= $data['jenis_kelamin']; ?></td>
                                <td><?= $data['alamat']; ?></td>
                                <td><?= $data['status']; ?></td>
                                <td class="d-flex justify-content-center">
                                    <a href="#" class="btn btn-info mx-1 detail_anggota" data-id_anggota="<?= $data['id'] ?>" data-nama_anggota="<?= $data['nama'] ?>" data-email_anggota="<?= $data['email'] ?>" data-jenis_kelamin="<?= $data['jenis_kelamin'] ?>" data-status_anggota="<?= $data['status'] ?>" data-alamat="<?= $data["alamat"] ?>" data-gambar="<?= $data['gambar'] ?>">
                                        <img width="20" height="20" src="../assets/images/icon/eye.svg" alt="">
                                    </a>

                                    <a href="#" class="btn btn-warning mx-1 ubah_anggota" data-id_anggota="<?= $data['id'] ?>" data-nama_anggota="<?= $data['nama'] ?>" data-email_anggota="<?= $data['email'] ?>" data-jenis_kelamin="<?= $data['jenis_kelamin'] ?>" data-status_anggota="<?= $data['status'] ?>" data-alamat="<?= $data["alamat"] ?>" data-gambar="<?= $data['gambar'] ?>">
                                        <img width="20" he="20" src="../assets/images/icon/pen.svg" alt="">
                                    </a>

                                    <a href="javascript:void(0)" class="btn btn-danger hapus_anggota" data-id_anggota="<?= $data['id'] ?>" data-nama_anggota="<?= $data['nama'] ?>" data-gambar_anggota="<?= $data['gambar'] ?>">
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
                <h5 class="modal-title" id="tambahModalLabel">Tambah Anggota Baru</h5>
                <img src="../assets/images/icon/x-lg.svg" width="20" height="20" alt="" data-bs-dismiss="modal">
            </div>
            <form action="function.php" method="post" enctype="multipart/form-data">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" name="email" class="form-control" id="email" required>
                    </div>
                    <div class="mb-3">
                        <label for="nama" class="form-label">Nama Anggota</label>
                        <input type="text" name="nama" class="form-control" id="nama" required>
                    </div>
                    <div class="mb-3">
                        <label for="jenis_kelamin" class="form-label">Jenis Kelamin</label>
                        <select name="jenis_kelamin" id="jenis_kelamin" class="form-control" required>
                            <option value="">--- pilih jenis kelamin ---</option>
                            <option value="Laki-laki">Laki-laki</option>
                            <option value="Perempuan">Perempuan</option>
                        </select>
                    </div>
                    <input type="hidden" name="status" class="form-control" id="status" readonly>
                    <div class="mb-3">
                        <label for="alamat" class="form-label">Alamat</label>
                        <textarea class="form-control" name="alamat" id="alamat" cols="30" rows="2" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="gambar" class="form-label">Gambar (Maks. 2 Mb)</label>
                        <input type="file" name="gambar" class="form-control" id="gambar" required>
                    </div>
                    <input type="hidden" name='tgl_daftar' value="<?= date('Ymd'); ?>">
                </div>
                <div class="modal-footer">
                    <button type="submit" name="tambah" class="btn btn-primary">Tambah Anggota</button>
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
                <h5 class="modal-title" id="ubahModalLabel">Ubah Data Anggota</h5>
                <img src="../assets/images/icon/x-lg.svg" width="22" height="22" alt="" data-bs-dismiss="modal">
            </div>
            <form action="" method="post" enctype="multipart/form-data">
                <div class="modal-body">
                    <input type="hidden" name="id" id="ubah_id_anggota">
                    <input type="hidden" name="gambarLama" id="ubah_gambarLama">
                    <div class="mb-3">
                        <label for="email" class="form-label">Email Address</label>
                        <input type="email" name="email" class="form-control" id="ubah_email_anggota" required>
                    </div>
                    <div class="mb-3">
                        <label for="nama" class="form-label">Nama Anggota</label>
                        <input type="text" name="nama" class="form-control" id="ubah_nama_anggota" required>
                    </div>
                    <div class="mb-3">
                        <label for="jenis_kelamin" class="form-label">Jenis Kelamin</label>
                        <select name="jenis_kelamin" id="ubah_jenis_kelamin" class="form-control" required>
                            <option value="0">--- pilih jenis kelamin ---</option>
                            <?php
                            include '../koneksi.php';
                            $jk = mysqli_query($conn, "SELECT jenis_kelamin FROM anggota");
                            while ($data = mysqli_fetch_array($jk)) {
                                echo "<option value=$data[jenis_kelamin]>$data[jenis_kelamin]</option>";
                            }
                            ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="status" class="form-label">Status</label>
                        <input type="text" name="status" class="form-control" id="ubah_status_anggota" readonly>
                    </div>
                    <div class="mb-3">
                        <label for="alamat" class="form-label">Alamat</label>
                        <input class="form-control" name="alamat" id="ubah_alamat_anggota" required></input>
                    </div>
                    <div class="mb-3">
                        <label for="gambar" class="form-label">Gambar (Maks. 2 Mb)</label><br>
                        <img alt="" width="300" id="ubah_gambar" class="my-md-4 d-flex m-auto">
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
                <h5 class="modal-title" id="hapusModalLabel">Hapus Data Anggota</h5>
                <img src="../assets/images/icon/x-lg.svg" width="20" height="20" alt="" data-bs-dismiss="modal">
            </div>
            <div class="modal-body">
                <h5 class="text-center mb-4">Apakah Anda yakin ingin menghapus data?</h5>
                <form action="" method="post" enctype="multipart/form-data">
                    <input type="hidden" name="id" readonly class="form-control" id="hapus_id_anggota" required>

                    <div class="mb-3 text-center">
                        <img id="hapus_gambar_anggota" alt="" width="200" class="my-md-4">
                    </div>

                    <div class="mb-3">
                        <label for="nama" class="form-label">Nama Anggota</label>
                        <input type="text" name="nama" class="form-control" id="hapus_nama_anggota" readonly>
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
                <h5 class="modal-title" id="detailModalLabel">Detail Anggota</h5>
                <img src="../assets/images/icon/x-lg.svg" width="20" height="20" alt="" data-bs-dismiss="modal">
            </div>
            <div class="modal-body">
                <div class="col text-center">
                    <img alt="" width="250" class="mt-md-4 mb-4" id="detail_gambar">
                </div>
                <div class="col">
                    <div class="mb-3">
                        <label for="email" class="form-label">Email Address</label>
                        <input type="email" name="email" class="form-control" id="detail_email_anggota" readonly>
                    </div>
                    <div class="mb-3">
                        <label for="nama" class="form-label">Nama Anggota</label>
                        <input type="text" name="nama" class="form-control" id="detail_nama_anggota" readonly>
                    </div>
                    <div class="mb-3">
                        <label for="jenis_kelamin" class="form-label">Jenis Kelamin</label>
                        <input type="text" name="jenis_kelamin" class="form-control" id="detail_jenis_kelamin" readonly>
                    </div>
                    <div class="mb-3">
                        <label for="status" class="form-label">status</label>
                        <input type="text" name="status" class="form-control" id="detail_status_anggota" readonly>
                    </div>
                    <div class="mb-3">
                        <label for="alamat" class="form-label">Alamat</label>
                        <input type="text" name="alamat" class="form-control" id="detail_alamat_anggota" readonly>
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
    '../assets/bootstrap/js/bootstrap.min.js',
    '../assets/bootstrap/js/bootstrap.bundle.min.js',
    '../js/index.js'
];

$script = "
<script>
    $(document).ready(function() {
        $('.hapus_anggota').click(function(){
            const id_anggota = $(this).data('id_anggota');
            const nama_anggota = $(this).data('nama_anggota');
            const gambar_anggota = '../assets/images/anggota/' + $(this).data('gambar_anggota');

            $('#hapus_id_anggota').val(id_anggota);
            $('#hapus_nama_anggota').val(nama_anggota);
            $('#hapus_gambar_anggota').attr('src', gambar_anggota);

            $('#hapusModal').modal('show');
        });

        $('.ubah_anggota').click(function(){
            const id_anggota = $(this).data('id_anggota');
            const nama_anggota = $(this).data('nama_anggota');
            const email_anggota = $(this).data('email_anggota');
            const jenis_kelamin = $(this).data('jenis_kelamin');
            const status_anggota = $(this).data('status_anggota');
            const alamat = $(this).data('alamat');
            const gambar = $(this).data('gambar');
            const url = '../assets/images/anggota/' + gambar;

            $('#ubah_id_anggota').val(id_anggota);
            $('#ubah_nama_anggota').val(nama_anggota);
            $('#ubah_email_anggota').val(email_anggota);
            $('#ubah_jenis_kelamin').val(jenis_kelamin);
            $('#ubah_status_anggota').val(status_anggota);
            $('#ubah_alamat_anggota').val(alamat);
            $('#ubah_gambarLama').val(gambar);
            $('#ubah_gambar').attr('src',url);

            $('#ubahModal').modal('show');
        });

        $('.detail_anggota').click(function(){
            const id_anggota = $(this).data('id_anggota');
            const nama_anggota = $(this).data('nama_anggota');
            const email_anggota = $(this).data('email_anggota');
            const jenis_kelamin = $(this).data('jenis_kelamin');
            const status_anggota = $(this).data('status_anggota');
            const alamat = $(this).data('alamat');
            const gambar = $(this).data('gambar');
            const url = '../assets/images/anggota/' + gambar;

            // const gambar = '../assets/images/anggota/' + $(this).data('gambar');

            $('#detail_id_anggota').val(id_anggota);
            $('#detail_nama_anggota').val(nama_anggota);
            $('#detail_email_anggota').val(email_anggota);
            $('#detail_jenis_kelamin').val(jenis_kelamin);
            $('#detail_status_anggota').val(status_anggota);
            $('#detail_alamat_anggota').val(alamat);
            $('#detail_gambar').attr('src',url);

            $('#detailModal').modal('show');
        });
    });
</script>
";

include('../layouts/footer.php');


?>