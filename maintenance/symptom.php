<?php include('../includes/init.php'); ?>

<?php
// Handle Add Category
if (isset($_POST['add_symptom'])) {
    $symptom = mysqli_real_escape_string($db_connection, $_POST['symptom']);
    $suggested_action = mysqli_real_escape_string($db_connection, $_POST['suggested_action']);
    $status_ = mysqli_real_escape_string($db_connection, $_POST['status_']);
    mysqli_query($db_connection, "INSERT INTO tblsymptom (symptom, suggested_action,status_,  is_archived) VALUES ('$symptom','$suggested_action', '$status_', 'No')");
    Audit($user['accountid'], 'Added symptom', 'Added symptom');
}

// Handle Edit Category
if (isset($_POST['edit_symptom']) && isset($_POST['symptom_id'])) {
    $symptom = mysqli_real_escape_string($db_connection, $_POST['symptom']);
    $suggested_action = mysqli_real_escape_string($db_connection, $_POST['suggested_action']);
    $status_ = mysqli_real_escape_string($db_connection, $_POST['status_']);
    $symptom_id = intval($_POST['symptom_id']);
    mysqli_query($db_connection, "UPDATE tblsymptom SET symptom = '$symptom',  suggested_action = '$suggested_action' , status_ = '$status_' WHERE symptom_id = $symptom_id");
    Audit($user['accountid'], 'Updated symptom', 'Updated symptom');
}

// Handle Get for Editing
$symptom = '';
$symptom_id = '';
$status_ = '';
if (isset($_GET['symptom_id'])) {
    $symptom_id = intval($_GET['symptom_id']);
    $result = mysqli_query($db_connection, "SELECT symptom, suggested_action, status_ FROM tblsymptom WHERE symptom_id = $symptom_id");
    $row = mysqli_fetch_assoc($result);
    if ($row) {
        $symptom = $row['symptom'];
        $suggested_action = $row['suggested_action'];
        $status_ = $row['status_'];
    }
}

// Toggle Archive Status
if (isset($_GET['archive_not'])) {
    $ii = $_GET['archive_not'];

    // Get current archive status
    $result = mysqli_query($db_connection, "SELECT is_archived FROM tblsymptom WHERE symptom_id = $ii");
    if ($row = mysqli_fetch_assoc($result)) {
        $current = strtolower($row['is_archived']) === 'yes' ? 'No' : 'Yes';
        mysqli_query($db_connection, "UPDATE tblsymptom SET is_archived = '$current' WHERE symptom_id = $ii");
    }
}
?>
<div class="main-container">
    <div class="section-title">Symptoms</div>
    <div style="text-align:right;"><span style="text-align:right;"><small>Status: Ill, Under Observation, Death</small></span></div><br>
    <div class="mb-3 text-end">
        <input type="text" id="symptom" value="<?= htmlspecialchars($symptom); ?>" placeholder="Symptom" class="form-control d-inline-block w-auto me-2" />
        <input type="text" id="suggested_action" value="<?= htmlspecialchars($suggested_action); ?>" placeholder="Suggested Action" class="form-control d-inline-block w-auto me-2" />
        <input type="text" id="status_" value="<?= htmlspecialchars($status_); ?>" placeholder="Status" class="form-control d-inline-block w-auto me-2" />
        <button class="btn btn-primary" onclick="add_edit_symptom(<?= $symptom_id ? $symptom_id : 'null'; ?>);">
            <?= $symptom_id ? 'Update' : 'Add'; ?>
        </button>
    </div>

    <div class="bg-white p-4 rounded shadow-sm">
        <div class="table-responsive">
            <table class="table table-bordered table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th>Symptom</th>
                        <th>Suggested Action</th>
                        <th>Status</th>
                        <th>Is Archived?</th>
                        <th colspan="2" class="text-center">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $rs = mysqli_query($db_connection, "SELECT symptom_id, symptom, suggested_action, status_, is_archived FROM tblsymptom");
                    while ($rw = mysqli_fetch_assoc($rs)) {
                        $archived = $rw['is_archived'] === 'Yes' ? 'Yes' : 'No';
                        $toggleText = $archived === 'Yes' ? 'Unarchive' : 'Archive';

                        echo '<tr>
                            <td>' . htmlspecialchars($rw['symptom']) . '</td>
                            <td>' . htmlspecialchars($rw['suggested_action']) . '</td>
                            <td>' . htmlspecialchars($rw['status_']) . '</td>
                            <td>' . $archived . '</td>
                               <td>
        <button class="btn btn-sm text-white" 
                style="background-color: #e546ad;" 
                onclick="ajax_fn(\'maintenance/symptom.php?symptom_id=' . $rw['symptom_id'] . '\', \'tmp_content\');" 
                title="Edit Symptom">
            <i class="fas fa-edit"></i>
        </button>
    </td>
    <td>
        <button class="btn btn-sm btn-secondary" 
                onclick="ajax_fn(\'maintenance/symptom.php?archive_not=' . $rw['symptom_id'] . '\', \'tmp_content\');" 
                title="' . htmlspecialchars($toggleText) . '">
            <i class="fas fa-toggle-' . (strtolower($toggleText) === 'archive' ? 'off' : 'on') . '"></i>
        </button>
    </td></tr>';
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>