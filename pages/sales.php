<?php include('../includes/init.php'); ?>
<div class="container-fluid ">

    <!-- Page Title -->
    <h1 class="h3 fw-bold text-dark mb-4">Record New Sale</h1>

    <!-- Sale Input Card -->
    <div class="bg-white p-4 rounded shadow-sm mb-5">
        <form id="sale-form" onsubmit="generateReceipt(event)">
            <div class="row mb-4">
                <div class="col-md-6 mb-3">
                    <label for="sale-date" class="form-label text-dark">Date</label>
                    <input type="date" id="sale-date" class="form-control border-2" required style="border-color: #e5e7eb;">
                </div>
                <div class="col-md-6 mb-3">
                    <label for="customer-name" class="form-label text-dark">Customer Name</label>
                    <select id="customer-name-select" class="form-select border-2" required style="border-color: #e5e7eb;">
                        <option value="" disabled selected>-- Select Customer --</option>
                        <option value="_guest_">Guest/Walk-in Customer</option>
                    </select>
                    <input type="hidden" id="customer-name-hidden">
                </div>
            </div>

            <!-- Sale Items Header -->
            <h5 class="fw-semibold text-secondary border-top pt-4 mb-3">Sale Items</h5>

            <!-- Sale Items Container -->
            <div id="sale-items-container" class="mb-3">
                <div class="row g-3 border-bottom pb-3 sale-item-row">
                    <div class="col-md-3">
                        <label class="form-label text-dark">Source Pen</label>
                        <select class="form-select border-2 item-pen-select" onchange="handlePenSelection(this)" required style="border-color: #e5e7eb;">
                            <option value="" disabled selected>-- Select Pen --</option>
                        </select>
                        <input type="hidden" class="item-pen-name">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label text-dark">Select Pig Type</label>
                        <select class="form-select border-2 item-pig-type-select" onchange="updateItemDescription(this)" required style="border-color: #e5e7eb;">
                            <option value="" disabled selected>-- Select Pen First --</option>
                        </select>
                        <input type="hidden" class="item-description">
                    </div>
                    <div class="col-md-3 other-description-container" style="display: none;">
                        <label class="form-label text-dark">Specify Other Type</label>
                        <input type="text" class="form-control border-2 item-other-description" placeholder="Enter custom type" style="border-color: #e5e7eb;">
                    </div>
                    <div class="col-md-1">
                        <label class="form-label text-dark">Qty</label>
                        <input type="number" min="1" step="1" value="1" class="form-control border-2 item-quantity" required style="border-color: #e5e7eb;">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label text-dark">Unit Price</label>
                        <input type="number" min="0" step="0.01" value="0.00" class="form-control border-2 item-price" required style="border-color: #e5e7eb;">
                    </div>
                    <div class="col-auto d-flex align-items-end">
                        <button type="button" onclick="removeSaleItem(this)" class="btn btn-danger px-3"><i class="fas fa-trash-alt"></i></button>
                    </div>
                </div>
            </div>

            <!-- Add Item Button -->
            <button type="button" onclick="addSaleItem()" class="btn btn-outline-secondary btn-sm mb-3">
                <i class="fas fa-plus me-1"></i> Add Item
            </button>

            <!-- Grand Total -->
            <div class="text-end border-top pt-3">
                <h5 class="fw-bold text-dark">Grand Total: <span id="grand-total">₱0.00</span></h5>
            </div>

            <p id="sale-form-error" class="text-danger mt-2 d-none small"></p>

            <div class="text-end mt-4">
                <button type="submit" class="btn text-white px-4 py-2 fw-semibold"
                    style="background: linear-gradient(135deg, #ec4899 0%, #ec4899 100%);">
                    <i class="fas fa-receipt me-2"></i>Generate Receipt & Record Sale
                </button>
            </div>
        </form>
    </div>

    <!-- Receipt Display -->
    <div id="receipt-display-container" class="d-none mb-5">
        <h4 class="fw-bold text-dark text-center mb-3">Sale Receipt</h4>
        <div id="receipt-content" class="p-4 border rounded mx-auto" style="max-width: 600px; font-family: monospace; background-color: #fff; box-shadow: 0 2px 5px rgba(0,0,0,0.1);">
            <!-- Receipt content populated via JS -->
        </div>
        <div class="text-center mt-3">
            <button onclick="printReceipt()" class="btn btn-outline-secondary me-2"><i class="fas fa-print me-1"></i>Print Receipt</button>
            <button onclick="closeReceiptDisplay()" class="btn btn-outline-secondary">Close</button>
        </div>
    </div>

    <!-- Sales History -->
    <div>
        <h4 class="fw-bold text-dark mb-3">Sales History</h4>
        <div class="mb-3">
            <input type="text" id="sales-search-input" placeholder="Search by Receipt ID..." oninput="filterSalesHistory()" class="form-control w-100" style="max-width: 400px;">
        </div>

        <div class="bg-white p-3 rounded shadow-sm table-responsive">
            <table class="table table-bordered table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th>Receipt ID</th>
                        <th>Date</th>
                        <th>Customer</th>
                        <th>Total Amount</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody id="sales-history-body">
                    <tr>
                        <td colspan="5" class="text-center text-muted py-4">No sales recorded yet.</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>