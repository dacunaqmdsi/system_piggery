<?php include('../includes/init.php'); ?>
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
            <tbody id="product-table-body">
                <tr>
                    <td colspan="6" class="text-center text-muted py-4">No products added yet.</td>
                </tr>
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
                <form id="product-form">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-dark">Product Name</label>
                            <input type="text" class="form-control border-2" placeholder="e.g., Grower Feed Pellets" style="border-color: #e5e7eb;" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-dark">Category</label>
                            <select class="form-select border-2" style="border-color: #e5e7eb;" required>
                                <option value="" disabled selected>Select category</option>
                                <option value="Feed">Feed</option>
                                <option value="Medicine">Medicine</option>
                                <option value="Vitamins">Vitamins</option>
                                <option value="Disinfectant">Disinfectant</option>
                                <option value="Equipment">Equipment</option>
                                <option value="Other">Other</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-dark">Current Quantity</label>
                            <input type="number" class="form-control border-2" placeholder="Enter current quantity" style="border-color: #e5e7eb;" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-dark">Unit</label>
                            <input type="text" class="form-control border-2" placeholder="e.g., kg, Liters, Bags, Units" style="border-color: #e5e7eb;" required>
                        </div>
                        <div class="col-12 mb-3">
                            <label class="form-label text-dark">Critical Level (Reorder Point)</label>
                            <input type="number" class="form-control border-2" placeholder="Enter reorder quantity level" style="border-color: #e5e7eb;" required>
                            <div class="form-text">A Purchase Order suggestion will be generated when quantity reaches this level.</div>
                        </div>
                    </div>
                    <div class="d-flex justify-content-end gap-2 pt-3">
                        <button type="button" class="btn btn-light text-dark" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn text-white" style="background-color: #ec4899;">Add Product</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>