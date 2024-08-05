<?php
include 'db/cucian_crud.php';

function getCucianById($id) {
    global $conn;
    $sql = "SELECT * FROM cucian WHERE id_cucian = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    return $stmt->get_result()->fetch_assoc();
}
$alert = '';
$alertModal = '';
if (isset($_GET['message'])) {
    $alert = '<div class="alert alert-info" role="alert">' . $_GET['message'] . '</div>';
}
?>

<?php
include_once 'sidebar.php';

echo '<script>';
echo 'console.log("test");';
echo '</script>';

// echo $id_pengguna;
// echo '<pre>';
// print_r($_SESSION);
// echo '</pre>';
?>
<?php include 'db/auth.php' ?>
<?php 
checkAccess(['kasir']); 
?>

<!-- Content wrapper -->
<div class="content-wrapper">
    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="py-3">Cucian</h4>

        <?= $alert; ?>
        <?php
            if (isset($_GET['alert'])) {
                echo "<div class='alert alert-warning'>" . htmlspecialchars($_GET['alert']) . "</div>";
            }
        ?>

        <!-- Button trigger modal -->
        <button type="button" class="btn btn-info mb-4" data-bs-toggle="modal" data-bs-target="#modalCenter">
            Tambah Cucian
        </button>

        <!-- Basic Layout -->
        <div class="row">
            <div class="col-xl">
                <div class="card">
                    <div class="card-header d-flex justify-content-between">

                        <h5 class=""></h5>
                         <div class="input-group" style="max-width: 200px;">
                                <span class="input-group-text"><i class="bx bx-search"></i></span>
                                <input type="text" class="form-control" placeholder="Cari..." aria-label="Search..." id="myInput">
                            </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive text-nowrap">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Kode</th>
                                        <th>Tanggal Cuci</th>
                                        <th>Nama Konsumen</th>
                                        <th>No HP</th>
                                        <th>Layanan</th>
                                        <th>Berat Cucian</th>
                                        <th>Total Bayar</th>
                                        <th>Status</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody id="myTable">
                                    <?php
                                    $result = getCucian();
                                    $no = 1;
                                    while ($row = $result->fetch_assoc()) {
                                        $cucianData = getCucianById($row['id_cucian']);
                                        echo "<tr>";
                                        echo "<td>{$cucianData['id_cucian']}</td>";
                                        echo "<td>{$row['tanggal_cuci']}</td>";
                                        echo "<td>{$row['nama_konsumen']}</td>";
                                        echo "<td>{$row['nohp_konsumen']}</td>";
                                        echo "<td>". getLayananById($row['id_layanan'])['nama_layanan'] . "</td>";
                                        echo "<td>{$row['berat_cucian']}</td>";
                                        echo "<td>Rp. " . number_format($row['berat_cucian'] * getLayananById($row['id_layanan'])['harga_layanan'], 0, ',', '.') . "</td>";
                                        echo "<td>";
                                        if ($row['status'] == 1) {
                                            echo "<span class='badge bg-success'>Selesai</span>";
                                        } else {
                                            echo "<span class='badge bg-warning'>Proses</span>";
                                        }
                                        echo "</td>";
                                        echo "<td>
                                                <a class='dropdown-item' href='#' data-bs-toggle='modal' data-bs-target='#modalEdit' data-id='{$row['id_cucian']}' data-nama='{$row['nama_konsumen']}' data-phone='{$row['nohp_konsumen']}' data-weight='{$row['berat_cucian']}' data-service='{$row['id_layanan']}'>
                                                    <i class='bx bx-edit-alt me-1'></i> Edit
                                                </a>
                                                <a class='dropdown-item' href='#' data-bs-toggle='modal' data-bs-target='#modalPrintTagihan' data-id='{$row['id_cucian']}'>
                                                    <i class='bx bx-printer me-1'></i> Print Tagihan
                                                </a>
                                                ".($row['status'] == 0 ? "<a class='dropdown-item' href='#' data-bs-toggle='modal' data-bs-target='#modalConfirmStatus' data-id='{$row['id_cucian']}' data-user-id='{$id_pengguna}'>
                                                    <i class='bx bx-check me-1'></i> Konfirmasi
                                                </a>" : "")."
                                                <a class='dropdown-item delete-laundry' href='#' data-bs-toggle='modal' data-bs-target='#modalConfirmDelete' data-id='{$row['id_cucian']}' data-name='{$row['nama_konsumen']}'>
                                                    <i class='bx bx-trash me-1'></i> Hapus
                                                </a>
                                            </td>";
                                        echo "</tr>";
                                    }
                                    $no++;
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
<!-- / Content wrapper -->

<!-- Modal Tambah Cucian -->
<div class="modal fade" id="modalCenter" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalCenterTitle">Tambah Cucian</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <?= $alertModal ?>
                <form action="db/cucian_crud.php" method="post">
                    <div class="row">
                        <div class="col mb-6">
                            <label for="nameWithTitle" class="form-label">Nama Konsumen</label>
                            <input type="text" id="nameWithTitle" name="nama_konsumen" class="form-control" placeholder="John Doe" required>
                        </div>
                    </div>
                    <div class="row g-6">
                        <div class="col mb-0">
                            <label for="emailWithTitle" class="form-label">No HP</label>
                            <input type="number" id="emailWithTitle" name="nohp_konsumen" class="form-control" placeholder="081234567890" required>
                        </div>
                    </div>
                    <div class="row g-6">
                        <div class="col mb-0">
                            <label for="weightWithTitle" class="form-label">Berat Cucian</label>
                            <input type="number" id="weightWithTitle" name="berat_cucian" class="form-control" placeholder="2 kg" min="1" required>
                        </div>
                        <div class="col mb-0">
                            <label for="serviceWithTitle" class="form-label">Layanan</label>
                            <select class="form-select" id="serviceWithTitle" name="id_layanan" aria-label="Default select example" required>
                                <?php
                                $layanan_result = getLayanan();
                                while ($layanan = $layanan_result->fetch_assoc()) {
                                    echo "<option value='{$layanan['id_layanan']}'>{$layanan['nama_layanan']}</option>";
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                    <input type="hidden" name="tanggal_cuci" value="<?php echo date('Y-m-d H:i:s'); ?>">
                    <div class="modal-footer">
                        <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-info" name="create">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal Edit Cucian -->
<div class="modal fade" id="modalEdit" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalEditTitle">Edit Cucian</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="db/cucian_crud.php" method="post">
                    <input type="hidden" id="editId" name="id_cucian">
                    <div class="row">
                        <div class="col mb-6">
                            <label for="editName" class="form-label">Nama Konsumen</label>
                            <input type="text" id="editName" name="nama_konsumen" class="form-control" required>
                        </div>
                    </div>
                    <div class="row g-6">
                        <div class="col mb-0">
                            <label for="editPhone" class="form-label">No HP</label>
                            <input type="number" id="editPhone" name="nohp_konsumen" class="form-control" maxlength="13" required>
                        </div>
                    </div>
                    <div class="row g-6">
                        <div class="col mb-0">
                            <label for="editWeight" class="form-label">Berat Cucian</label>
                            <input type="number" id="editWeight" name="berat_cucian" class="form-control" min="1" required>
                        </div>
                        <div class="col mb-0">
                            <label for="editService" class="form-label">Layanan</label>
                            <select class="form-select" id="editService" name="id_layanan" aria-label="Default select example" required>
                                <?php
                                $layanan_result = getLayanan();
                                while ($layanan = $layanan_result->fetch_assoc()) {
                                    echo "<option value='{$layanan['id_layanan']}'>{$layanan['nama_layanan']}</option>";
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-info" name="update">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal Konfirmasi Status -->
<div class="modal fade" id="modalConfirmStatus" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalConfirmStatusTitle">Konfirmasi Status</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="db/cucian_crud.php" method="post">
                    <input type="hidden" id="confirmStatusId" name="id_cucian">
                    <input type="hidden" name="id_pengguna" value="<?= $id_pengguna; ?>">
                    <p>Apakah Anda yakin ingin mengubah status cucian ini menjadi selesai?</p>
                    <p class="text-danger">*print terlebih dahulu</p>
                    <button type="button" class="btn btn-info" id="printPembayaranBtn">Print Pembayaran</button>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-info" name="update_status" id="confirmStatusBtn" disabled>Selesai</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal Print Tagihan -->
<div class="modal fade" id="modalPrintTagihan" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalPrintTagihanTitle">Print Tagihan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p id="printTagihanText">Apakah Anda yakin ingin mencetak tagihan untuk cucian ini?</p>
                <p hidden id="printTagihanCode"></p>
                <div class="modal-footer">
                    <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-info" onclick="printTagihan()">Print</button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Konfirmasi Hapus -->
<div class="modal fade" id="modalConfirmDelete" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalConfirmDeleteTitle">Hapus Cucian</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="db/cucian_crud.php" method="post">
                    <input type="hidden" id="deleteId" name="id_cucian">
                    <p id="deleteConfirmationText">Apakah Anda yakin ingin menghapus cucian ini?</p>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-danger" name="delete">Hapus</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Hidden Element for Print Pembayaran Code -->
<p hidden id="printPembayaranCode"></p>

<!-- JavaScript -->
<script>
$(document).ready(function(){
  $("#myInput").on("keyup", function() {
    var value = $(this).val().toLowerCase();
    $("#myTable tr").filter(function() {
      $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
    });
  });
});

document.querySelectorAll('[data-bs-target="#modalEdit"]').forEach(function(button) {
    button.addEventListener('click', function() {
        var id = this.getAttribute('data-id');
        var nama = this.getAttribute('data-nama');
        var phone = this.getAttribute('data-phone');
        var weight = this.getAttribute('data-weight');
        var service = this.getAttribute('data-service');

        document.getElementById('editId').value = id;
        document.getElementById('editName').value = nama;
        document.getElementById('editPhone').value = phone;
        document.getElementById('editWeight').value = weight;
        document.getElementById('editService').value = service;
    });
});

document.querySelectorAll('[data-bs-target="#modalConfirmStatus"]').forEach(function(button) {
    button.addEventListener('click', function() {
        var id = this.getAttribute('data-id');
        var userId = this.getAttribute('data-user-id');
        document.getElementById('confirmStatusId').value = id;
        console.log("User ID:", userId);

        // Set ID for Print Pembayaran
        document.getElementById('printPembayaranCode').textContent = id;
    });
});

document.querySelectorAll('[data-bs-target="#modalPrintTagihan"]').forEach(function(button) {
    button.addEventListener('click', function() {
        var id = this.getAttribute('data-id');
        document.getElementById('printTagihanCode').textContent = id;
        document.getElementById('printTagihanText').textContent = `Print Cucian : ${id}`;

    });
});

function printTagihan() {
    var kode = document.getElementById('printTagihanCode').textContent;
    window.open('print_tagihan.php?id=' + kode, '_blank');
}

function printPembayaran() {
    var kode = document.getElementById('printPembayaranCode').textContent;
    window.open('print_pembayaran.php?id=' + kode, '_blank');
}

document.querySelectorAll('[data-bs-target="#modalConfirmDelete"]').forEach(function(button) {
    button.addEventListener('click', function() {
        var id = this.getAttribute('data-id');
        var name = this.getAttribute('data-name');
        document.getElementById('deleteId').value = id;
        document.getElementById('deleteConfirmationText').textContent = `Apakah Anda yakin ingin menghapus cucian dengan kode ${id}?`;
    });
});

// Print Pembayaran in Konfirmasi Status
document.getElementById('printPembayaranBtn').addEventListener('click', function() {
    printPembayaran();
    document.getElementById('confirmStatusBtn').disabled = false;
});

// JavaScript Validation for Phone Number Input
document.addEventListener('DOMContentLoaded', function() {
    const phoneInputs = document.querySelectorAll('input[name="nohp_konsumen"]');

    phoneInputs.forEach(input => {
        input.addEventListener('input', function() {
            let value = this.value;
            // Remove non-numeric characters
            value = value.replace(/\D/g, '');
            // Trim to 13 characters
            if (value.length > 13) {
                value = value.slice(0, 13);
            }
            this.value = value;
        });
    });
});


</script>
