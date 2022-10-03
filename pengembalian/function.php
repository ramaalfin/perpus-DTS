<?php 
require '../koneksi.php';

if (isset($_POST['submit'])) {
    $date = date('Ymd');
    $id_kembali = $_POST['id_kembali'];
    $id_pinjam = $_POST['id_pinjam'];
    $id_anggota = $_POST['id_anggota'];
    $tgl_pinjam = $_POST['tgl_pinjam'];
    $tgl_kembali = $_POST['tgl_kembali'];

    $query = mysqli_query($conn, "INSERT INTO pengembalian 
                VALUES 
                ('$id_kembali', '$id_pinjam', '$id_anggota', '$tgl_kembali')
            ");

    $query_stok = mysqli_fetch_array(mysqli_query($conn, "SELECT id_buku FROM peminjaman WHERE id_pinjam = '$id_pinjam'"));
    $stok = mysqli_query($conn, "UPDATE buku SET stok = stok + 1 WHERE id = '$query_stok[id_buku]' ");
    $anggota = mysqli_query($conn, "UPDATE anggota SET anggota.status = 'Mengembalikan' WHERE id = '$id_anggota'");
    $peminjaman = mysqli_query($conn, "UPDATE peminjaman SET peminjaman.status = 'Mengembalikan' WHERE id_pinjam = '$id_pinjam'");

    echo "
    <script>
        alert('Pengembalian Buku Berhasil!');
        window.location.href = 'data-pengembalian.php';
    </script>";

}

// fitur cari
function cari($keyword)
{
    global $conn;

    $query = "SELECT * FROM pengembalian 
        LEFT JOIN peminjaman on peminjaman.id_pinjam = pengembalian.id_pinjam 
        LEFT JOIN buku on peminjaman.id_buku = buku.id 
        LEFT JOIN anggota on pengembalian.id_anggota = anggota.id 
        WHERE (id_kembali LIKE '%$keyword%' OR 
        -- id_pinjam LIKE '%$keyword%' OR 
        judul_buku LIKE '%$keyword%' OR 
        nama LIKE '%$keyword%') 
        AND 
        (peminjaman.status = 'Mengembalikan')";

    $result = mysqli_query($conn, $query);
    return $result;
}
