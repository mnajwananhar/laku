<?php
// Include file koneksi.php untuk menghubungkan ke database
session_start();
require 'db/connection.php';
$alert = '';

// Mengecek apakah form login telah di-submit
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Mengambil nilai dari form
    $nama_pengguna = $_POST['nama_pengguna'];
    $kata_sandi = $_POST['kata_sandi'];

    // Query untuk memeriksa kombinasi nama_pengguna dan kata_sandi
    $query = "SELECT * FROM pengguna WHERE nama_pengguna = ?";

    // Prepare statement
    $stmt = mysqli_prepare($conn, $query);
    
    // Bind parameter dan eksekusi query
    mysqli_stmt_bind_param($stmt, "s", $nama_pengguna);
    mysqli_stmt_execute($stmt);
    
    // Ambil hasil query
    $result = mysqli_stmt_get_result($stmt);
    
    // Ambil data dari hasil query
    if ($row = mysqli_fetch_assoc($result)) {
        // Verifikasi kata_sandi
        if (password_verify($kata_sandi, $row['kata_sandi'])) {
            // Password cocok, login berhasil
			$alert = '<div class="alert alert-success" role="alert">Login Berhasil!</div>';
            // Lakukan tindakan setelah login berhasil
            $_SESSION["loggedin"] = true;
            $_SESSION["id_pengguna"] = $row['id_pengguna']; // Mengambil id_pengguna dari hasil query
            $_SESSION["nama_pengguna"] = $nama_pengguna;
            $_SESSION["peran"] = $row['peran'];

      
        // Arahkan berdasarkan peran pengguna
         if ($row['peran'] == 'kasir') {
            header("Location: cucian.php");
        } elseif ($row['peran'] == 'manajer') {
            header("Location: laporan.php");
        }
        exit();
        } else {
            // Password tidak cocok
			$alert = '<div class="alert alert-danger" role="alert"> Nama Pengguna atau Kata Sandi Salah!</div>';

        }
    } else {
        // Username tidak ditemukan
		$alert = '<div class="alert alert-danger" role="alert"> Nama Pengguna atau Kata Sandi Salah!</div>';

		
    }

	// Tutup statement
    mysqli_stmt_close($stmt);
}
// $conn->close();
?>
<!DOCTYPE html>
<html
  lang="en"
  class="light-style layout-wide customizer-hide"
  dir="ltr"
  data-theme="theme-default"
  data-assets-path="../assets/"
  data-template="vertical-menu-template-free">
  <head>
    <meta charset="utf-8" />
    <meta
      name="viewport"
      content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />

    <title>Login Laundryku</title>

    <meta name="description" content="" />

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="../assets/img/logo.png" />

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
      href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&display=swap"
      rel="stylesheet" />

    <link rel="stylesheet" href="../assets/vendor/fonts/boxicons.css" />

    <!-- Core CSS -->
    <link rel="stylesheet" href="../assets/vendor/css/core.css" class="template-customizer-core-css" />
    <link rel="stylesheet" href="../assets/vendor/css/theme-default.css" class="template-customizer-theme-css" />
    <link rel="stylesheet" href="../assets/css/demo.css" />

    <!-- Vendors CSS -->
    <link rel="stylesheet" href="../assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css" />

    <!-- Page CSS -->
    <!-- Page -->
    <link rel="stylesheet" href="../assets/vendor/css/pages/page-auth.css" />

    <!-- Helpers -->
    <script src="../assets/vendor/js/helpers.js"></script>
    <!--! Template customizer & Theme config files MUST be included after core stylesheets and helpers.js in the <head> section -->
    <!--? Config:  Mandatory theme config file contain global vars & default theme options, Set your preferred theme option in this file.  -->
    <script src="../assets/js/config.js"></script>


  <script>
    // function validateForm() {
    //     var nama_pengguna = document.getElementById("nama_pengguna").value.trim();
    //     var kata_sandi = document.getElementById("kata_sandi").value.trim();

    //     if (nama_pengguna === "" || kata_sandi === "") {
    //         alert("Username dan Password harus diisi.");
    //         // $alert = '<div class="alert alert-danger" role="alert">Username dan Password harus diisi.</div>'
    //         return false;
    //     }

    //     // Form valid, lanjutkan pengiriman formulir
    //     return true;
    //   }
  </script>


  </head>

  <body>
    <!-- Content -->

    <div class="container-xxl">
      <div class="authentication-wrapper authentication-basic container-p-y">
        <div class="authentication-inner">
          <!-- Register -->
          <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-center">
                <!-- <img src="https://placehold.co/200x200" alt="" srcset="" class="app-brand"> -->
                <img src="../assets/img/index.png" alt="" class="me-2 app-brand" width="200" height="200">
              </div>

              <!-- /Logo -->

              <form id="formAuthentication" class="mb-3" action="" method='post'>
                <div class="mb-3">

                <?php echo $alert; ?>
                  

                  <label for="nama_pengguna" class="form-label">Nama Pengguna</label>
                  <input
                    type="text"
                    class="form-control"
                    id="nama_pengguna"
                    name="nama_pengguna"
                    placeholder="Masukkan nama pengguna"
                    autofocus required />
                </div>
                <div class="mb-3 form-kata_sandi-toggle">
                  <div class="d-flex justify-content-between">
                    <label class="form-label" for="kata_sandi">Kata Sandi</label>
                  </div>
                  <div class="input-group input-group-merge">
                    <input
                      type="password"
                      id="kata_sandi"
                      class="form-control"
                      name="kata_sandi"
                      placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;"
                      aria-describedby="kata_sandi" required/>
                   
                  </div>
                </div>
                <div class="mb-3">
                  <!-- <button class="btn btn-primary d-grid w-100" type="submit" style="background-color: rgb(33, 158, 216); border-color: rgb(33, 158, 216);" onsubmit="return validateForm()">Login</button> -->
                  <button class="btn btn-primary d-grid w-100" type="submit" style="background-color: rgb(33, 158, 216); border-color: rgb(33, 158, 216);">Login</button>
                </div>
              </form>

            </div>
          </div>
          <!-- /Register -->
        </div>
      </div>
    </div>

    <!-- Core JS -->
    <!-- build:js assets/vendor/js/core.js -->

    <script src="../assets/vendor/libs/jquery/jquery.js"></script>
    <script src="../assets/vendor/libs/popper/popper.js"></script>
    <script src="../assets/vendor/js/bootstrap.js"></script>
    <script src="../assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js"></script>
    <script src="../assets/vendor/js/menu.js"></script>

    <!-- endbuild -->

    <!-- Vendors JS -->

    <!-- Main JS -->
    <script src="../assets/js/main.js"></script>

    <!-- Page JS -->

    <!-- Place this tag in your head or just before your close body tag. -->
    <script async defer src="https://buttons.github.io/buttons.js"></script>
  </body>
</html>