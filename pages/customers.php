<?php include('../includes/init.php'); ?>
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
                <tr>
                    <td colspan="6" class="text-center text-muted py-4">No customers added yet.</td>
                </tr>
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
                <form id="customer-form">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-dark">Full Name</label>
                            <input type="text" class="form-control border-2" placeholder="Enter full name" style="border-color: #e5e7eb;">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-dark">Phone Number</label>
                            <input type="text" class="form-control border-2" placeholder="Enter phone number" style="border-color: #e5e7eb;">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-dark">Email Address</label>
                            <input type="email" class="form-control border-2" placeholder="Enter email address" style="border-color: #e5e7eb;">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-dark">Address</label>
                            <input type="text" class="form-control border-2" placeholder="Enter address" style="border-color: #e5e7eb;">
                        </div>
                        <div class="col-12 mb-3">
                            <label class="form-label text-dark">Notes</label>
                            <textarea class="form-control border-2" placeholder="Any relevant notes about the customer" rows="3" style="border-color: #e5e7eb;"></textarea>
                        </div>
                    </div>
                    <div class="d-flex justify-content-end gap-2 pt-3">
                        <button type="button" class="btn btn-light text-dark" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn text-white" style="background-color: #ec4899;">Add Customer</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>