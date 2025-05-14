<?php include('../includes/init.php'); ?>

<?php
if (isset($_POST['add_product'])) {
    // Sanitize and escape input values
    $product_name = mysqli_real_escape_string($db_connection, $_POST['product_name']);
    $categoryid = mysqli_real_escape_string($db_connection, $_POST['categoryid']);
    $current_qty = mysqli_real_escape_string($db_connection, $_POST['current_qty']);
    $unit = mysqli_real_escape_string($db_connection, $_POST['unit']);
    $critical_level = mysqli_real_escape_string($db_connection, $_POST['critical_level']);

    // Build and execute the insert query
    $query = "
        INSERT INTO tblproducts 
        (product_name, categoryid, current_qty, unit, critical_level) 
        VALUES 
        ('$product_name', '$categoryid', '$current_qty', '$unit', '$critical_level')
    ";
    Audit($_SESSION['accountid'], 'Added new product', 'Added new product');
    if (mysqli_query($db_connection, $query)) {
        echo "Product successfully added.";
    } else {
        echo "Error: " . mysqli_error($db_connection);
    }
}

if (isset($_POST['edit_product'])) {
    // Sanitize and escape input values
    $product_id = mysqli_real_escape_string($db_connection, $_POST['product_id_edit']);
    $product_name = mysqli_real_escape_string($db_connection, $_POST['product_name_edit']);
    $categoryid = mysqli_real_escape_string($db_connection, $_POST['categoryid_edit']);
    $current_qty = mysqli_real_escape_string($db_connection, $_POST['current_qty_edit']);
    $unit = mysqli_real_escape_string($db_connection, $_POST['unit_edit']);
    $critical_level = mysqli_real_escape_string($db_connection, $_POST['critical_level_edit']);

    // Build and execute the update query
    $query = "
        UPDATE tblproducts
        SET 
            product_name = '$product_name',
            categoryid = '$categoryid',
            current_qty = '$current_qty',
            unit = '$unit',
            critical_level = '$critical_level'
        WHERE product_id = '$product_id'
    ";
    Audit($_SESSION['accountid'], 'Updated new product', 'Updated new product');
    if (mysqli_query($db_connection, $query)) {
        echo "Product successfully updated.";
    } else {
        echo "Error: " . mysqli_error($db_connection);
    }
}
?>

<!-- Header + Button -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 fw-bold text-dark">Product Inventory</h1>
    <button class="btn text-white fw-semibold d-flex align-items-center px-4 py-2 rounded"
        style="background: linear-gradient(to right, #ec4899, #ec4899);"
        data-bs-toggle="modal" data-bs-target="#productModal">
        <i class="fas fa-plus me-2"></i>
        <span>Add Product</span>
    </button>
</div>

<!-- Table -->
<div class="bg-white p-4 rounded shadow-sm">
    <div class="table-responsive">
        <table class="table table-bordered table-hover align-middle">
            <thead class="table-light">
                <tr>
                    <th>Product Name</th>
                    <th>Category</th>
                    <th>Quantity</th>
                    <th>Unit</th>
                    <th>Critical Level</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <!-- productEditModal -->
            <tbody id="product-table-body">
                <?php

                $query = "SELECT * FROM tblproducts "; // Adjust field if `id` is named differently
                $result = mysqli_query($db_connection, $query);

                if (mysqli_num_rows($result) > 0) {
                    while ($row = mysqli_fetch_assoc($result)) {
                        $category = GetValue('select category from tblcategory where categoryid=' . $row['categoryid']);
                        echo '
                            <tr>
                                <td>' . htmlspecialchars($row['product_name']) . '</td>
                                <td>' . htmlspecialchars($category) . '</td>
                                <td>' . htmlspecialchars($row['current_qty']) . '</td>
                                <td>' . htmlspecialchars($row['unit']) . '</td>
                                <td>' . htmlspecialchars($row['critical_level']) . '</td>
                                <td>
                                    <button onclick="select_product(' . $row['product_id'] . ');" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#productEditModal">Edit</button>
                                </td>
                            </tr>
                        ';
                    }
                } else {
                    echo '
                        <tr>
                            <td colspan="6" class="text-center text-muted py-4">No customers added yet.</td>
                        </tr>
                    ';
                }
                ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Bootstrap Modal -->
<div class="modal fade" id="productModal" tabindex="-1" aria-labelledby="productModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 rounded shadow">
            <div class="modal-header bg-white border-bottom-0">
                <h5 class="modal-title fw-semibold text-dark" id="productModalLabel">Add New Product</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body px-4 py-3">

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label text-dark">Product Name</label>
                        <input id="product_name" type="text" class="form-control border-2" placeholder="e.g., Grower Feed Pellets" style="border-color: #e5e7eb;" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label text-dark">Category</label>
                        <select id="categoryid" class="form-select border-2" style="border-color: #e5e7eb;" required>
                            <option value="" disabled selected>Select category</option>
                            <?php
                            $res = mysqli_query($db_connection, "SELECT categoryid, category FROM tblcategory WHERE is_archived = 'No' ORDER BY category ASC");
                            while ($row = mysqli_fetch_assoc($res)) {
                                echo '<option value="' . $row['categoryid'] . '">' . htmlspecialchars($row['category']) . '</option>';
                            }
                            ?>
                        </select>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label text-dark">Current Quantity</label>
                        <input id="current_qty" type="number" class="form-control border-2" placeholder="Enter current quantity" style="border-color: #e5e7eb;" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label text-dark">Unit</label>
                        <input id="unit" type="text" class="form-control border-2" placeholder="e.g., kg, Liters, Bags, Units" style="border-color: #e5e7eb;" required>
                    </div>
                    <div class="col-12 mb-3">
                        <label class="form-label text-dark">Critical Level (Reorder Point)</label>
                        <input id="critical_level" type="number" class="form-control border-2" placeholder="Enter reorder quantity level" style="border-color: #e5e7eb;" required>
                        <div class="form-text">A Purchase Order suggestion will be generated when quantity reaches this level.</div>
                    </div>
                </div>
                <div class="d-flex justify-content-end gap-2 pt-3">
                    <button type="button" class="btn btn-light text-dark" data-bs-dismiss="modal">Cancel</button>
                    <button onclick="add_product();" class="btn text-white" style="background-color: #ec4899;">Add Product</button>
                </div>

            </div>
        </div>
    </div>
</div>


<!-- Bootstrap Modal -->
<div class="modal fade" id="productEditModal" tabindex="-1" aria-labelledby="productModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 rounded shadow">
            <div class="modal-header bg-white border-bottom-0">
                <h5 class="modal-title fw-semibold text-dark" id="productModalLabel">Update Product</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body px-4 py-3">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <input type="text" hidden id="product_id_edit" />
                        <label class="form-label text-dark">Product Name</label>
                        <input type="text" id="product_name_edit" class="form-control border-2" placeholder="e.g., Grower Feed Pellets" style="border-color: #e5e7eb;" required>
                    </div>
                    <div class="col-md-6 mb-3">

                        <label class="form-label text-dark">Category</label>
                        <select id="categoryid_edit" class="form-select border-2" style="border-color: #e5e7eb;" required>
                            <option value="" disabled selected>Select category</option>
                            <?php
                            $res = mysqli_query($db_connection, "SELECT categoryid, category FROM tblcategory WHERE is_archived = 'No' ORDER BY category ASC");
                            while ($row = mysqli_fetch_assoc($res)) {
                                echo '<option value="' . $row['categoryid'] . '">' . htmlspecialchars($row['category']) . '</option>';
                            }
                            ?>
                        </select>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label text-dark">Current Quantity</label>
                        <input type="number" id="current_qty_edit" class="form-control border-2" placeholder="Enter current quantity" style="border-color: #e5e7eb;" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label text-dark">Unit</label>
                        <input type="text" id="unit_edit" class="form-control border-2" placeholder="e.g., kg, Liters, Bags, Units" style="border-color: #e5e7eb;" required>
                    </div>
                    <div class="col-12 mb-3">
                        <label class="form-label text-dark">Critical Level (Reorder Point)</label>
                        <input type="number" id="critical_level_edit" class="form-control border-2" placeholder="Enter reorder quantity level" style="border-color: #e5e7eb;" required>
                        <div class="form-text">A Purchase Order suggestion will be generated when quantity reaches this level.</div>
                    </div>
                </div>
                <div class="d-flex justify-content-end gap-2 pt-3">
                    <button type="button" class="btn btn-light text-dark" data-bs-dismiss="modal">Cancel</button>
                    <button onclick="edit_product();" class="btn text-white" style="background-color: #ec4899;">Update Product</button>
                </div>
            </div>
        </div>
    </div>
</div>