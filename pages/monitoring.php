<?php include('../includes/init.php'); ?>
<div class="container-fluid">
    <h1 class="mb-4 fw-bold text-dark fs-2">Daily Monitoring Log</h1>

    <!-- Log Entry Form -->
    <div class="card p-4 shadow-sm mb-5">
        <h2 class="h5 fw-semibold mb-3 text-dark">Log Health Observation</h2>
        <form id="monitoring-log-form" onsubmit="addLogEntry(event)">
            <div class="row g-3 mb-3">
                <div class="col-md-6">
                    <label for="log-date" class="form-label fw-medium">Date</label>
                    <input type="date" id="log-date" class="form-control" required />
                </div>
                <div class="col-md-6">
                    <label for="log-identifier" class="form-label fw-medium">Pen ID / Pig Tag</label>
                    <input type="text" id="log-identifier" class="form-control" placeholder="e.g., Pen F5, Pig #102" required />
                </div>
            </div>
            <div class="mb-3">
                <label for="log-symptoms" class="form-label fw-medium">Symptoms / Observations</label>
                <textarea class="form-control" id="log-symptoms" rows="3" placeholder="Describe symptoms like coughing, lameness, not eating, etc." required></textarea>
            </div>
            <div class="mb-3">
                <label for="log-action" class="form-label fw-medium">Suggested Action (System)</label>
                <input type="text" id="log-action" class="form-control bg-light" placeholder="Action will be suggested after saving..." readonly />
            </div>
            <p id="log-form-error" class="text-danger d-none small mb-3"></p>
            <div class="text-end">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-notes-medical me-2"></i>Log Observation & Suggest Action
                </button>
            </div>
        </form>
    </div>

    <!-- Log History -->
    <div>
        <h2 class="h5 fw-bold text-dark mb-3">Health Log History</h2>
        <div class="card p-3 shadow-sm">
            <div class="table-responsive">
                <table class="table table-bordered table-striped align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th style="width: 15%;">Date</th>
                            <th style="width: 20%;">Pen/Pig</th>
                            <th style="width: 35%;">Symptoms/Observations</th>
                            <th style="width: 30%;">Suggested Action</th>
                        </tr>
                    </thead>
                    <tbody id="log-history-body">
                        <tr>
                            <td colspan="4" class="text-center text-muted py-3">No health log entries found.</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>