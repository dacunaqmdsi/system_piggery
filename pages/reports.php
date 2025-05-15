<?php include('../includes/init.php'); is_blocked(); ?>
<div class="container-fluid bg-light d-flex flex-column">

    <!-- Header -->
    <header class="bg-white shadow-sm py-3 px-4 d-flex flex-wrap justify-content-between align-items-center mb-4">
        <h2 class="h4 fw-bold text-dark mb-2 mb-md-0">Reports</h2>
        <div class="d-flex flex-wrap gap-2">
            <select id="reportType" class="form-select" style="width: auto;">
                <option value="monthly">Monthly Report</option>
                <option value="quarterly">Quarterly Report</option>
                <option value="yearly">Yearly Report</option>
            </select>
            <button onclick="generateReport()" class="btn text-white" style="background-color: #4f46e5;">
                <i class="fas fa-download me-2"></i> Generate Report
            </button>
        </div>
    </header>

    <!-- Main Content -->
    <main class="px-4 pb-5 flex-grow-1">
        <div class="row g-4">

            <!-- Sales Overview -->
            <div class="col-lg-6">
                <div class="bg-white p-4 rounded shadow-sm">
                    <h5 class="fw-semibold text-dark mb-3">Sales Overview (Sample)</h5>
                    <div class="row row-cols-2 g-3">
                        <div>
                            <div class="fs-3 fw-bold text-dark">₱75,000</div>
                            <div class="text-muted small">Total Sales (Period)</div>
                        </div>
                        <div>
                            <div class="fs-3 fw-bold text-dark">₱12,500</div>
                            <div class="text-muted small">Average Sale Value</div>
                        </div>
                        <div>
                            <div class="fs-3 fw-bold text-dark">6</div>
                            <div class="text-muted small">Number of Sales</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Expenses Breakdown -->
            <div class="col-lg-6">
                <div class="bg-white p-4 rounded shadow-sm">
                    <h5 class="fw-semibold text-dark mb-3">Expenses Breakdown (Sample)</h5>
                    <div class="table-responsive">
                        <table class="table table-sm table-bordered">
                            <thead class="table-light">
                                <tr>
                                    <th>Category</th>
                                    <th class="text-end">Amount (₱)</th>
                                    <th class="text-end">Percentage</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>Feed</td>
                                    <td class="text-end">45,000</td>
                                    <td class="text-end">45%</td>
                                </tr>
                                <tr>
                                    <td>Medicine</td>
                                    <td class="text-end">20,000</td>
                                    <td class="text-end">20%</td>
                                </tr>
                                <tr>
                                    <td>Labor</td>
                                    <td class="text-end">15,000</td>
                                    <td class="text-end">15%</td>
                                </tr>
                                <tr>
                                    <td>Utilities</td>
                                    <td class="text-end">10,000</td>
                                    <td class="text-end">10%</td>
                                </tr>
                                <tr>
                                    <td>Maintenance</td>
                                    <td class="text-end">10,000</td>
                                    <td class="text-end">10%</td>
                                </tr>
                            </tbody>
                            <tfoot class="fw-semibold">
                                <tr>
                                    <td>Total</td>
                                    <td class="text-end">100,000</td>
                                    <td class="text-end">100%</td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Inventory Status -->
            <div class="col-lg-6">
                <div class="bg-white p-4 rounded shadow-sm">
                    <h5 class="fw-semibold text-dark mb-3">Inventory Status (Sample)</h5>
                    <div class="table-responsive">
                        <table class="table table-sm table-bordered">
                            <thead class="table-light">
                                <tr>
                                    <th>Category</th>
                                    <th class="text-end">Count</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>Sows</td>
                                    <td class="text-end">30</td>
                                </tr>
                                <tr>
                                    <td>Boars</td>
                                    <td class="text-end">5</td>
                                </tr>
                                <tr>
                                    <td>Piglets</td>
                                    <td class="text-end">120</td>
                                </tr>
                                <tr>
                                    <td>Growers</td>
                                    <td class="text-end">80</td>
                                </tr>
                                <tr>
                                    <td>Fatteners</td>
                                    <td class="text-end">60</td>
                                </tr>
                            </tbody>
                            <tfoot class="fw-semibold">
                                <tr>
                                    <td>Total Pigs</td>
                                    <td class="text-end">295</td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Health Statistics -->
            <div class="col-lg-6">
                <div class="bg-white p-4 rounded shadow-sm">
                    <h5 class="fw-semibold text-dark mb-3">Health Statistics (Sample)</h5>
                    <div class="row row-cols-3 text-center g-3">
                        <div>
                            <div class="fs-3 fw-bold text-success">85%</div>
                            <div class="text-muted small">Healthy</div>
                        </div>
                        <div>
                            <div class="fs-3 fw-bold text-warning">12%</div>
                            <div class="text-muted small">Under Treatment</div>
                        </div>
                        <div>
                            <div class="fs-3 fw-bold text-danger">3%</div>
                            <div class="text-muted small">Critical</div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </main>
</div>

<script>
    function generateReport() {
        const reportType = document.getElementById('reportType').value;
        alert(`Generating ${reportType} report (placeholder)... Sample data shown.`);
        console.log(`Report Type Selected: ${reportType}`);
    }

    document.addEventListener('DOMContentLoaded', function() {
        console.log("Reports page loaded. Displaying sample static data.");
    });
</script>