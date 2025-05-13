<?php include('../includes/init.php'); ?>
<?php
if (isset($_POST['add_user'])) {
    $username = escape_str($db_connection, $_POST['username']);
    $account_password = escape_str($db_connection, $_POST['account_password']);
    $account_type = escape_str($db_connection, $_POST['account_type']);

    $query = "INSERT INTO tblaccounts (username, account_password, account_type)
              VALUES ('$username', '$account_password', '$account_type')";

    if (mysqli_query($db_connection, $query)) {
        echo "User successfully added.";
    } else {
        echo "Error: " . mysqli_error($db_connection);
    }
}

// Update user
if (isset($_POST['update_user'])) {
    $accountid = escape_str($db_connection, $_POST['accountid']);
    $username = escape_str($db_connection, $_POST['username']);
    $account_type = escape_str($db_connection, $_POST['account_type']);

    $query = "UPDATE tblaccounts SET 
                username = '$username',
                account_type = '$account_type'";

    if (!empty($_POST['account_password'])) {
        $account_password = escape_str($db_connection, $_POST['account_password']);
        $query .= ", account_password = '$account_password'";
    }

    $query .= " WHERE accountid = '$accountid'";

    if (mysqli_query($db_connection, $query)) {
        echo "User successfully updated.";
    } else {
        echo "Error: " . mysqli_error($db_connection);
    }
}

?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 fw-bold text-dark">User Management</h1>
    <button class="btn text-white fw-semibold d-flex align-items-center px-4 py-2 rounded"
        style="background: linear-gradient(to right, #ec4899, #ec4899);"
        data-bs-toggle="modal" data-bs-target="#userModal">
        <i class="fas fa-plus me-2"></i>
        <span>Add User</span>
    </button>
</div>

<div class="bg-white p-4 rounded shadow-sm">
    <div class="table-responsive">
        <table class="table table-bordered table-hover align-middle">
            <thead class="table-light">
                <tr>
                    <th>Username</th>
                    <th>Account Type</th>
                    <th class="text-center">Actions</th>
                </tr>
            </thead>
            <tbody id="user-table-body">
                <?php
                $rs = mysqli_query($db_connection, 'select * from tblaccounts ');
                while ($rw = mysqli_fetch_array($rs)) {
                    echo '<tr>
                        <td>' . htmlspecialchars($rw['username']) . '</td>
                        <td>' . htmlspecialchars($rw['account_type']) . '</td>
                        <td>';
                    echo '<a style="text-decoration: none" href="javascript:void(0);" onclick="select_user(' . $rw['accountid'] . ');" data-bs-toggle="modal" data-bs-target="#userEditModal">Edit</a>';
                    echo '</td>
                    </tr>';
                }
                ?>
            </tbody>
        </table>
    </div>
</div>

<!-- User Modal -->
<div class="modal fade" id="userModal" tabindex="-1" aria-labelledby="userModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 rounded shadow">
            <div class="modal-header bg-white border-bottom-0">
                <h5 class="modal-title fw-semibold text-dark" id="userModalLabel">Add New User</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body px-4 py-3">

                <div class="mb-3">
                    <label for="modal-username" class="form-label text-dark">Username</label>
                    <input id="username" type="text" class="form-control border-2"
                        placeholder="Enter username" style="border-color: #e5e7eb;" required>
                </div>
                <div class="mb-3">
                    <label for="modal-password" class="form-label text-dark">Password</label>
                    <input id="account_password" type="password" class="form-control border-2"
                        placeholder="Enter password" style="border-color: #e5e7eb;">
                    <small class="form-text text-muted">Leave blank to keep current password.</small>
                </div>
                <div class="mb-3">
                    <label for="modal-account-type" class="form-label text-dark">Account Type</label>
                    <select id="account_type" class="form-select border-2"
                        style="border-color: #e5e7eb;" required>
                        <option value="" disabled selected>Select account type</option>
                        <option value="Farm Owner">Farm Owner</option>
                        <option value="Care Taker">Care Taker</option>
                    </select>
                </div>
                <div class="d-flex justify-content-end gap-2 pt-3">
                    <button type="button" class="btn btn-light text-dark" data-bs-dismiss="modal">Cancel</button>
                    <button onclick="add_user();" class="btn text-white" style="background-color: #ec4899;">Add User</button>
                </div>

            </div>
        </div>
    </div>
</div>


<!-- Edit User Modal -->
<div class="modal fade" id="userEditModal" tabindex="-1" aria-labelledby="userModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 rounded shadow">
            <div class="modal-header bg-white border-bottom-0">
                <h5 class="modal-title fw-semibold text-dark" id="userModalLabel">Update User</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body px-4 py-3">
                <input type="text" id="accountid_edit" hidden />
                <div class="mb-3">
                    <label for="modal-username" class="form-label text-dark">Username</label>
                    <input id="username_edit" type="text" class="form-control border-2"
                        placeholder="Enter username" style="border-color: #e5e7eb;" required>
                </div>
                <div class="mb-3">
                    <label for="modal-password" class="form-label text-dark">Password</label>
                    <input id="account_password_edit" type="text" class="form-control border-2"
                        placeholder="Enter password" style="border-color: #e5e7eb;">
                    <small class="form-text text-muted">Leave blank to keep current password.</small>
                </div>
                <div class="mb-3">
                    <label for="modal-account-type" class="form-label text-dark">Account Type</label>
                    <select id="account_type_edit" class="form-select border-2"
                        style="border-color: #e5e7eb;" required>
                        <option value="" disabled selected>Select account type</option>
                        <option value="Farm Owner">Farm Owner</option>
                        <option value="Care Taker">Care Taker</option>
                    </select>
                </div>
                <div class="d-flex justify-content-end gap-2 pt-3">
                    <button type="button" class="btn btn-light text-dark" data-bs-dismiss="modal">Cancel</button>
                    <button onclick="edit_user();" class="btn text-white" style="background-color: #ec4899;">Update User</button>
                </div>

            </div>
        </div>
    </div>
</div>