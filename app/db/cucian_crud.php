<?php

include 'connection.php'; // Pastikan Anda telah menyesuaikan dengan nama file koneksi DB Anda

function getCucian() {
    global $conn;
    $sql = "SELECT * FROM cucian WHERE status = 0"; // Hanya menampilkan cucian yang dalam proses
    return $conn->query($sql);
}

function getLayanan() {
    global $conn;
    $sql = "SELECT * FROM layanan";
    return $conn->query($sql);
}

function getLayananById($id) {
    global $conn;
    $sql = "SELECT * FROM layanan WHERE id_layanan = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    return $stmt->get_result()->fetch_assoc();
}

function handleError($error_message) {
    // Log the error message to a file or database
    error_log($error_message, 3, 'errors.log');
    // Redirect to error page or display an error message
    header('Location: ../cucian.php?message=' . urlencode($error_message));
    exit();
}

if (isset($_POST['create'])) {
    $nama_konsumen = $_POST['nama_konsumen'];
    $nohp_konsumen = $_POST['nohp_konsumen'];
    $berat_cucian = $_POST['berat_cucian'];
    $id_layanan = $_POST['id_layanan'];
    $tanggal_cuci = $_POST['tanggal_cuci'];
    $status = 0;

    if (!is_numeric($nohp_konsumen) || strlen($nohp_konsumen) > 13) {
        handleError('Nomor HP konsumen tidak valid!');
    }

    if (!is_numeric($berat_cucian) || $berat_cucian < 1) {
        handleError('Berat cucian tidak valid!');
    }

    // Validasi id_layanan
    $sql = "SELECT COUNT(*) FROM layanan WHERE id_layanan = ?";
    $stmtly = $conn->prepare($sql);
    $stmtly->bind_param("i", $id_layanan);
    $stmtly->execute();
    $stmtly->bind_result($count);
    $stmtly->fetch();
    $stmtly->close();
    if ($count == 0) {
        handleError('ID layanan tidak valid.');
    }

    // Mulai transaksi
    $conn->begin_transaction();

    try {
        $sql = "INSERT INTO cucian (nama_konsumen, nohp_konsumen, berat_cucian, id_layanan, tanggal_cuci, status) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssdiss", $nama_konsumen, $nohp_konsumen, $berat_cucian, $id_layanan, $tanggal_cuci, $status);
        if ($stmt->execute()) {
            $conn->commit();
            header('Location: ../cucian.php?message=Cucian+berhasil+ditambahkan!');
        } else {
            throw new Exception($stmt->error);
        }
    } catch (Exception $e) {
        $conn->rollback();
        handleError('Terjadi kesalahan: ' . $e->getMessage());
    }
}

elseif (isset($_POST['update'])) {
    $id = $_POST['id_cucian'];
    $nama_konsumen = $_POST['nama_konsumen'];
    $nohp_konsumen = $_POST['nohp_konsumen'];
    $berat_cucian = $_POST['berat_cucian'];
    $id_layanan = $_POST['id_layanan'];

    if (!is_numeric($nohp_konsumen) || strlen($nohp_konsumen) > 13) {
        handleError('Nomor HP konsumen tidak valid!');
    }

    if (!is_numeric($berat_cucian) || $berat_cucian < 1) {
        handleError('Berat cucian tidak valid!');
    }

    // Validasi id_layanan
    $sql = "SELECT COUNT(*) FROM layanan WHERE id_layanan = ?";
    $stmtly = $conn->prepare($sql);
    $stmtly->bind_param("i", $id_layanan);
    $stmtly->execute();
    $stmtly->bind_result($count);
    $stmtly->fetch();
    $stmtly->close();
    if ($count == 0) {
        handleError('ID layanan tidak valid.');
    }

    // Mulai transaksi
    $conn->begin_transaction();

    try {
        $sql = "UPDATE cucian SET nama_konsumen=?, nohp_konsumen=?, berat_cucian=?, id_layanan=? WHERE id_cucian=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssdis", $nama_konsumen, $nohp_konsumen, $berat_cucian, $id_layanan, $id);
        if ($stmt->execute()) {
            $conn->commit();
            header('Location: ../cucian.php?message=Cucian+berhasil+diupdate');
        } else {
            throw new Exception($stmt->error);
        }
    } catch (Exception $e) {
        $conn->rollback();
        handleError('Terjadi kesalahan: ' . $e->getMessage());
    }
}

elseif (isset($_POST['update_status'])) {
    $id = $_POST['id_cucian'];
    $id_pengguna = $_POST['id_pengguna'];

    // Pastikan ID pengguna tersedia dalam sesi
    if (!isset($id_pengguna)) {
        handleError("ID pengguna tidak ditemukan dalam sesi.");
    }

    // Mulai transaksi
    $conn->begin_transaction();

    try {
        // Update status cucian
        $sql = "UPDATE cucian SET status=1 WHERE id_cucian=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();

        // Ambil data cucian untuk digunakan di tabel pembayaran
        $sql = "SELECT * FROM cucian WHERE id_cucian=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $cucian = $stmt->get_result()->fetch_assoc();

        if ($cucian) {
            $tanggal_pembayaran = date('Y-m-d H:i:s');
            $total_harga = $cucian['berat_cucian'] * getLayananById($cucian['id_layanan'])['harga_layanan'];

            // Tambahkan ke tabel pembayaran
            $sql = "INSERT INTO pembayaran (tanggal_pembayaran, total_harga, id_pengguna, id_cucian) VALUES (?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("sdii", $tanggal_pembayaran, $total_harga, $id_pengguna, $id);
            $stmt->execute();
        }

        // Commit transaksi
        $conn->commit();

        header('Location: ../cucian.php?message=Status+cucian+dan+pembayaran+berhasil+diupdate');
    } catch (Exception $e) {
        // Rollback jika ada error
        $conn->rollback();
        handleError('Terjadi kesalahan: ' . $e->getMessage());
    }
}

elseif (isset($_POST['delete'])) {
    $id = $_POST['id_cucian'];

    // Mulai transaksi
    $conn->begin_transaction();

    try {
        $sql = "DELETE FROM cucian WHERE id_cucian=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $id);
        if ($stmt->execute()) {
            $conn->commit();
            header('Location: ../cucian.php?message=Cucian+berhasil+dihapus');
        } else {
            throw new Exception($stmt->error);
        }
    } catch (Exception $e) {
        $conn->rollback();
        handleError('Terjadi kesalahan: ' . $e->getMessage());
    }
}

?>
