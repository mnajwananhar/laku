<?php include 'db/layanan_crud.php' ?>
<?php include 'sidebar.php'; ?>
<?php include 'db/auth.php' ?>
<?php 
checkAccess(['manajer']); 
?>

<!-- Content wrapper -->
<div class="content-wrapper">
    <!-- Content -->
    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="py-3 mb-4">Layanan</h4>

        <?= $alert; ?>
        <!-- Basic Layout -->
        <div class="row">
            <div class="col-xl">
                <div class="card">
                    <h5 class="card-header"></h5>
                    <div class="card-body">
                        <div class="table-responsive text-nowrap">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Nama Layanan</th>
                                        <th>Harga Layanan</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $result = getLayanan();
                                    $no = 1;
                                    while ($row = $result->fetch_assoc()) {
                                        echo "<tr>";
                                        echo "<td>{$no}</td>";
                                        echo "<td>{$row['nama_layanan']}</td>";
                                        echo "<td>Rp. " . number_format($row['harga_layanan'], 0, ',', '.') . "</td>";
                                        echo "<td>
                                            <a class='dropdown-item edit-button' href='#' data-id='{$row['id_layanan']}' data-nama='{$row['nama_layanan']}' data-harga='{$row['harga_layanan']}'><i class='bx bx-edit-alt me-1'></i> Edit</a>
                                            <a class='dropdown-item delete-button' href='#' data-id='{$row['id_layanan']}' data-nama='{$row['nama_layanan']}'><i class='bx bx-trash me-1'></i> Hapus</a>
                                        </td>";
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
            <div class="col-xl">
                <div class="card mb-4">
                    <div class="card-header d-flex justify-content-between align-items-center" id="content">
                        <h5 class="mb-0">Tambah/Edit Layanan</h5>
                    </div>
                    <div class="card-body">
                        <form method="post" id="service-form">
                            <input type="hidden" name="id" id="service-id" value="">
                            <div class="mb-3">
                                <label class="form-label" for="basic-default-fullname">Nama Layanan</label>
                                <input type="text" class="form-control" name="nama_layanan" id="basic-default-fullname" placeholder="Layanan Express" required/>
                            </div>
                            <div class="mb-3">
                                <label class="form-label" for="basic-default-phone">Harga Layanan (Per kg)</label>
                                <input type="number" name="harga_layanan" id="basic-default-phone" class="form-control phone-mask" min='0' placeholder="Rp. 8000" required />
                            </div>
                            <div class="text-center">
                                <button type="submit" class="btn btn-info w-100" id="submit-button" name="create">Tambah</button>
                                <button type="button" class="btn btn-info w-100" id="update-button" style="display: none;">Edit</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- / Content -->
    <div class="content-backdrop fade"></div>
</div>
<!-- Content wrapper -->

<!-- Modal Konfirmasi Delete -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel">Konfirmasi Hapus</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Apakah Anda yakin ingin menghapus layanan <span id="delete-service-name"></span>?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-danger" id="confirm-delete-button">Hapus</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Konfirmasi Update -->
<div class="modal fade" id="updateConfirmModal" tabindex="-1" aria-labelledby="updateConfirmModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="updateConfirmModalLabel">Konfirmasi Update</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Apakah Anda yakin ingin mengupdate layanan <span id="edit-service-name-confirm"></span>?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-info" id="confirm-update-button">Edit</button>
            </div>
        </div>
    </div>
</div>

<script>
document.querySelectorAll('.edit-button').forEach(button => {
    button.addEventListener('click', function(event) {
        event.preventDefault();
        document.getElementById('submit-button').style.display = 'none';
        document.getElementById('update-button').style.display = 'block';
        document.getElementById('service-id').value = this.getAttribute('data-id');
        document.getElementById('basic-default-fullname').value = this.getAttribute('data-nama');
        document.getElementById('basic-default-phone').value = this.getAttribute('data-harga');
    });
});

document.querySelectorAll('.delete-button').forEach(button => {
    button.addEventListener('click', function(event) {
        event.preventDefault();

        const serviceId = this.getAttribute('data-id');
        const serviceName = this.getAttribute('data-nama');

        document.getElementById('delete-service-name').innerText = serviceName;
        new bootstrap.Modal(document.getElementById('deleteModal')).show();

        document.getElementById('confirm-delete-button').setAttribute('data-id', serviceId);
    });
});

document.getElementById('update-button').addEventListener('click', function(event) {
    event.preventDefault();
    
    // Show confirmation modal
    const serviceName = document.getElementById('basic-default-fullname').value;
    document.getElementById('edit-service-name-confirm').innerText = serviceName;
    new bootstrap.Modal(document.getElementById('updateConfirmModal')).show();
});

document.getElementById('confirm-update-button').addEventListener('click', function() {
    const serviceForm = document.getElementById('service-form');
    
    // Change the form's action to confirm update
    const confirmInput = document.createElement('input');
    confirmInput.type = 'hidden';
    confirmInput.name = 'confirm_update';
    confirmInput.value = '1';
    serviceForm.appendChild(confirmInput);

    serviceForm.submit();
});

document.getElementById('confirm-delete-button').addEventListener('click', function() {
    const serviceId = this.getAttribute('data-id');
    window.location.href = 'layanan.php?delete=' + serviceId;
});

// Prevent form submission with Enter key
document.getElementById('service-form').addEventListener('keydown', function(event) {
    if (event.key === 'Enter') {
        event.preventDefault();
    }
});
</script>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<script>
$(document).ready(function(){
    $("#content").click(function(){
        var value1 = $('#basic-default-fullname').val();
        var value2 = $('#basic-default-phone').val();
        if (value1 !== "" && value2 !== "")
            location.reload();
    });
});
</script>

<?php
// Close the connection here
$conn->close();
?>
