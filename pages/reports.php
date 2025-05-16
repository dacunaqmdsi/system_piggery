<?php include('../includes/init.php');
is_blocked();

$q = "
   SELECT 
    COUNT(DISTINCT receipt_id) AS num_sales,
    SUM(sub.total) AS total_sales,
    AVG(sub.total) AS average_sale_value
FROM (
    SELECT 
        receipt_id,
        SUM(qty * price) AS total
    FROM 
        tblsales
    GROUP BY 
        receipt_id
) AS sub

";

$rs = mysqli_query($db_connection, $q);
$data = mysqli_fetch_assoc($rs);

// Safe fallback if null
$total_sales = $data['total_sales'] ?? 0;
$average_sale_value = $data['average_sale_value'] ?? 0;
$num_sales = $data['num_sales'] ?? 0;

?>
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

            <button onclick="openCustom('pages/reports_gen?reportType='+document.getElementById('reportType').value,700,700);" class="btn text-white" style="background-color: #4f46e5;">
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
                            <div class="fs-3 fw-bold text-dark">₱<?= number_format($total_sales, 2) ?></div>
                            <div class="text-muted small">Total Sales (Period)</div>
                        </div>
                        <div>
                            <div class="fs-3 fw-bold text-dark">₱<?= number_format($average_sale_value, 2) ?></div>
                            <div class="text-muted small">Average Sale Value</div>
                        </div>
                        <div>
                            <div class="fs-3 fw-bold text-dark"><?= $num_sales ?></div>
                            <div class="text-muted small">Number of Sales</div>
                        </div>
                    </div>
                </div>
            </div>

            <?php
            $total_query = mysqli_query($db_connection, "SELECT SUM(amount) AS total_expense FROM tblexpense");
            $total_row = mysqli_fetch_assoc($total_query);
            $total_expense = $total_row['total_expense'];

            $query = "
    SELECT 
        categoryid AS category_name, 
        SUM(amount) AS category_total 
    FROM 
        tblexpense 
    GROUP BY 
        categoryid 
    ORDER BY 
        category_total DESC
";
            $result = mysqli_query($db_connection, $query);
            ?>

            <!-- Expenses Breakdown -->
            <div class="col-lg-6">
                <div class="bg-white p-4 rounded shadow-sm">
                    <h5 class="fw-semibold text-dark mb-3">Expenses Breakdown</h5>
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
                                <?php
                                $grand_total = 0;
                                while ($row = mysqli_fetch_assoc($result)) {
                                    $cat = GetValue('select category from tblcategory where categoryid=' . $row['category_name']);
                                    $amount = $row['category_total'];
                                    $percent = ($total_expense > 0) ? ($amount / $total_expense) * 100 : 0;
                                    echo '<tr>
                            <td>' . htmlspecialchars($cat) . '</td>
                            <td class="text-end">' . number_format($amount, 2) . '</td>
                            <td class="text-end">' . number_format($percent, 2) . '%</td>
                        </tr>';
                                    $grand_total += $amount;
                                }
                                ?>
                            </tbody>
                            <tfoot class="fw-semibold">
                                <tr>
                                    <td>Total</td>
                                    <td class="text-end">₱<?= number_format($grand_total, 2) ?></td>
                                    <td class="text-end">100%</td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>

            <?php
            // Get total by category
            $inv_query = mysqli_query($db_connection, "
    SELECT pen_type, SUM(count_) AS total_count 
    FROM tblinventory 
    GROUP BY pen_type
");

            // Compute total pigs
            $total_query = mysqli_query($db_connection, "SELECT SUM(count_) AS total_pigs FROM tblinventory");
            $total_row = mysqli_fetch_assoc($total_query);
            $total_pigs = $total_row['total_pigs'];
            ?>

            <!-- Inventory Status -->
            <div class="col-lg-6">
                <div class="bg-white p-4 rounded shadow-sm">
                    <h5 class="fw-semibold text-dark mb-3">Inventory Status</h5>
                    <div class="table-responsive">
                        <table class="table table-sm table-bordered">
                            <thead class="table-light">
                                <tr>
                                    <th>Category</th>
                                    <th class="text-end">Count</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while ($row = mysqli_fetch_assoc($inv_query)) : ?>
                                    <tr>
                                        <td><?= htmlspecialchars($row['pen_type']) ?></td>
                                        <td class="text-end"><?= number_format($row['total_count']) ?></td>
                                    </tr>
                                <?php endwhile; ?>
                            </tbody>
                            <tfoot class="fw-semibold">
                                <tr>
                                    <td>Total Pigs</td>
                                    <td class="text-end"><?= number_format($total_pigs) ?></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>



            <?php
            // Fetch total pigs per pen_type
            $inv_query = mysqli_query($db_connection, "
    SELECT pen_type, SUM(count_) AS total_count 
    FROM tblinventory 
    GROUP BY pen_type
");

            $pen_totals = [];
            while ($row = mysqli_fetch_assoc($inv_query)) {
                $pen_totals[$row['pen_type']] = $row['total_count'];
            }

            // Fetch affected counts grouped by symptom and pen_type
            $symptom_query = mysqli_query($db_connection, "
    SELECT s.symptom, i.pen_type, COUNT(m.monitor_id) AS affected_count
    FROM tblmonitor m
    JOIN tblinventory i ON m.pen_number = i.pen_number
    JOIN tblsymptom s ON m.symptom_id = s.symptom_id
    GROUP BY s.symptom, i.pen_type
");

            // Group results by symptom
            $grouped_data = [];
            while ($row = mysqli_fetch_assoc($symptom_query)) {
                $symptom = $row['symptom'];
                $pen_type = $row['pen_type'];
                $affected = $row['affected_count'];
                $total = $pen_totals[$pen_type] ?? 1;
                $percent = ($affected / $total) * 100;

                $grouped_data[$symptom][] = [
                    'pen_type' => $pen_type,
                    'affected' => $affected,
                    'total' => $total,
                    'percent' => number_format($percent, 1)
                ];
            }
            ?>

            <div class="col-lg-6">
                <div class="bg-white p-4 rounded shadow-sm">
                    <h5 class="fw-semibold text-dark mb-3">Inventory Health Status by Symptom</h5>
                    <div class="table-responsive">
                        <table class="table table-sm table-bordered">
                            <thead class="table-light">
                                <tr>
                                    <th>Symptom</th>
                                    <th>Pen Type</th>
                                    <th class="text-end">Affected</th>
                                    <th class="text-end">Total</th>
                                    <th class="text-end">%</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($grouped_data as $symptom => $rows): ?>
                                    <?php foreach ($rows as $index => $data): ?>
                                        <tr>
                                            <?php if ($index === 0): ?>
                                                <td rowspan="<?= count($rows) ?>" class="align-middle fw-bold"><?= htmlspecialchars($symptom) ?></td>
                                            <?php endif; ?>
                                            <td><?= htmlspecialchars($data['pen_type']) ?></td>
                                            <td class="text-end"><?= $data['affected'] ?></td>
                                            <td class="text-end"><?= $data['total'] ?></td>
                                            <td class="text-end"><?= $data['percent'] ?>%</td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
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