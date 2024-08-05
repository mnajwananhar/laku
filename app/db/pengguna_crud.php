<?php
include 'db/connection.php'; // Pastikan koneksi hanya disertakan sekali

// Create
function createPengguna($nama_pengguna, $kata_sandi_hashed, $peran) {
    global $conn;

    // Validate peran
    if (!in_array($peran, ['manajer', 'kasir'])) {
        return ['success' => false, 'error' => 'Invalid role'];
    }

    // Check if nama_pengguna contains spaces
    if (preg_match('/\s/', $nama_pengguna)) {
        return ['success' => false, 'error' => 'Nama pengguna tidak boleh mengandung spasi'];
    }

    // Check if nama_pengguna already exists
    $sql_check = "SELECT COUNT(*) FROM pengguna WHERE nama_pengguna=?";
    $stmt_check = $conn->prepare($sql_check);
    $stmt_check->bind_param("s", $nama_pengguna);
    $stmt_check->execute();
    $stmt_check->bind_result($count);
    $stmt_check->fetch();
    $stmt_check->close();

    if ($count > 0) {
        return ['success' => false, 'error' => 'Nama pengguna sudah ada'];
    }

    $sql = "INSERT INTO pengguna (nama_pengguna, kata_sandi, peran) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sss", $nama_pengguna, $kata_sandi_hashed, $peran);
    if ($stmt->execute()) {
        return ['success' => true];
    } else {
        return ['success' => false, 'error' => $stmt->error];
    }
}

// Update
function updatePengguna($id_pengguna, $nama_pengguna, $kata_sandi_hashed, $peran) {
    global $conn;

    // Validate peran
    if (!in_array($peran, ['manajer', 'kasir'])) {
        return ['success' => false, 'error' => 'Invalid role'];
    }

    // Validate if nama_pengguna or peran is empty
    if (empty($nama_pengguna) || empty($peran)) {
        return ['success' => false, 'error' => 'Nama pengguna dan peran tidak boleh kosong'];
    }

    // Ambil kata sandi lama dari database
    $sql = "SELECT kata_sandi FROM pengguna WHERE id_pengguna=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id_pengguna);
    $stmt->execute();
    $stmt->bind_result($old_password);
    $stmt->fetch();
    $stmt->close();

    // Jika kata sandi baru kosong, gunakan kata sandi lama
    if (empty($kata_sandi_hashed)) {
        $kata_sandi_hashed = $old_password;
    }

    // Check if nama_pengguna contains spaces
    if (preg_match('/\s/', $nama_pengguna)) {
        return ['success' => false, 'error' => 'Nama pengguna tidak boleh mengandung spasi'];
    }

    $sql = "UPDATE pengguna SET nama_pengguna=?, kata_sandi=?, peran=? WHERE id_pengguna=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssi", $nama_pengguna, $kata_sandi_hashed, $peran, $id_pengguna);
    if ($stmt->execute()) {
        return ['success' => true];
    } else {
        return ['success' => false, 'error' => $stmt->error];
    }
}

// Delete
function deletePengguna($id_pengguna) {
    global $conn;
    $sql = "DELETE FROM pengguna WHERE id_pengguna=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id_pengguna);
    if ($stmt->execute()) {
        return ['success' => true];
    } else {
        return ['success' => false, 'error' => $stmt->error];
    }
}

// Read
function getPengguna() {
    global $conn;
    $sql = "SELECT * FROM pengguna";
    $result = $conn->query($sql);
    return $result;
}
?>
