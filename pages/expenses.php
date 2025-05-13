<?php include('../includes/init.php'); ?>
<!-- Header + Buttons -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 fw-bold text-dark">Expenses</h1>
    <div>
        <button class="btn btn-outline-secondary fw-semibold d-flex align-items-center me-3 px-4 py-2 rounded"
            onclick="generateLowStockPO()">
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
            <tbody id="expense-table-body">
                <tr>
                    <td colspan="6" class="text-center text-muted py-4">No expenses recorded yet.</td>
                </tr>
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
                <form id="expense-form" onsubmit="handleExpenseFormSubmit(event)">
                    <input type="hidden" id="edit-expense-id">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-dark">Date</label>
                            <input type="date" id="expense-date" class="form-control border-2" style="border-color: #e5e7eb;" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-dark">Category</label>
                            <select id="expense-category" class="form-select border-2" style="border-color: #e5e7eb;" required>
                                <option value="" disabled selected>Select category</option>
                                <option value="Feed">Feed</option>
                                <option value="Medicine/Vitamins">Medicine/Vitamins</option>
                                <option value="Utilities">Utilities (Water, Electricity)</option>
                                <option value="Salaries">Salaries/Labor</option>
                                <option value="Maintenance/Repairs">Maintenance/Repairs</option>
                                <option value="Equipment Purchase">Equipment Purchase</option>
                                <option value="Transportation">Transportation</option>
                                <option value="Other">Other</option>
                            </select>
                        </div>
                        <div class="col-12 mb-3">
                            <label class="form-label text-dark">Description</label>
                            <input type="text" id="expense-description" placeholder="e.g., Purchase of 10 bags Grower Feed"
                                class="form-control border-2" style="border-color: #e5e7eb;" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-dark">Amount (₱)</label>
                            <input type="number" id="expense-amount" min="0" step="0.01"
                                class="form-control border-2" placeholder="Enter amount" style="border-color: #e5e7eb;" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-dark">Receipt/Reference # (Optional)</label>
                            <input type="text" id="expense-receipt" class="form-control border-2"
                                placeholder="e.g., OR# 12345" style="border-color: #e5e7eb;">
                        </div>
                    </div>
                    <div class="d-flex justify-content-end gap-2 pt-3">
                        <button type="button" class="btn btn-light text-dark" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn text-white" style="background-color: #ec4899;">Add Expense</button>
                    </div>
                </form>
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