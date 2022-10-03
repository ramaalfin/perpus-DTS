<?php 

require '../koneksi.php';
require '../dompdf/autoload.inc.php';

use Dompdf\Dompdf;

// laporan data anggota yang terdaftar
if (isset($_POST['anggota'])) {

    $tgl_awal = $_POST['tgl_awal'];
    $tgl_akhir = $_POST['tgl_akhir'];

    $query = mysqli_query($conn, "SELECT * FROM anggota WHERE (anggota.tgl_daftar BETWEEN '$tgl_awal' AND '$tgl_akhir') ORDER BY nama");

    $dompdf = new Dompdf();
    ob_start();
    require 'tampil-laporan-data-anggota.php';
    $html = ob_get_contents();
    ob_get_clean();

    $dompdf->loadHtml($html);

    $dompdf->setPaper('A4', 'portrait');
    
    $dompdf->render();
    $dompdf->stream('laporan-data-anggota.pdf', ['Attachment'=>false]);
}


// laporan data buku yang sudah tersedia
if (isset($_POST['buku'])) {

    $tgl_awal = $_POST['tgl_awal'];
    $tgl_akhir = $_POST['tgl_akhir'];

    $query = mysqli_query($conn, "SELECT * FROM buku WHERE (buku.tgl_input BETWEEN '$tgl_awal' AND '$tgl_akhir') ORDER BY judul_buku");

    $dompdf = new Dompdf();
    ob_start();
    require 'tampil-laporan-data-buku.php';
    $html = ob_get_contents();
    ob_get_clean();

    $dompdf->loadHtml($html);

    $dompdf->setPaper('A4', 'portrait');
    
    $dompdf->render();
    $dompdf->stream('laporan-data-buku.pdf', ['Attachment'=>false]);
}


// "SELECT * FROM pengembalian LEFT JOIN peminjaman on peminjaman.id_pinjam = pengembalian.id_pinjam LEFT JOIN buku on peminjaman.id_buku = buku.id LEFT JOIN anggota on peminjaman.id_anggota = anggota.id WHERE (peminjaman.tgl_pinjam BETWEEN '$tgl_awal' AND '$tgl_akhir') AND (peminjaman.status = 'Mengembalikan')"