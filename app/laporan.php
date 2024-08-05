<?php include 'sidebar.php'; ?>
<?php include 'db/auth.php' ?>
<?php 
checkAccess(['manajer','kasir']); 
?>

<!--  -->

<?php 
include 'db/connection.php';

function getLaporan()
{
    global $conn;
    $sql = "SELECT * FROM pembayaran";
    $result = $conn->query($sql);
    return $result;
}

function getCucianById($id) {
    global $conn;
    $sql = "SELECT * FROM cucian WHERE id_cucian = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    return $stmt->get_result()->fetch_assoc();
}

function getLayananById($id) {
    global $conn;
    $sql = "SELECT * FROM layanan WHERE id_layanan = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    return $stmt->get_result()->fetch_assoc();
}

?>

<!-- Content wrapper -->
<div class="content-wrapper">
    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="py-3 mb-4">Laporan</h4>

        <!-- Basic Layout -->
        <div class="row">
            <div class="col-xl">
                <div class="card">
                    <div class="card-header d-flex justify-content-between">
                        <h5 class="mb-0"></h5>
                        <div class="input-group" style="max-width: 200px;">
                            <span class="input-group-text"><i class="bx bx-search"></i></span>
                            <input type="text" class="form-control" placeholder="Cari..." aria-label="Cari..." id="myInput">
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive text-nowrap">
                            
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Tanggal Pembayaran</th>
                                        <th>Tanggal Cuci</th>
                                        <th>Nama Konsumen</th>
                                        <th>No HP</th>
                                        <th>Layanan</th>
                                        <th>Berat Cucian</th>
                                        <th>Total Bayar</th>
                                    </tr>
                                </thead>
                                <tbody id="myTable">
                                <?php
                                $result = getLaporan();
                                $no = 1;
                                while ($row = $result->fetch_assoc()) {
                                    $cucianData = getCucianById($row['id_cucian']);
                                    $layananData = getLayananById($cucianData['id_layanan']); // Corrected to use cucianData's layanan ID

                                    echo "<tr>";
                                    echo "<td>{$row['id_pembayaran']}</td>";
                                    echo "<td>{$row['tanggal_pembayaran']}</td>";
                                    echo "<td>{$cucianData['tanggal_cuci']}</td>";
                                    echo "<td>{$cucianData['nama_konsumen']}</td>";
                                    echo "<td>{$cucianData['nohp_konsumen']}</td>";
                                    echo "<td>{$layananData['nama_layanan']}</td>";
                                    echo "<td>{$cucianData['berat_cucian']} kg</td>"; // Corrected the field name
                                    echo "<td>Rp. " . number_format($row['total_harga'], 0, ',', '.') . "</td>";
                                    echo "</tr>";
                                    $no++;
                                }
                                ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="content-backdrop fade"></div>
</div>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<script>
$(document).ready(function(){
  $("#myInput").on("keyup", function() {
    var value = $(this).val().toLowerCase();
    $("#myTable tr").filter(function() {
      $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
    });
  });
});
</script>
<!-- / Content wrapper -->
