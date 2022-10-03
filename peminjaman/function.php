<?php
require '../koneksi.php';

if (isset($_POST['tambah'])) {
    $id_pinjam = $_POST['id_pinjam'];
    $id_buku = $_POST['id_buku'];
    $tgl_pinjam = $_POST['tgl_pinjam'];
    $id_anggota = $_POST['id'];
    $status = 'Meminjam';

    $jumlahStok = mysqli_num_rows(mysqli_query($conn, "SELECT stok FROM buku WHERE id = $id_buku AND stok > 0"));

    if($jumlahStok) {
        $query = mysqli_query($conn, "INSERT INTO peminjaman 
                VALUES 
                ('$id_pinjam', '$id_buku', '$id_anggota', '$tgl_pinjam', '$status')
            ");

        $query_stok = mysqli_fetch_array(mysqli_query($conn, "SELECT id_buku FROM peminjaman WHERE id_pinjam = '$id_pinjam'"));
        $stok = mysqli_query($conn, "UPDATE buku SET stok = stok - 1 WHERE id = '$query_stok[id_buku]' ");
        $anggota = mysqli_query($conn, "UPDATE anggota SET status = '$status'");
        

        if ($query) {
            $_SESSION['success_message'] = "Berhasil menambahkan data peminjaman";
            header("Location: data-peminjaman.php");
            exit();
        } else {
            echo "
                 <script> 
                     alert('Gagal menambahkan data peminjaman')
                     window.location.href = 'data-peminjaman.php'
                 </script>
            ";
        }
    
    } else {

        echo "
             <script> 
                 alert('Stok Buku Kosong')
                 window.location.href = 'data-peminjaman.php'
             </script>
        ";
    }

    
}

// ubah
if (isset($_POST['ubah'])) {
    $id_pinjam = $_POST['id_pinjam'];
    $id_buku = $_POST['id_buku'];
    $tgl_pinjam = $_POST['tgl_pinjam'];
    $id_anggota = $_POST['id_anggota'];
    $jmlStok = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM buku WHERE id = $id_buku AND stok > 0"));
    $updateTglPinjam = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM buku WHERE id = $id_buku AND stok = 0"));
    
    if ($jmlStok) {
        $updateStokBukuLama = mysqli_query($conn, "UPDATE buku SET stok = stok + 1 WHERE id = (SELECT id_buku FROM peminjaman WHERE id_pinjam = '$id_pinjam')");
        $updateStokBukuBaru = mysqli_query($conn, "UPDATE buku SET stok = stok - 1 WHERE id = $id_buku");
         
        $update = mysqli_query($conn, "UPDATE peminjaman SET id_buku = '$id_buku', tgl_pinjam = '$tgl_pinjam', id_anggota = '$id_anggota' WHERE id_pinjam = $id_pinjam ");
    
        if ($update) {
            $_SESSION['success_message'] = "Berhasil mengubah data peminjaman";
            header("Location: data-peminjaman.php");
            exit();
        } else {
            echo "
             <script> 
                 alert('Gagal mengubah data peminjaman')
                 window.location.href = 'data-peminjaman.php'
             </script>
        ";
        }
    } else {
        echo "
        <script> 
            alert('Stok Buku Kosong')
            window.location.href = 'data-peminjaman.php'
        </script>
        ";
    }
    
    if ( $updateTglPinjam ) {
        $update = mysqli_query($conn, "UPDATE peminjaman SET id_buku = '$id_buku', tgl_pinjam = '$tgl_pinjam', id_anggota = '$id_anggota' WHERE id_pinjam = $id_pinjam ");
    
        if ($update) {
            $_SESSION['success_message'] = "Berhasil mengubah data peminjaman";
            header("Location: data-peminjaman.php");
            exit();
        } else {
            echo "
             <script> 
                 alert('Gagal mengubah data peminjaman')
                 window.location.href = 'data-peminjaman.php'
             </script>
        ";
        }
    } 

}

// fitur cari
function cari($keyword)
{
    global $conn;

    $query = "SELECT * FROM peminjaman LEFT JOIN buku on peminjaman.id_buku = buku.id LEFT JOIN anggota on peminjaman.id_anggota = anggota.id WHERE (id_pinjam LIKE '%$keyword%' OR judul_buku LIKE '%$keyword%' OR nama LIKE '%$keyword%')";

    $result = mysqli_query($conn, $query);
    return $result;
}
