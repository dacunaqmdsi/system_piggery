<?php include('../includes/init.php'); ?>
<!-- Management Header + Button -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 fw-bold text-dark">User Management</h1>
    <button class="btn text-white fw-semibold d-flex align-items-center px-4 py-2 rounded"
        style="background: linear-gradient(to right, #ec4899, #ec4899);"
        data-bs-toggle="modal" data-bs-target="#userModal">
        <i class="fas fa-plus me-2"></i>
        <span>Add User</span>
    </button>
</div>

<!-- Table -->
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
                <!-- User rows will be populated here -->
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
                <form id="user-form">
                    <div class="mb-3">
                        <label for="modal-username" class="form-label text-dark">Username</label>
                        <input id="modal-username" type="text" class="form-control border-2"
                            placeholder="Enter username" style="border-color: #e5e7eb;" required>
                    </div>
                    <div class="mb-3">
                        <label for="modal-password" class="form-label text-dark">Password</label>
                        <input id="modal-password" type="password" class="form-control border-2"
                            placeholder="Enter password" style="border-color: #e5e7eb;">
                        <small class="form-text text-muted">Leave blank to keep current password.</small>
                    </div>
                    <div class="mb-3">
                        <label for="modal-account-type" class="form-label text-dark">Account Type</label>
                        <select id="modal-account-type" class="form-select border-2"
                            style="border-color: #e5e7eb;" required>
                            <option value="" disabled selected>Select account type</option>
                            <option value="Farm Owner">Farm Owner</option>
                            <option value="Care Taker">Care Taker</option>
                        </select>
                    </div>
                    <div class="d-flex justify-content-end gap-2 pt-3">
                        <button type="button" class="btn btn-light text-dark" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn text-white" style="background-color: #ec4899;">Add User</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>