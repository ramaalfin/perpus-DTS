<?php
session_start();


if (isset($_SESSION['login']) && $_SESSION['login'] == true) {
  header("Location: beranda/index.php");
  exit();
}

require 'koneksi.php';

if (isset($_POST['login'])) {
  global $conn;

  $username = $_POST['username'];
  $password = $_POST['password_admin'];

  $result = mysqli_query($conn, "SELECT * FROM admin WHERE username = '$username' AND password_admin = '$password'");

  // cek jika nama admin ada
  if (mysqli_num_rows($result) === 1) {
    // cek jika password sesuai

    $row = mysqli_fetch_assoc($result);
    $_SESSION['sesi'] = $row['nama_admin'];
    $_SESSION['login'] = true;

    header('Location: beranda/index.php');
    exit;
  }

  $error = true;
}


?>

<!-- HEADER -->
<?php
$title = 'Masuk - Admin';
$style = [
  'css/style.css',
  'assets/bootstrap/css/bootstrap.min.css'
];
include("layouts/header.php")
?>

<section class="vh-100" style="background-color: #053083;">
  <div class="container py-5 h-100">
    <div class="row d-flex justify-content-center align-items-center h-100">
      <div class="col-12 col-md-8 col-lg-6 col-xl-5">
        <div class="d-flex gap-2 mb-4 justify-content-center">
          <img src="assets/images/logo.png" alt="Logo" width="52" height="50" />
          <div>
            <h6 class="judul-logo" style="color: #fff;">Blu Books</h6>
            <p class="tag-logo" style="color: #fff;">Kumpulan Buku Keren</p>
          </div>
        </div>
        <div class="card shadow-2-strong" style="border-radius: 1rem;">
          <div class="card-body p-5">
            <?php if (isset($error)) : ?>
              <p style="color: red;" class="mb-3">Nama Pengguna atau Password salah</p>
            <?php endif ?>
            <h4 class="mb-4 text-center">Masuk</h4>
            <form action="" method="POST">
              <div class="form-outline mb-4">
                <label class="form-label text-left">Nama Pengguna</label>
                <input type="text" class="form-control form-control-lg" name="username" required />
              </div>

              <div class="form-outline mb-4">
                <label class="form-label">Password</label>
                <input type="password" class="form-control form-control-lg" name="password_admin" required />
              </div>

              <div class="text-center">
                <button class="btn btn-primary btn-block btnLogin" type="login" name="login">Login</button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- FOOTER -->
<?php
$src = [
  'js/jquery.js',
  'assets/bootstrap/js/bootstrap.min.js',
  'assets/bootstrap/js/bootstrap.bundle.min.js',
  'js/index.js'
];
include('layouts/footer.php');

?>