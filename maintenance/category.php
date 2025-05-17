<?php include('../includes/init.php'); ?>

<?php
// Handle Add Category
if (isset($_POST['add_category'])) {
    $category = mysqli_real_escape_string($db_connection, $_POST['category']);
    mysqli_query($db_connection, "INSERT INTO tblcategory (category, is_archived) VALUES ('$category', 'No')");
    Audit($user['accountid'], 'Added category', 'Added category');
}

// Handle Edit Category
if (isset($_POST['edit_category']) && isset($_POST['categoryid'])) {
    $category = mysqli_real_escape_string($db_connection, $_POST['category']);
    $categoryid = intval($_POST['categoryid']);
    mysqli_query($db_connection, "UPDATE tblcategory SET category = '$category' WHERE categoryid = $categoryid");
    Audit($user['accountid'], 'Updated category', 'Updated category');
}

// Handle Get for Editing
$category = '';
$categoryid = '';
if (isset($_GET['categoryid'])) {
    $categoryid = intval($_GET['categoryid']);
    $result = mysqli_query($db_connection, "SELECT category FROM tblcategory WHERE categoryid = $categoryid");
    $row = mysqli_fetch_assoc($result);
    if ($row) {
        $category = $row['category'];
    }
}

// Toggle Archive Status
if (isset($_GET['archive_not'])) {
    $categoryids = intval($_GET['archive_not']);

    // Get current archive status
    $result = mysqli_query($db_connection, "SELECT is_archived FROM tblcategory WHERE categoryid = $categoryids");
    if ($row = mysqli_fetch_assoc($result)) {
        $current = strtolower($row['is_archived']) === 'yes' ? 'No' : 'Yes';
        mysqli_query($db_connection, "UPDATE tblcategory SET is_archived = '$current' WHERE categoryid = $categoryids");
    }
}
?>
<div class="main-container">
    <div class="section-title">Category</div>

    <div class="mb-3 text-end">
        <input type="text" id="category" value="<?= htmlspecialchars($category); ?>" placeholder="Category" class="form-control d-inline-block w-auto me-2" />
        <button class="btn btn-primary" onclick="add_edit_category(<?= $categoryid ? $categoryid : 'null'; ?>);">
            <?= $categoryid ? 'Update' : 'Add'; ?>
        </button>
    </div>

    <div class="bg-white p-4 rounded shadow-sm">
        <div class="table-responsive">
            <table class="table table-bordered table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th>Category Name</th>
                        <th>Is Archived?</th>
                        <th colspan="2" class="text-center">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $rs = mysqli_query($db_connection, "SELECT categoryid, category, is_archived FROM tblcategory ORDER BY category ASC");
                    while ($rw = mysqli_fetch_assoc($rs)) {
                        $archived = $rw['is_archived'] === 'Yes' ? 'Yes' : 'No';
                        $toggleText = $archived === 'Yes' ? 'Unarchive' : 'Archive';

                        echo '<tr>
                            <td>' . htmlspecialchars($rw['category']) . '</td>
                            <td>' . $archived . '</td>
                               <td>
        <button class="btn btn-sm text-white" 
                style="background-color: #e546ad;" 
                onclick="ajax_fn(\'maintenance/category.php?categoryid=' . $rw['categoryid'] . '\', \'tmp_content\');" 
                title="Edit Category">
            <i class="fas fa-edit"></i>
        </button>
    </td>
    <td>
        <button class="btn btn-sm btn-secondary" 
                onclick="ajax_fn(\'maintenance/category.php?archive_not=' . $rw['categoryid'] . '\', \'tmp_content\');" 
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