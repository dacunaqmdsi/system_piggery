<?php
include('../includes/init.php'); // Make sure this includes the DB connection

if (isset($_POST['add_customer'])) {
    // Escape all user inputs to prevent SQL Injection
    $name = mysqli_real_escape_string($db_connection, $_POST['name']);
    $phone = mysqli_real_escape_string($db_connection, $_POST['phone']);
    $email = mysqli_real_escape_string($db_connection, $_POST['email']);
    $address = mysqli_real_escape_string($db_connection, $_POST['address']);
    $notes = mysqli_real_escape_string($db_connection, $_POST['notes']);

    // Insert query
    $query = "INSERT INTO tblcustomer (name, phone, email, address, notes) 
              VALUES ('$name', '$phone', '$email', '$address', '$notes')";
    Audit($_SESSION['accountid'], 'Added new customer', 'Added new customer');
    if (mysqli_query($db_connection, $query)) {
        echo "Customer successfully added.";
    } else {
        echo "Error: " . mysqli_error($db_connection);
    }
}

if (isset($_POST['edit_customer'])) {
    // Escape all user inputs to prevent SQL Injection
    $customerid = mysqli_real_escape_string($db_connection, $_POST['customerid_edit']);
    $name = mysqli_real_escape_string($db_connection, $_POST['name_edit']);
    $phone = mysqli_real_escape_string($db_connection, $_POST['phone_edit']);
    $email = mysqli_real_escape_string($db_connection, $_POST['email_edit']);
    $address = mysqli_real_escape_string($db_connection, $_POST['address_edit']);
    $notes = mysqli_real_escape_string($db_connection, $_POST['notes_edit']);

    // Update query
    $query = "UPDATE tblcustomer 
              SET name = '$name', 
                  phone = '$phone', 
                  email = '$email', 
                  address = '$address', 
                  notes = '$notes' 
              WHERE customerid = '$customerid'";
    // echo $query;

    Audit($_SESSION['accountid'], 'Updated new customer', 'Updated new customer');
    if (mysqli_query($db_connection, $query)) {
        echo "Customer successfully updated.";
    } else {
        echo "Error: " . mysqli_error($db_connection);
    }
}

?>

<!-- Header + Button -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 fw-bold text-dark">Customer Profiles</h1>
    <button class="btn text-white fw-semibold d-flex align-items-center px-4 py-2 rounded"
        style="background: linear-gradient(to right, #ec4899, #ec4899);"
        data-bs-toggle="modal" data-bs-target="#customerModal">
        <i class="fas fa-plus me-2"></i>
        <span>Add Customer</span>
    </button>
</div>

<!-- Table -->
<div class="bg-white p-4 rounded shadow-sm">
    <div class="table-responsive">
        <table class="table table-bordered table-hover align-middle">
            <thead class="table-light">
                <tr>
                    <th>Name</th>
                    <th>Phone</th>
                    <th>Email</th>
                    <th>Address</th>
                    <th>Notes</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody id="customer-table-body">
                <?php

                $query = "SELECT * FROM tblcustomer ORDER BY customerid DESC"; // Adjust field if `id` is named differently
                $result = mysqli_query($db_connection, $query);

                if (mysqli_num_rows($result) > 0) {
                    while ($row = mysqli_fetch_assoc($result)) {
                        echo '
                            <tr>
                                <td>' . htmlspecialchars($row['name']) . '</td>
                                <td>' . htmlspecialchars($row['phone']) . '</td>
                                <td>' . htmlspecialchars($row['email']) . '</td>
                                <td>' . htmlspecialchars($row['address']) . '</td>
                                <td>' . htmlspecialchars($row['notes']) . '</td>
                                <td>
                                    <button onclick="select_customer(' . $row['customerid'] . ');" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#customerEditModal">Edit</button>
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
<div class="modal fade" id="customerModal" tabindex="-1" aria-labelledby="customerModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 rounded shadow">
            <div class="modal-header bg-white border-bottom-0">
                <h5 class="modal-title fw-semibold text-dark" id="customerModalLabel">Add New Customer</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body px-4 py-3">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label text-dark">Full Name</label>
                        <input type="text" id="name" class="form-control border-2" placeholder="Enter full name" style="border-color: #e5e7eb;">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label text-dark">Phone Number</label>
                        <input type="number" id="phone" class="form-control border-2" placeholder="Enter phone number" style="border-color: #e5e7eb;">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label text-dark">Email Address</label>
                        <input type="email" id="email" class="form-control border-2" placeholder="Enter email address" style="border-color: #e5e7eb;">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label text-dark">Address</label>
                        <input type="text" id="address" class="form-control border-2" placeholder="Enter address" style="border-color: #e5e7eb;">
                    </div>
                    <div class="col-12 mb-3">
                        <label class="form-label text-dark">Notes</label>
                        <textarea class="form-control border-2" id="notes" placeholder="Any relevant notes about the customer" rows="3" style="border-color: #e5e7eb;"></textarea>
                    </div>
                </div>
                <div class="d-flex justify-content-end gap-2 pt-3">
                    <button type="button" class="btn btn-light text-dark" data-bs-dismiss="modal">Cancel</button>
                    <button onclick="add_customer();" class="btn text-white" style="background-color: #ec4899;">Add Customer</button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Bootstrap Modal -->
<div class="modal fade" id="customerEditModal" tabindex="-1" aria-labelledby="customerModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 rounded shadow">
            <div class="modal-header bg-white border-bottom-0">
                <h5 class="modal-title fw-semibold text-dark" id="customerModalLabel">Update Customer</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body px-4 py-3">
                <div class="row">
                    <input type="text" hidden id="customerid_edit" />
                    <div class="col-md-6 mb-3">
                        <label class="form-label text-dark">Full Name</label>
                        <input type="text" id="name_edit" class="form-control border-2" placeholder="Enter full name" style="border-color: #e5e7eb;">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label text-dark">Phone Number</label>
                        <input type="number" id="phone_edit" class="form-control border-2" placeholder="Enter phone number" style="border-color: #e5e7eb;">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label text-dark">Email Address</label>
                        <input type="email" id="email_edit" class="form-control border-2" placeholder="Enter email address" style="border-color: #e5e7eb;">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label text-dark">Address</label>
                        <input type="text" id="address_edit" class="form-control border-2" placeholder="Enter address" style="border-color: #e5e7eb;">
                    </div>
                    <div class="col-12 mb-3">
                        <label class="form-label text-dark">Notes</label>
                        <textarea class="form-control border-2" id="notes_edit" placeholder="Any relevant notes about the customer" rows="3" style="border-color: #e5e7eb;"></textarea>
                    </div>
                </div>
                <div class="d-flex justify-content-end gap-2 pt-3">
                    <button type="button" class="btn btn-light text-dark" data-bs-dismiss="modal">Cancel</button>
                    <button onclick="edit_customer();" class="btn text-white" style="background-color: #ec4899;">Update Customer</button>
                </div>
            </div>
        </div>
    </div>
</div>