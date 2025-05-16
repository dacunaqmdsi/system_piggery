<?php
include('../includes/init.php');
is_blocked();

if (!isset($_GET['reportType'])) {
    echo "No report type selected.";
    exit;
}

$reportType = $_GET['reportType'];
$dateCondition = "";

// Generate dynamic date condition
switch ($reportType) {
    case 'monthly':
        // Current month
        $dateCondition = "MONTH(sale_date) = MONTH(CURDATE()) AND YEAR(sale_date) = YEAR(CURDATE())";
        break;
    case 'quarterly':
        // Current quarter
        $dateCondition = "QUARTER(sale_date) = QUARTER(CURDATE()) AND YEAR(sale_date) = YEAR(CURDATE())";
        break;
    case 'yearly':
        // Current year
        $dateCondition = "YEAR(sale_date) = YEAR(CURDATE())";
        break;
    default:
        echo "Invalid report type.";
        exit;
}

$query = "
    SELECT s.sale_id, s.sale_date, c.name AS customer, i.pen_number, s.qty, s.price, (s.qty * s.price) AS total
    FROM tblsales s
    LEFT JOIN tblcustomer c ON c.customerid = s.customerid
    LEFT JOIN tblinventory i ON i.inventory_id = s.inventory_id
    WHERE s.is_void = 0 AND $dateCondition
    ORDER BY s.sale_date DESC
";

$res = mysqli_query($db_connection, $query);

$grand_total = 0;
?>

<!DOCTYPE html>
<html>

<head>
    <title><?= ucfirst($reportType) ?> Sales Report</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            padding: 20px;
        }

        .receipt {
            max-width: 900px;
            margin: auto;
            border: 1px solid #ccc;
            padding: 20px;
        }

        .receipt h2 {
            text-align: center;
            margin-bottom: 20px;
        }

        .receipt table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        .receipt th,
        .receipt td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        .receipt th {
            background-color: #f2f2f2;
        }

        .text-right {
            text-align: right;
        }

        .print-btn {
            margin-bottom: 20px;
            text-align: right;
        }
    </style>
</head>

<body>

    <div class="receipt">
        <div class="print-btn">
            <button onclick="window.print()">🖨️ Print</button>
        </div>

        <h2><?= ucfirst($reportType) ?> Sales Report</h2>

        <table>
            <thead>
                <tr>
                    <th>Sale ID</th>
                    <th>Date</th>
                    <th>Customer</th>
                    <th>Pen</th>
                    <th>Qty</th>
                    <th>Unit Price</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if (mysqli_num_rows($res) > 0) {
                    while ($row = mysqli_fetch_assoc($res)) {
                        $grand_total += $row['total'];
                        echo "<tr>
                            <td>" . htmlspecialchars($row['sale_id']) . "</td>
                            <td>" . date("F j, Y", strtotime($row['sale_date'])) . "</td>
                            <td>" . htmlspecialchars($row['customer'] ?? 'Guest') . "</td>
                            <td>" . htmlspecialchars($row['pen_number']) . "</td>
                            <td>" . (int)$row['qty'] . "</td>
                            <td>₱" . number_format($row['price'], 2) . "</td>
                            <td>₱" . number_format($row['total'], 2) . "</td>
                        </tr>";
                    }
                } else {
                    echo "<tr><td colspan='7'>No sales found for this report type.</td></tr>";
                }
                ?>
            </tbody>
            <tfoot>
                <tr>
                    <th colspan="6" class="text-right">Grand Total</th>
                    <th>₱<?= number_format($grand_total, 2) ?></th>
                </tr>
            </tfoot>
        </table>

        <p style="text-align: center;">Generated on <?= date("F j, Y g:i A") ?></p>
    </div>

</body>

</html>