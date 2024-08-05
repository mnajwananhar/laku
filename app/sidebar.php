<?php
// session_start();
session_start();


// Cek apakah pengguna sudah login, jika tidak arahkan ke halaman login
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: index.php");
    exit;
} else {

}




?>


<!DOCTYPE html>

<html
  lang="en"
  class="light-style layout-menu-fixed layout-compact"
  dir="ltr"
  data-theme="theme-default"
  data-assets-path="..//"
  data-template="vertical-menu-template-free">
  <head>
    <meta charset="utf-8" />
    <meta
      name="viewport"
      content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />

    <title>Laundryku</title>

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

    <!-- Helpers -->
    <script src="../assets/vendor/js/helpers.js"></script>
    <!--! Template customizer & Theme config files MUST be included after core stylesheets and helpers.js in the <head> section -->
    <!--? Config:  Mandatory theme config file contain global vars & default theme options, Set your preferred theme option in this file.  -->
    <script src="../assets/js/config.js"></script>
  </head>

  <body>
    <!-- Layout wrapper -->
    <div class="layout-wrapper layout-content-navbar">
      <div class="layout-container">
        <!-- Menu -->

        <aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
          <div class="app-brand demo">
            <a href="#" class="app-brand-link ">
              <div style="display: flex; justify-content: center; align-items: center; height: 100%;">
                <img src="../assets/img/logo.png" alt="" class="me-2">
                <h1 style="margin: 0;">LAKU</h1>
              </div>
            </a>

            <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto d-block d-xl-none">
              <i class="bx bx-chevron-left bx-sm align-middle"></i>
            </a>
          </div>

          <div class="menu-inner-shadow"></div>

          <ul class="menu-inner py-1">

            <!-- Navbar -->
            <!-- <li class="menu-item">
              <a
                href="layanan.php"
                class="menu-link">
                <i class="menu-icon tf-icons bx bx-store"></i>
                <div data-i18n="Front Pages">Layanan</div>
              </a>
            </li>
            <li class="menu-item">
              <a
                href="pengguna.php"
                class="menu-link">
                <i class="menu-icon tf-icons bx bx-user"></i>
                <div data-i18n="Authentications">Pengguna</div>
              </a>
            </li>
            <li class="menu-item">
              <a
                href="cucian.php"
                class="menu-link">
                <i class='menu-icon tf-icons bx bxs-washer'></i>
                <div data-i18n="Front Pages">Cucian</div>
              </a>
            </li>
            <li class="menu-item">
              <a
                href="laporan.php"
                class="menu-link">
                <i class="menu-icon tf-icons bx bx-box"></i>
                <div data-i18n="User interface">Laporan</div>
              </a>
            </li> 
          <li class="menu-item">
            </li>
            <a
              href="logout.php"
              class="menu-link" style="position: absolute; bottom: 0;">
              <i class="menu-icon tf-icons bx bx-log-out"></i>
              <div data-i18n="Logout">Logout</div>
            </a>
          </ul> -->

          <?php
            // Menu navigasi berdasarkan peran
            if ($_SESSION['peran'] === 'manajer' || $_SESSION['peran'] === 'all') {
                echo '
                <li class="menu-item">
                <a
                  href="laporan.php"
                  class="menu-link">
                  <i class="menu-icon tf-icons bx bx-box"></i>
                  <div data-i18n="User interface">Laporan</div>
                </a>
              </li>
              <li class="menu-item">
                  <a
                    href="layanan.php"
                    class="menu-link">
                    <i class="menu-icon tf-icons bx bx-store"></i>
                    <div data-i18n="Front Pages">Layanan</div>
                  </a>
                </li>
              <li class="menu-item">
                <a
                  href="pengguna.php"
                  class="menu-link">
                  <i class="menu-icon tf-icons bx bx-user"></i>
                  <div data-i18n="Authentications">Pengguna</div>
                </a>
              </li>
                ';
            }
            if ($_SESSION['peran'] === 'kasir' || $_SESSION['peran'] === 'all') {
                echo '
                <li class="menu-item">
                <a
                  href="cucian.php"
                  class="menu-link">
                  <i class="menu-icon tf-icons bx bxs-washer"></i>
                  <div data-i18n="Front Pages">Cucian</div>
                </a>
              </li>
              <li class="menu-item">
              <a
                href="laporan.php"
                class="menu-link">
                <i class="menu-icon tf-icons bx bx-box"></i>
                <div data-i18n="User interface">Laporan</div>
              </a>
            </li>
                ';
            }
          ?>
          </ul>
          <div class="menu-item" style="position: absolute; bottom: 0; width: 100%;">
            <a
              href="logout.php"
              class="menu-link">
              <i class="menu-icon tf-icons bx bx-log-out"></i>
              <div data-i18n="Logout">Logout</div>
            </a>
          </div>
        </aside>
        <!-- / Menu -->

        <!-- Layout container -->
        <div class="layout-page">
          <!-- Navbar -->

          <nav
            class="layout-navbar container-xxl navbar navbar-expand-xl navbar-detached align-items-center bg-navbar-theme"
            id="layout-navbar">
            <div class="layout-menu-toggle navbar-nav align-items-xl-center me-3 me-xl-0 d-xl-none">
              <a class="nav-item nav-link px-0 me-xl-4" href="javascript:void(0)">
                <i class="bx bx-menu bx-sm"></i>
              </a>
            </div>

            <div class="navbar-nav-right d-flex align-items-center" id="navbar-collapse">
              <ul class="navbar-nav flex-row align-items-center ms-auto">
                <!-- Place this tag where you want the button to render. -->

                <?php echo $_SESSION["nama_pengguna"] . " ";   ?>
                <?php $id_pengguna = $_SESSION["id_pengguna"] ?>
                <?php
                  $badgeClass = ($_SESSION["peran"] == "manajer") ? "bg-label-primary" : "bg-label-info";
                  $badgeText = $_SESSION["peran"]; // atau Anda bisa mengatur teks badge sesuai kebutuhan
                  echo '  <span class="ms-2 badge ' . $badgeClass . ' me-1">' . $badgeText . '</span>';
                  ?>

              </ul>
            </div>
          </nav>

          <!-- / Navbar -->


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
