<?php include('../includes/init.php'); is_blocked();?>
<!-- Header + Buttons -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 fw-bold text-dark">Expenses</h1>
    <div>
        <button class="btn btn-outline-secondary fw-semibold d-flex align-items-center me-3 px-4 py-2 rounded"
            data-bs-toggle="modal" data-bs-target="#poModal">
            <i class="fas fa-file-invoice-dollar me-2"></i>
            <span>Generate PO (Low Stock)</span>
        </button>
        <button class="btn text-white fw-semibold d-flex align-items-center px-4 py-2 rounded"
            style="background: linear-gradient(to right, #ec4899, #ec4899);"
            data-bs-toggle="modal" data-bs-target="#expenseModal">
            <i class="fas fa-plus me-2"></i>
            <span>Add Expense</span>
        </button>
    </div>
</div>


<?php
if (isset($_POST['add_expense'])) {
    // Sanitize and escape inputs
    $expense_date = mysqli_real_escape_string($db_connection, $_POST['expense_date']);
    $categoryid = mysqli_real_escape_string($db_connection, $_POST['categoryid']);
    $description = mysqli_real_escape_string($db_connection, $_POST['description']);
    $amount = mysqli_real_escape_string($db_connection, $_POST['amount']);
    $refnum = mysqli_real_escape_string($db_connection, $_POST['refnum']);

    // Insert query
    $query = "INSERT INTO tblexpense (expense_date, categoryid, description, amount, refnum)
              VALUES ('$expense_date', '$categoryid', '$description', '$amount', '$refnum')";
    Audit($_SESSION['accountid'], 'Added expenses', 'Added expenses');
    if (mysqli_query($db_connection, $query)) {
        echo "Expense successfully added.";
    } else {
        echo "Error: " . mysqli_error($db_connection);
    }
}


if (isset($_POST['edit_expense'])) {
    // Sanitize and escape inputs
    $expenseid_edit = mysqli_real_escape_string($db_connection, $_POST['expenseid_edit']);
    $expense_date_edit = mysqli_real_escape_string($db_connection, $_POST['expense_date_edit']);
    $categoryid_edit = mysqli_real_escape_string($db_connection, $_POST['categoryid_edit']);
    $description_edit = mysqli_real_escape_string($db_connection, $_POST['description_edit']);
    $amount_edit = mysqli_real_escape_string($db_connection, $_POST['amount_edit']);
    $refnum_edit = mysqli_real_escape_string($db_connection, $_POST['refnum_edit']);

    // Update query
    $query = "UPDATE tblexpense 
              SET expense_date = '$expense_date_edit',
                  categoryid = '$categoryid_edit',
                  description = '$description_edit',
                  amount = '$amount_edit',
                  refnum = '$refnum_edit'
              WHERE expenseid = '$expenseid_edit'";
    Audit($_SESSION['accountid'], 'Updated expenses', 'Updated expenses');
    if (mysqli_query($db_connection, $query)) {
        echo "Expense successfully updated.";
    } else {
        echo "Error: " . mysqli_error($db_connection);
    }
}
?>

<!-- Expense Table -->
<div class="bg-white p-4 rounded shadow-sm">
    <div class="table-responsive">
        <table class="table table-bordered table-hover align-middle">
            <thead class="table-light">
                <tr>
                    <th>Date</th>
                    <th>Category</th>
                    <th>Description</th>
                    <th>Amount (₱)</th>
                    <th>Receipt/Ref #</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <!-- expenseEditModal -->
            <tbody id="expense-table-body">
                <?php

                $query = "SELECT * FROM tblexpense "; // Adjust field if `id` is named differently
                $result = mysqli_query($db_connection, $query);

                if (mysqli_num_rows($result) > 0) {
                    while ($row = mysqli_fetch_assoc($result)) {
                        $category = GetValue('select category from tblcategory where categoryid=' . $row['categoryid']);
                        echo '
                            <tr>
                                <td>' . htmlspecialchars($row['expense_date']) . '</td>
                                <td>' . htmlspecialchars($category) . '</td>
                                <td>' . htmlspecialchars($row['description']) . '</td>
                                <td>' . htmlspecialchars($row['amount']) . '</td>
                                <td>' . htmlspecialchars($row['refnum']) . '</td>
                                <td>
                                    <button onclick="select_expense(' . $row['expenseid'] . ');" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#expenseEditModal">Edit</button>
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

<!-- Bootstrap Expense Modal -->
<div class="modal fade" id="expenseModal" tabindex="-1" aria-labelledby="expenseModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-md">
        <div class="modal-content border-0 rounded shadow">
            <div class="modal-header bg-white border-bottom-0">
                <h5 class="modal-title fw-semibold text-dark" id="expenseModalLabel">Add New Expense</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body px-4 py-3">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label text-dark">Date</label>
                        <input type="date" id="expense_date" class="form-control border-2" style="border-color: #e5e7eb;" required>
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
                    <div class="col-12 mb-3">
                        <label class="form-label text-dark">Description</label>
                        <input type="text" id="description" placeholder="e.g., Purchase of 10 bags Grower Feed"
                            class="form-control border-2" style="border-color: #e5e7eb;" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label text-dark">Amount (₱)</label>
                        <input type="number" id="amount" min="0" step="0.01"
                            class="form-control border-2" placeholder="Enter amount" style="border-color: #e5e7eb;" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label text-dark">Receipt/Reference # (Optional)</label>
                        <input type="text" id="refnum" class="form-control border-2"
                            placeholder="e.g., OR# 12345" style="border-color: #e5e7eb;">
                    </div>
                </div>
                <div class="d-flex justify-content-end gap-2 pt-3">
                    <button type="button" class="btn btn-light text-dark" data-bs-dismiss="modal">Cancel</button>
                    <button onclick="add_expense();" class="btn text-white" style="background-color: #ec4899;">Add Expense</button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Bootstrap Expense Modal -->
<div class="modal fade" id="expenseEditModal" tabindex="-1" aria-labelledby="expenseModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-md">
        <div class="modal-content border-0 rounded shadow">
            <div class="modal-header bg-white border-bottom-0">
                <h5 class="modal-title fw-semibold text-dark" id="expenseModalLabel">Add New Expense</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body px-4 py-3">
                <input type="text" hidden id="expenseid_edit" />
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label text-dark">Date</label>
                        <input type="date" id="expense_date_edit" class="form-control border-2" style="border-color: #e5e7eb;" required>
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
                    <div class="col-12 mb-3">
                        <label class="form-label text-dark">Description</label>
                        <input type="text" id="description_edit" placeholder="e.g., Purchase of 10 bags Grower Feed"
                            class="form-control border-2" style="border-color: #e5e7eb;" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label text-dark">Amount (₱)</label>
                        <input type="number" id="amount_edit" min="0" step="0.01"
                            class="form-control border-2" placeholder="Enter amount" style="border-color: #e5e7eb;" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label text-dark">Receipt/Reference # (Optional)</label>
                        <input type="text" id="refnum_edit" class="form-control border-2"
                            placeholder="e.g., OR# 12345" style="border-color: #e5e7eb;">
                    </div>
                </div>
                <div class="d-flex justify-content-end gap-2 pt-3">
                    <button type="button" class="btn btn-light text-dark" data-bs-dismiss="modal">Cancel</button>
                    <button onclick="edit_expense();" class="btn text-white" style="background-color: #ec4899;">Update Expense</button>
                </div>
            </div>
        </div>
    </div>
</div>


<!-- Purchase Order Modal (Bootstrapified Static Version) -->
<div class="modal fade" id="poModal" tabindex="-1" aria-labelledby="poModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 rounded shadow">
            <div class="modal-header bg-white border-bottom-0">
                <h5 class="modal-title fw-semibold text-dark" id="poModalLabel">Purchase Order Suggestion</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body px-4 py-3">
                <div id="po-content">
                    <p class="text-center text-muted py-4">Loading Purchase Order...</p>
                </div>
                <div class="text-center mt-4">
                    <button onclick="printPO()" class="btn btn-outline-secondary px-4 py-2">
                        <i class="fas fa-print me-2"></i>Print Purchase Order
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>