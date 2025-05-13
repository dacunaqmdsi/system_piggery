<?php include('../includes/init.php'); ?>
<div class="container-fluid">

    <!-- Header + Buttons -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 fw-bold text-dark">Pig Pen Inventory</h1>
        <div class="d-flex gap-2">
            <button class="btn text-white px-4 py-2 fw-semibold rounded" style="background: linear-gradient(135deg, #e546b5 0%, #e546b5 100%);" data-bs-toggle="modal" data-bs-target="#addPenModal">
                <i class="fas fa-plus me-2"></i> Add Pen
            </button>
            <button class="btn text-white px-4 py-2 fw-semibold rounded" style="background: linear-gradient(135deg, #e546b5 0%, #e546b5 100%);" data-bs-toggle="modal" data-bs-target="#recordBirthModal">
                <i class="fas fa-baby me-2"></i> Record Birth
            </button>
        </div>
    </div>

    <!-- Inventory Tables -->
    <div class="row g-4 mb-5">
        <!-- Sow Pens -->
        <div class="col-lg-6">
            <div class="bg-white p-4 rounded shadow-sm">
                <h5 class="fw-semibold text-secondary mb-3">Sow Pens (<span id="total-sows" class="fw-bold">0</span>)</h5>
                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>Pen Number</th>
                                <th>Count</th>
                            </tr>
                        </thead>
                        <tbody id="sow-table-body">
                            <tr>
                                <td colspan="2" class="text-center text-muted py-4">No sow pens added yet.</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Boar Pens -->
        <div class="col-lg-6">
            <div class="bg-white p-4 rounded shadow-sm">
                <h5 class="fw-semibold text-secondary mb-3">Boar Pens (<span id="total-boars" class="fw-bold">0</span>)</h5>
                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>Pen Number</th>
                                <th>Count</th>
                            </tr>
                        </thead>
                        <tbody id="boar-table-body">
                            <tr>
                                <td colspan="2" class="text-center text-muted py-4">No boar pens added yet.</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Fattener Pens -->
        <div class="col-lg-6">
            <div class="bg-white p-4 rounded shadow-sm">
                <h5 class="fw-semibold text-secondary mb-3">Fattener Pens (<span id="total-fatteners" class="fw-bold">0</span>)</h5>
                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>Pen Number</th>
                                <th>Count</th>
                            </tr>
                        </thead>
                        <tbody id="fattener-table-body">
                            <tr>
                                <td colspan="2" class="text-center text-muted py-4">No fattener pens added yet.</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Piglet Pens -->
        <div class="col-lg-6">
            <div class="bg-white p-4 rounded shadow-sm">
                <h5 class="fw-semibold text-secondary mb-3">Piglet Pens (<span id="total-piglets" class="fw-bold">0</span>)</h5>
                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>Pen Number</th>
                                <th>Mother Sow</th>
                                <th>Count</th>
                            </tr>
                        </thead>
                        <tbody id="piglet-table-body">
                            <tr>
                                <td colspan="3" class="text-center text-muted py-4">No piglet pens added yet.</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Birthing Records -->
    <div class="mb-5">
        <h4 class="fw-bold text-dark mb-3">Birthing Records</h4>
        <div class="bg-white p-4 rounded shadow-sm table-responsive">
            <table class="table table-bordered table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th>Date of Birth</th>
                        <th>Mother Sow (Pen#)</th>
                        <th>Total Born</th>
                        <th>Deaths</th>
                        <th>Alive</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody id="birthing-records-body">
                    <tr>
                        <td colspan="6" class="text-center text-muted py-4">No birthing records found.</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Add Pen Modal -->
    <div class="modal fade" id="addPenModal" tabindex="-1" aria-labelledby="addPenModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content border-0 rounded shadow">
                <div class="modal-header bg-white border-bottom-0">
                    <h5 class="modal-title fw-bold text-dark" id="addPenModalLabel">Add New Pen</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body px-4 py-3">
                    <form id="add-pen-form" onsubmit="handleAddPenSubmit(event)">
                        <div class="mb-3">
                            <label for="pen-number" class="form-label text-dark">Pen Number</label>
                            <input id="pen-number" type="text" class="form-control border-2" placeholder="e.g., S1, B2, F10, P1-Litter" required>
                            <div class="form-text">Use a unique identifier for the pen.</div>
                        </div>
                        <div class="mb-3">
                            <label for="pen-type" class="form-label text-dark">Pen Type</label>
                            <select id="pen-type" class="form-select border-2" required>
                                <option value="" disabled selected>Select pen type</option>
                                <option value="Sow">Sow</option>
                                <option value="Boar">Boar</option>
                                <option value="Fattener">Fattener</option>
                                <option value="Piglet">Piglet</option>
                            </select>
                        </div>

                        <div id="piglet-options" class="d-none">
                            <div class="mb-3">
                                <label for="mother-sow-pen" class="form-label text-dark">Mother Sow Pen</label>
                                <select id="mother-sow-pen" class="form-select">
                                    <option value="" disabled selected>Select mother sow's pen</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="piglet-count" class="form-label text-dark">Number of Piglets</label>
                                <input id="piglet-count" type="number" class="form-control" min="0">
                            </div>
                        </div>
                        <!-- id="sow-boar-fattener-options" class="d-none" -->
                        <div>
                            <div class="mb-3">
                                <label for="animal-count" class="form-label text-dark">Number of Animals</label>
                                <input id="animal-count" type="number" class="form-control" value="1" min="0">
                            </div>
                        </div>

                        <p id="add-pen-modal-error" class="text-danger small d-none mt-3"></p>
                        <div class="d-flex justify-content-end gap-2 pt-3">
                            <button type="button" class="btn btn-light text-dark" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn text-white" style="background-color: #ec4899;">Add Pen</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Record Birth Modal -->
    <div class="modal fade" id="recordBirthModal" tabindex="-1" aria-labelledby="recordBirthModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content border-0 rounded shadow">
                <div class="modal-header bg-white border-bottom-0">
                    <h5 class="modal-title fw-bold text-dark" id="recordBirthModalLabel">Record New Birth</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body px-4 py-3">
                    <form id="record-birth-form" onsubmit="handleRecordBirthSubmit(event)">
                        <div class="mb-3">
                            <label for="birth-mother-sow-pen" class="form-label text-dark">Mother Sow Pen</label>
                            <select id="birth-mother-sow-pen" class="form-select border-2" required>
                                <option value="" disabled selected>Select mother sow's pen</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="birth-date" class="form-label text-dark">Date of Birth</label>
                            <input id="birth-date" type="date" class="form-control border-2" required>
                        </div>
                        <div class="mb-3">
                            <label for="birth-total-born" class="form-label text-dark">Total Piglets Born</label>
                            <input id="birth-total-born" type="number" min="0" class="form-control border-2" required>
                        </div>
                        <div class="mb-3">
                            <label for="birth-deaths" class="form-label text-dark">Number of Deaths</label>
                            <input id="birth-deaths" type="number" value="0" min="0" class="form-control border-2" required>
                        </div>
                        <div class="mb-3">
                            <label for="birth-alive" class="form-label text-dark">Number Alive</label>
                            <input id="birth-alive" type="number" class="form-control border-2" readonly placeholder="Calculated automatically">
                        </div>

                        <p id="record-birth-modal-error" class="text-danger small d-none mt-3"></p>
                        <div class="d-flex justify-content-end gap-2 pt-3">
                            <button type="button" class="btn btn-light text-dark" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn text-white" style="background-color: #ec4899;">Record Birth</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

</div>