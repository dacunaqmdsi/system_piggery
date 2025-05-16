<?php
include('../includes/init.php');
is_blocked();

// Fetch expenses from the database
$query = "SELECT expenseid, expense_date, categoryid, amount, description, refnum FROM tblexpense";
$result = mysqli_query($db_connection, $query);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Expense Report</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #fdf6fb;
            margin: 0;
            padding: 20px;
        }

        .container {
            max-width: 1000px;
            margin: auto;
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 15px rgba(229, 70, 173, 0.2);
        }

        h2 {
            text-align: center;
            color: #e546ad;
            margin-bottom: 30px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        thead {
            background-color: #e546ad;
            color: white;
        }

        th, td {
            padding: 12px 15px;
            text-align: left;
            border: 1px solid #e7d2df;
        }

        tr:nth-child(even) {
            background-color: #f9edf4;
        }

        tr:hover {
            background-color: #fce4f1;
        }

        .text-center {
            text-align: center;
            color: #888;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Expense Report</h2>

    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Date</th>
                <th>Category ID</th>
                <th>Amount</th>
                <th>Description</th>
                <th>Reference Number</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $count = 1;
            if ($result && mysqli_num_rows($result) > 0) {
                while ($row = mysqli_fetch_assoc($result)) {
                    echo "<tr>";
                    echo "<td>{$count}</td>";
                    echo "<td>{$row['expense_date']}</td>";
                    echo "<td>{$row['categoryid']}</td>";
                    echo "<td>" . number_format($row['amount'], 2) . "</td>";
                    echo "<td>{$row['description']}</td>";
                    echo "<td>{$row['refnum']}</td>";
                    echo "</tr>";
                    $count++;
                }
            } else {
                echo "<tr><td colspan='6' class='text-center'>No expenses found.</td></tr>";
            }
            ?>
        </tbody>
    </table>
</div>

</body>
</html>
