<?php

require '../koneksi.php';

// tambah
if (isset($_POST['tambah'])) {

    $email = htmlspecialchars($_POST['email']);
    $nama = htmlspecialchars($_POST['nama']);
    $jenis_kelamin = htmlspecialchars($_POST['jenis_kelamin']);
    $status = null;
    $alamat = htmlspecialchars($_POST['alamat']);
    $tgl_daftar = $_POST['tgl_daftar'];

    $gambar = upload();
    if (!$gambar) {
        return false;
    }

    $query = mysqli_query($conn, "INSERT INTO anggota 
                VALUES 
                (null, '$email', '$nama', '$jenis_kelamin', '$status', '$alamat', '$gambar', '$tgl_daftar')
            ");

    if ($query) {
        $_SESSION['success_message'] = "Berhasil menambahkan anggota baru";
        header("Location: data-anggota.php");
        exit();
    } else {
        echo "
             <script> 
                 alert('Gagal menambahkan anggota')
                 window.location.href = 'data-anggota.php'
             </script>
        ";
    }
}

// ubah
if (isset($_POST['ubah'])) {
    $id = $_POST['id'];
    $email = htmlspecialchars($_POST['email']);
    $nama = htmlspecialchars($_POST['nama']);
    $jenis_kelamin = htmlspecialchars($_POST['jenis_kelamin']);
    $status = htmlspecialchars($_POST['status']);
    $alamat = htmlspecialchars($_POST['alamat']);
    $gambarLama = htmlspecialchars($_POST['gambarLama']);

    if ($_FILES['gambar']['error'] === 4) {
        $gambar = $gambarLama;
        
    } else {
        $gambar = upload();
        if (!$gambar) {
            return false;
        }
    }

    $query = mysqli_query($conn, "UPDATE anggota 
                SET
                email = '$email', 
                nama = '$nama',
                jenis_kelamin = '$jenis_kelamin', 
                status = '$status', 
                alamat = '$alamat', 
                gambar = '$gambar'
                WHERE id = $id
            ");

    if ($query) {
        $_SESSION['success_message'] = "Berhasil mengubah data anggota";
        header("Location: data-anggota.php");
        exit();
    } else {
        echo "
         <script> 
             alert('Gagal mengubah data anggota')
             window.location.href = 'data-anggota.php'
         </script>
    ";
    }
}

// hapus
if (isset($_POST['hapus'])) {
    $query = mysqli_query($conn, "DELETE FROM anggota WHERE id = '$_POST[id]'");

    if ($query) {
        $_SESSION['success_message'] = "Berhasil menghapus anggota";
        header("Location: data-anggota.php");
        exit();
    } else {
        echo "
             <script> 
                 alert('Gagal menghapus data anggota')
                 window.location.href = 'data-anggota.php'
             </script>
        ";
    }
}

function upload()
{
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
                window.location.href =  'data-anggota.php';
            </script>
        ";
        return false;
    }

    // cek jika ukuran gambar terlalu besar
    if ($sizeFile > 2000000) {
        echo "
            <script> 
                alert('ukuran gambar terlalu besar');
                window.location.href =  'data-anggota.php';
            </script>
        ";
        return false;
    }

    // lolos validasi, gambar diupload
    move_uploaded_file($tmpName, '../assets/images/anggota/' . $namaFile);
    return $namaFile;
}

function cari($keyword){
    global $conn;

    $query = "SELECT * FROM anggota WHERE 
                nama LIKE '%$keyword%' OR
                email LIKE '%$keyword%' OR
                status LIKE '%$keyword%' OR
                alamat LIKE '%$keyword%'
            ";

    $result = mysqli_query($conn, $query);
    return $result;
}
