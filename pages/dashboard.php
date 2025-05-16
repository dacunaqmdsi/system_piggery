<?php include('../includes/init.php');
is_blocked(); ?>
<div class="container-fluid py-4 bg-light">
    <h1 class="h3 fw-bold text-dark mb-4">Farm Overview Dashboard</h1>

    <div class="row g-4">

        <!-- Pig Population Chart -->
        <div class="col-md-6">
            <div class="bg-white p-3 rounded shadow-sm">
                <h5 class="text-center fw-semibold text-secondary mb-3">Pig Population Trend</h5>
                <div style="height:300px;"><canvas id="pigPopulationChart"></canvas></div>
            </div>
        </div>

        <!-- Feed Consumption Chart -->
        <div class="col-md-6">
            <div class="bg-white p-3 rounded shadow-sm">
                <h5 class="text-center fw-semibold text-secondary mb-3">Monthly Feed Consumption (kg)</h5>
                <div style="height:300px;"><canvas id="feedConsumptionChart"></canvas></div>
            </div>
        </div>

        <!-- Sales Overview Chart -->
        <div class="col-md-6">
            <div class="bg-white p-3 rounded shadow-sm">
                <h5 class="text-center fw-semibold text-secondary mb-3">Sales Overview (Last 6 Months)</h5>
                <div style="height:300px;"><canvas id="salesOverviewChart"></canvas></div>
            </div>
        </div>

        <!-- Health Status Chart -->
        <div class="col-md-6">
            <div class="bg-white p-3 rounded shadow-sm">
                <h5 class="text-center fw-semibold text-secondary mb-3">Pig Health Status</h5>
                <div style="height:300px;"><canvas id="healthStatusChart"></canvas></div>
            </div>
        </div>

        <!-- Recent Activity Log -->
        <!-- <div class="col-12">
            <div class="bg-white p-4 rounded shadow-sm">
                <h5 class="fw-semibold text-secondary mb-3">Recent Activity Log</h5>
                <div class="table-responsive" style="max-height: 300px; overflow-y: auto;">
                    <table class="table table-sm table-bordered align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>Date</th>
                                <th>Category/Area</th>
                                <th>Notes</th>
                            </tr>
                        </thead>
                        <tbody id="recent-log-body">
                            <tr>
                                <td colspan="3" class="text-center text-muted">No recent log entries.</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div> -->

        <!-- Recent Notifications -->
        <div class="col-12">
            <div class="bg-white p-4 rounded shadow-sm">
                <h5 class="fw-semibold text-secondary mb-3">Recent Notifications</h5>
                <div id="recent-notifications-list" class="d-flex flex-column gap-2" style="max-height: 300px; overflow-y: auto;">
                    <p id="no-recent-notifications" class="text-center text-muted">No recent notifications.</p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
