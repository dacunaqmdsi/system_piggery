<?php include('../includes/init.php'); ?>
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

<script>
    const months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'];
    const populationData = [120, 135, 150, 145, 160, 175];
    const feedData = [800, 850, 900, 880, 950, 1000];
    const salesData = [5000, 5500, 6200, 5800, 6500, 7100];
    const healthData = {
        healthy: 160,
        sick: 10,
        quarantine: 5
    };

    new Chart(document.getElementById('pigPopulationChart'), {
        type: 'line',
        data: {
            labels: months,
            datasets: [{
                label: 'Total Pigs',
                data: populationData,
                borderColor: 'rgb(75, 192, 192)',
                tension: 0.1,
                fill: false
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });

    new Chart(document.getElementById('feedConsumptionChart'), {
        type: 'bar',
        data: {
            labels: months,
            datasets: [{
                label: 'Feed Consumed (kg)',
                data: feedData,
                backgroundColor: 'rgba(255, 159, 64, 0.7)',
                borderColor: 'rgba(255, 159, 64, 1)',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });

    new Chart(document.getElementById('salesOverviewChart'), {
        type: 'bar',
        data: {
            labels: months,
            datasets: [{
                label: 'Revenue (₱)',
                data: salesData,
                backgroundColor: 'rgba(54, 162, 235, 0.7)',
                borderColor: 'rgba(54, 162, 235, 1)',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });

    new Chart(document.getElementById('healthStatusChart'), {
        type: 'doughnut',
        data: {
            labels: ['Healthy', 'Sick', 'Quarantine'],
            datasets: [{
                label: 'Health Status',
                data: [healthData.healthy, healthData.sick, healthData.quarantine],
                backgroundColor: ['rgba(75, 192, 192, 0.7)', 'rgba(255, 99, 132, 0.7)', 'rgba(255, 206, 86, 0.7)'],
                hoverOffset: 4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false
        }
    });

    const MONITORING_LOG_KEY = 'farmMonitoringLog';

    function getLogEntries() {
        const json = localStorage.getItem(MONITORING_LOG_KEY);
        try {
            return json ? JSON.parse(json) : [];
        } catch {
            return [];
        }
    }

    function renderRecentLogs() {
        const logs = getLogEntries();
        const body = document.getElementById('recent-log-body');
        body.innerHTML = '';
        if (!logs.length) {
            body.innerHTML = '<tr><td colspan="3" class="text-center text-muted">No recent log entries.</td></tr>';
            return;
        }
        logs.sort((a, b) => new Date(b.date) - new Date(a.date)).slice(0, 5).forEach(log => {
            const row = document.createElement('tr');
            row.innerHTML = `
        <td>${log.date}</td>
        <td>${log.category}</td>
        <td>${log.notes}</td>
      `;
            body.appendChild(row);
        });
    }

    const placeholderNotifications = [{
            id: 'n1',
            type: 'warning',
            title: 'Low Feed Stock',
            message: 'Feed quantity for Fatteners is below 50kg.',
            timestamp: '2023-11-15 08:30 AM'
        },
        {
            id: 'n2',
            type: 'info',
            title: 'Vaccination Reminder',
            message: 'Sow Pen S2 is due for vaccination next week.',
            timestamp: '2023-11-14 10:00 AM'
        },
        {
            id: 'n3',
            type: 'success',
            title: 'New Birth Recorded',
            message: 'A new birth record was added for Sow Pen S1 (10 alive).',
            timestamp: '2023-11-13 02:15 PM'
        },
        {
            id: 'n4',
            type: 'info',
            title: 'System Update',
            message: 'Welcome to the updated Piggery Farm Management System!',
            timestamp: '2023-11-10 09:00 AM'
        }
    ];

    function renderRecentNotifications() {
        const list = document.getElementById('recent-notifications-list');
        const empty = document.getElementById('no-recent-notifications');
        list.innerHTML = '';
        if (!placeholderNotifications.length) {
            empty.classList.remove('d-none');
            return;
        }
        empty.classList.add('d-none');
        placeholderNotifications.slice(0, 4).forEach(n => {
            const div = document.createElement('div');
            div.className = `border-start ps-3 py-2 small bg-light rounded ${n.type === 'warning' ? 'border-warning' : n.type === 'success' ? 'border-success' : 'border-info'}`;
            div.innerHTML = `
        <div class="d-flex gap-2">
          <i class="fas ${n.type === 'warning' ? 'fa-exclamation-triangle text-warning' : n.type === 'success' ? 'fa-check-circle text-success' : 'fa-info-circle text-info'} pt-1"></i>
          <div>
            <div class="fw-semibold">${n.title}</div>
            <div class="text-muted">${n.message}</div>
            <div class="text-muted small">${n.timestamp}</div>
          </div>
        </div>`;
            list.appendChild(div);
        });
    }

    renderRecentLogs();
    renderRecentNotifications();
</script>