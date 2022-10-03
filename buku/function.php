<?php

// fix ubah data gambar jika yg dikirim bukan gambar

require '../koneksi.php';

if (isset($_POST['tambah'])) {

    $judul_buku = htmlspecialchars($_POST['judul_buku']);
    $penulis = htmlspecialchars($_POST['penulis']);
    $penerbit = htmlspecialchars($_POST['penerbit']);
    $tahun_terbit = htmlspecialchars($_POST['tahun_terbit']);
    $isbn = htmlspecialchars($_POST['isbn']);
    $stok = htmlspecialchars($_POST['stok']);
    $tgl_input = $_POST['tgl_input'];

    $gambar = upload();
    if (!$gambar) {
        return false;
    }

    $query = mysqli_query($conn, "INSERT INTO buku 
                VALUES 
                (null, '$judul_buku', '$penulis', '$penerbit', '$tahun_terbit', '$isbn', '$gambar', '$stok', '$tgl_input')
            ");

    if ($query) {
        $_SESSION['success_message'] = "Berhasil menambahkan buku baru";
        header("Location: data-buku.php");
        exit();
    } else {
        echo "
             <script> 
                 alert('Gagal menambahkan buku')
                 window.location.href = 'data-buku.php'
             </script>
        ";
    }

    
}

if (isset($_POST['ubah'])) {
    $id = $_POST['id'];
    $judul_buku = htmlspecialchars($_POST['judul_buku']);
    $penulis = htmlspecialchars($_POST['penulis']);
    $penerbit = htmlspecialchars($_POST['penerbit']);
    $tahun_terbit = htmlspecialchars($_POST['tahun_terbit']);
    $isbn = htmlspecialchars($_POST['ISBN']);
    $stok = htmlspecialchars($_POST['stok']);
    $gambarLama = htmlspecialchars($_POST['gambarLama']);

    if ($_FILES['gambar']['error'] === 4) {
        $gambar = $gambarLama;
    } else {
        $gambar = upload();
        if (!$gambar) {
            return false;
        }
    }

    $query = mysqli_query($conn, "UPDATE buku 
                SET
                judul_buku = '$judul_buku', 
                penulis = '$penulis',
                penerbit = '$penerbit', 
                tahun_terbit = '$tahun_terbit', 
                ISBN = '$isbn', 
                gambar = '$gambar',
                stok = '$stok'
                WHERE id = $id
            ");
    if ($query) {
        $_SESSION['success_message'] = "Berhasil mengubah data buku";
        header("Location: data-buku.php");
        exit();
    } else {
        echo "
             <script> 
                 alert('Gagal mengubah data buku')
                 window.location.href = 'data-buku.php'
             </script>
        ";
    }
    
}

// hapus
if (isset($_POST['hapus'])) {
    $query = mysqli_query($conn, "DELETE FROM buku WHERE id = '$_POST[id]'");
    
    if($query){
        $_SESSION['success_message'] = "Berhasil menghapus buku";
        header("Location: data-buku.php");
        exit();
    } else {
        echo "
             <script> 
                 alert('Gagal menghapus data buku')
                 window.location.href = 'data-buku.php'
             </script>
        ";
    }
    
}

function upload(){
    $namaFile = $_FILES['gambar']['name'];
    $sizeFile = $_FILES['gambar']['size'];
    $tmpName = $_FILES['gambar']['tmp_name'];

    // cek apakah yang diupload adalah gambar
    $ekstensiGambarValid = ['jpg', 'jpeg', 'png'];
    $ekstensiGambar = explode('.', $namaFile);
    $ekstensiGambar = strtolower(end($ekstensiGambar));

    if (!in_array($ekstensiGambar, $ekstensiGambarValid)) {
        echo "
            <script> 
                alert('Yang Anda upload bukan gambar');
                window.location.href = 'data-buku.php';
            </script>
        ";
        return false;
    }

    // cek jika ukuran gambar terlalu besar
    if ($sizeFile > 2000000) {
        echo "
            <script> 
                alert('ukuran gambar terlalu besar, maksimal 2 Mb');
                window.location.href = 'data-buku.php';
            </script>
        ";
        return false;
    }

    // lolos validasi, gambar diupload
    move_uploaded_file($tmpName, '../assets/images/foto-buku/' . $namaFile);
    return $namaFile;
}

// fitur cari
function cari($keyword){
    global $conn;

    $query = "SELECT * FROM buku WHERE 
                judul_buku LIKE '%$keyword%' OR
                penulis LIKE '%$keyword%' OR
                penerbit LIKE '%$keyword%' OR
                tahun_terbit LIKE '%$keyword%' OR
                ISBN LIKE '%$keyword%'
            ";

    $result = mysqli_query($conn, $query);
    return $result;
}