<?php
include 'db/connection.php';
// Pastikan ini hanya dimasukkan sekali
$alert = '';

// Handle form submission for create and update
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['create'])) {
        // Handle create
        $nama_layanan = $_POST['nama_layanan'];
        $harga_layanan = $_POST['harga_layanan'];
        
        // Validate input
        if (empty($nama_layanan)) {
            $alert = '<div class="alert alert-danger" role="alert">Nama layanan tidak boleh kosong!</div>';
        } elseif (!is_numeric($harga_layanan) || $harga_layanan < 0) {
            $alert = '<div class="alert alert-danger" role="alert">Harga layanan harus berupa angka dan tidak negatif!</div>';
        } elseif (empty($nama_layanan) && empty($harga_layanan)) {
            $alert = '<div class="alert alert-danger" role="alert">Nama layanan dan Harga layanan tidak boleh kosong!</div>';
        } else {
            // Check if nama_layanan already exists
            $sql_check = "SELECT COUNT(*) FROM layanan WHERE nama_layanan=?";
            $stmt_check = $conn->prepare($sql_check);
            $stmt_check->bind_param("s", $nama_layanan);
            $stmt_check->execute();
            $stmt_check->bind_result($count);
            $stmt_check->fetch();
            $stmt_check->close();

            if ($count > 0) {
                $alert = '<div class="alert alert-warning" role="alert">Nama layanan sudah ada!</div>';
            } else {
                $sql = "INSERT INTO layanan (nama_layanan, harga_layanan) VALUES (?, ?)";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("si", $nama_layanan, $harga_layanan);
                if ($stmt->execute()) {
                    $alert = '<div class="alert alert-success" role="alert">Data berhasil ditambahkan!</div>';
                } else {
                    echo "Error: " . $stmt->error;
                }
            }
        }

    } elseif (isset($_POST['confirm_update'])) {
        // Handle update
        $id = $_POST['id'];
        $nama_layanan = $_POST['nama_layanan'];
        $harga_layanan = $_POST['harga_layanan'];

        // Validate input
        if (empty($nama_layanan)) {
            $alert = '<div class="alert alert-danger" role="alert">Nama layanan tidak boleh kosong!</div>';
        } elseif (empty($harga_layanan)) {
            $alert = '<div class="alert alert-danger" role="alert">Harga layanan tidak boleh kosong!</div>';
        } elseif (!is_numeric($harga_layanan) || $harga_layanan < 0) {
            $alert = '<div class="alert alert-danger" role="alert">Harga layanan harus berupa angka dan tidak negatif!</div>';
        } elseif (empty($nama_layanan) && empty($harga_layanan)) {
            $alert = '<div class="alert alert-danger" role="alert">Nama layanan dan Harga layanan tidak boleh kosong!</div>';
        } else {
            $sql = "UPDATE layanan SET nama_layanan=?, harga_layanan=? WHERE id_layanan=?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ssi", $nama_layanan, $harga_layanan, $id);
            if ($stmt->execute()) {
                $alert = '<div class="alert alert-info" role="alert">Data berhasil diperbaharui!</div>';
            } else {
                echo "Error: " . $stmt->error;
            }
        }
    }
}

// Handle delete
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $sql = "DELETE FROM layanan WHERE id_layanan=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    if ($stmt->execute()) {
        $alert = '<div class="alert alert-danger" role="alert">Data berhasil dihapus!</div>';
        echo '<script>
                setTimeout(function() {
                    window.location.href = "layanan.php";
                }, 3000);
              </script>';
    } else {
        echo "Error: " . $stmt->error;
    }
}

// Function to get layanan
function getLayanan()
{
    global $conn;
    $sql = "SELECT * FROM layanan";
    $result = $conn->query($sql);
    return $result;
}
?>
