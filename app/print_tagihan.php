<?php
include 'db/connection.php';

if (!isset($_GET['id'])) {
    echo "ID tidak ditemukan.";
    exit;
}

$id = $_GET['id'];

$sql = "SELECT c.*, l.nama_layanan, l.harga_layanan 
        FROM cucian c 
        JOIN layanan l ON c.id_layanan = l.id_layanan 
        WHERE c.id_cucian = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

// if ($result->num_rows === 0) {
//     $alert = "Cucian tidak ditemukan.";
//     header("Location: cucian.php?alert=" . urlencode($alert));
//     exit;
// }

if ($result->num_rows === 0) {
    $alert = "Cucian tidak ditemukan.";
    echo "<script>
            window.opener.location.href = 'cucian.php?alert=" . urlencode($alert) . "';
            window.close();
          </script>";
    exit;
}



$cucian = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Print Tagihan</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }
        .invoice-box {
            max-width: 800px;
            margin: auto;
            padding: 30px;
            border: 1px solid #eee;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.15);
            font-size: 16px;
            line-height: 24px;
            color: #555;
        }
        .invoice-box table {
            width: 100%;
            line-height: inherit;
            text-align: left;
        }
        .invoice-box table td {
            padding: 5px;
            vertical-align: top;
        }
        .invoice-box table tr td:nth-child(2) {
            text-align: right;
        }
        .invoice-box table tr.top table td {
            padding-bottom: 20px;
        }
        .invoice-box table tr.top table td.title {
            font-size: 45px;
            line-height: 45px;
            color: #333;
        }
        .invoice-box table tr.information table td {
            padding-bottom: 40px;
        }
        .invoice-box table tr.heading td {
            background: #eee;
            border-bottom: 1px solid #ddd;
            font-weight: bold;
        }
        .invoice-box table tr.details td {
            padding-bottom: 20px;
        }
        .invoice-box table tr.item td{
            border-bottom: 1px solid #eee;
        }
        .invoice-box table tr.item.last td {
            border-bottom: none;
        }
        .invoice-box table tr.total td:nth-child(2) {
            border-top: 2px solid #eee;
            font-weight: bold;
        }
        .logo {
            display: flex;
            justify-content: center;
            align-items: center;
        }
    </style>
</head>
<body>
    <div class="invoice-box">
           <div class="invoice-box">
        <div class="logo">
            <img src="../assets/img/logo.png" alt="">
        
            <h1 >LAKU </h1>
        </div>
        <p style="text-align: center; color: red;">Struk tagihan ini jangan sampai hilang karena nanti akan diminta kembali</p>

        <table cellpadding="0" cellspacing="0">
            <tr class="item">
                <td>Kode Cucian</td>
                <td><?php echo $cucian['id_cucian']; ?></td>
            </tr>
            <tr class="item">
                <td>Tanggal Cuci</td>
                <td><?php echo $cucian['tanggal_cuci']; ?></td>
            </tr>
            <tr class="item">
                <td>Nama Konsumen</td>
                <td><?php echo $cucian['nama_konsumen']; ?></td>
            </tr>
            <tr class="item">
                <td>No HP</td>
                <td><?php echo $cucian['nohp_konsumen']; ?></td>
            </tr>
            <tr class="item">
                <td>Layanan</td>
                <td><?php echo $cucian['nama_layanan']; ?></td>
            </tr>
            <tr class="item">
                <td>Berat Cucian</td>
                <td><?php echo $cucian['berat_cucian']; ?> kg</td>
            </tr>
            <tr class="item">
                <td>Harga Layanan</td>
                <td>Rp. <?php echo number_format($cucian['harga_layanan'], 0, ',', '.'); ?></td>
            </tr>
            <tr class="total">
                <td>Total Bayar</td>
                <td>Rp. <?php echo number_format($cucian['berat_cucian'] * $cucian['harga_layanan'], 0, ',', '.'); ?></td>
            </tr>
            <tr class="status">
                <td colspan="2" style="text-align: center; font-size: 30px;"><b>PROSES</b></td>
            </tr>
        </table>
    </div>
    <script>
        window.print();
    </script>
</body>
</html>
