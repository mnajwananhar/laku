<?php

include 'db/pengguna_crud.php'; // Pastikan ini disertakan di bagian atas

$alert = '';

// Handle form submission for create and update
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['create'])) {
        // Handle create
        $nama_pengguna = $_POST['nama_pengguna'];
        $kata_sandi = $_POST['kata_sandi'];
        $kata_sandi_hashed = password_hash($kata_sandi, PASSWORD_DEFAULT);
        $peran = $_POST['peran'];
        $result = createPengguna($nama_pengguna, $kata_sandi_hashed, $peran);
        if ($result['success']) {
            $alert = '<div class="alert alert-success" role="alert">Data berhasil ditambahkan!</div>';
        } else {
            $alert = '<div class="alert alert-danger" role="alert">Error: ' . $result['error'] . '</div>';
        }
    } elseif (isset($_POST['confirm_update'])) {
        // Handle update
        $id = $_POST['id'];
        $nama_pengguna = $_POST['nama_pengguna'];
        $kata_sandi = $_POST['kata_sandi'];
        $kata_sandi_hashed = password_hash($kata_sandi, PASSWORD_DEFAULT);
        $peran = $_POST['peran'];
        $result = updatePengguna($id, $nama_pengguna, $kata_sandi_hashed, $peran);
        if ($result['success']) {
            $alert = '<div class="alert alert-info" role="alert">Data berhasil diperbaharui!</div>';
        } else {
            $alert = '<div class="alert alert-danger" role="alert">Error: ' . $result['error'] . '</div>';
        }
    }
}

// Handle delete
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $result = deletePengguna($id);
    if ($result['success']) {
        $alert = '<div class="alert alert-danger" role="alert">Data berhasil dihapus!</div>';
        echo '<script>
                setTimeout(function() {
                    window.location.href = "pengguna.php";
                }, 3000);
              </script>';
    } else {
        $alert = '<div class="alert alert-danger" role="alert">Error: ' . $result['error'] . '</div>';
    }
}

// Get pengguna data
$penggunaResult = getPengguna();
?>

<?php include 'sidebar.php'; ?>
<?php include 'db/auth.php' ?>
<?php 
checkAccess(['manajer']); 
?>

<div class="content-wrapper">
    <!-- Content -->
    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="py-3 mb-4">Pengguna</h4>

        <?php echo $alert; ?>

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
                                        <th>Nama Pengguna</th>
                                        <th>Peran</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $no = 1;
                                    while ($row = $penggunaResult->fetch_assoc()) {
                                        echo "<tr>";
                                        echo "<td>{$no}</td>";
                                        echo "<td>{$row['nama_pengguna']}</td>";
                                        echo "<td><span class='badge bg-label-" . ($row['peran'] == 'manajer' ? 'primary' : 'info') . " me-1'>{$row['peran']}</span></td>";
                                        echo "<td>
                                            <a class='dropdown-item edit-user' href='#' data-id='{$row['id_pengguna']}' data-name='{$row['nama_pengguna']}' data-role='{$row['peran']}'>
                                                <i class='bx bx-edit-alt me-1'></i> Edit
                                            </a>
                                            <a class='dropdown-item delete-user' href='#' data-id='{$row['id_pengguna']}' data-name='{$row['nama_pengguna']}'>
                                                <i class='bx bx-trash me-1'></i> Delete
                                            </a>
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
                        <h5 class="mb-0" id="form-title">Tambah Pengguna</h5>
                    </div>
                    <div class="card-body">
                        <form method="post" id="user-form">
                            <input type="hidden" name="id" id="user-id">
                            <div class="mb-3">
                                <label class="form-label" for="basic-default-fullname">Nama Pengguna</label>
                                <input type="text" class="form-control" name="nama_pengguna" id="basic-default-fullname" placeholder="Nama Pengguna" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label" for="basic-default-password">Kata Sandi</label>
                                <input type="password" class="form-control" name="kata_sandi" id="basic-default-password" placeholder="••••••••" required>
                            </div>
                            <div class="mb-3">
                                <label for="exampleFormControlSelect1" class="form-label">Peran</label>
                                <select class="form-select" name="peran" id="exampleFormControlSelect1">
                                    <option value="kasir" selected>Kasir</option>
                                    <option value="manajer">Manajer</option>
                                </select>
                            </div>
                            <div class="text-center">
                                <button type="submit" class="btn btn-info w-100" id="form-button" name="create">Tambah</button>
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
<!-- / Content wrapper -->

<!-- Modal Konfirmasi Delete -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel">Konfirmasi Hapus</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Apakah Anda yakin ingin menghapus pengguna <span id="delete-user-name"></span>?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-danger" id="confirm-delete-button">Hapus</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Konfirmasi Edit -->
<div class="modal fade" id="editConfirmModal" tabindex="-1" aria-labelledby="editConfirmModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editConfirmModalLabel">Konfirmasi Edit</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Apakah Anda yakin ingin mengupdate pengguna <span id="edit-user-name-confirm"></span>?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-info" id="confirm-update-button">Edit</button>
            </div>
        </div>
    </div>
</div>

<script>
document.querySelectorAll('.edit-user').forEach(button => {
    button.addEventListener('click', function(event) {
        event.preventDefault();

        const userId = this.getAttribute('data-id');
        const userName = this.getAttribute('data-name');
        const userRole = this.getAttribute('data-role');

        document.getElementById('user-id').value = userId;
        document.getElementById('basic-default-fullname').value = userName;
        document.getElementById('basic-default-password').value = ''; // Password field will remain empty
        document.getElementById('exampleFormControlSelect1').value = userRole;
        document.getElementById('form-title').innerText = 'Edit Pengguna';
        document.getElementById('form-button').style.display = 'none';
        document.getElementById('update-button').style.display = 'block';

        
        // Change the password input placeholder
        document.getElementById('basic-default-password').placeholder = "Kosongkan jika kata sandi tidak diubah";
    });
});

document.getElementById('update-button').addEventListener('click', function(event) {
    event.preventDefault();
    
    // Show confirmation modal
    const userName = document.getElementById('basic-default-fullname').value;
    document.getElementById('edit-user-name-confirm').innerText = userName;


    new bootstrap.Modal(document.getElementById('editConfirmModal')).show();
});

document.getElementById('confirm-update-button').addEventListener('click', function() {
    const userForm = document.getElementById('user-form');
    
    // Change the form's action to confirm update
    const confirmInput = document.createElement('input');
    confirmInput.type = 'hidden';
    confirmInput.name = 'confirm_update';
    confirmInput.value = '1';
    userForm.appendChild(confirmInput);

    userForm.submit();
});

document.querySelectorAll('.delete-user').forEach(button => {
    button.addEventListener('click', function(event) {
        event.preventDefault();
        
        const userId = this.getAttribute('data-id');
        const userName = this.getAttribute('data-name');
        
        document.getElementById('delete-user-name').innerText = userName;
        new bootstrap.Modal(document.getElementById('deleteModal')).show();
        
        document.getElementById('confirm-delete-button').setAttribute('data-id', userId);
    });
});

document.getElementById('confirm-delete-button').addEventListener('click', function() {
    const userId = this.getAttribute('data-id');
    window.location.href = 'pengguna.php?delete=' + userId;

});

// Prevent form submission with Enter key
document.getElementById('user-form').addEventListener('keydown', function(event) {
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
        var value2 = $('#basic-default-password').val();
        var value3 = $('#exampleFormControlSelect1').val();
        if (value1 !== "" && value2 !== "" && value3 !== "")
            location.reload();
    });
});

// JavaScript Validation for Phone Number Input
document.addEventListener('DOMContentLoaded', function() {
    const userInputs = document.querySelectorAll('input[name="nama_pengguna"]');

    userInputs.forEach(input => {
        input.addEventListener('input', function() {
            let value = this.value;
            // Trim to 15 characters
            if (value.length > 15) {
                value = value.slice(0, 15);
            }
            this.value = value;
        });
    });
});

</script>

