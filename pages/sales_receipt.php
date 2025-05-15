<?php 
include('../includes/init.php');
is_blocked();

if (isset($_GET['sale_id'])) {
    $sale_id = (int) $_GET['sale_id'];

    // Fetch receipt header info
    $query = "SELECT * FROM tblsales WHERE sale_id = $sale_id LIMIT 1";
    $res = mysqli_query($db_connection, $query);
    $header = mysqli_fetch_assoc($res);

    if (!$header) {
        echo "Receipt not found.";
        exit;
    }

    $receipt_id = $header['receipt_id'];
    $customer = GetValue("SELECT name FROM tblcustomer WHERE customerid = " . $header['customerid']);
    $sale_date = date("F j, Y", strtotime($header['sale_date']));

    // Fetch all items under this receipt
    $items_q = mysqli_query($db_connection, "SELECT * FROM tblsales WHERE receipt_id = '" . mysqli_real_escape_string($db_connection, $receipt_id) . "'");
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Sales Receipt</title>
    <style>
        body { font-family: Arial, sans-serif; padding: 20px; }
        .receipt { max-width: 600px; margin: auto; border: 1px solid #ccc; padding: 20px; }
        .receipt h2 { text-align: center; margin-bottom: 20px; }
        .receipt table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        .receipt th, .receipt td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        .receipt th { background-color: #f2f2f2; }
        .text-right { text-align: right; }
        .print-btn { margin-bottom: 20px; text-align: right; }
    </style>
</head>
<body>

<div class="receipt">
    <div class="print-btn">
        <button onclick="window.print()">🖨️ Print</button>
    </div>

    <h2>Sales Receipt</h2>

    <p><strong>Receipt ID:</strong> <?= htmlspecialchars($receipt_id) ?></p>
    <p><strong>Date:</strong> <?= htmlspecialchars($sale_date) ?></p>
    <p><strong>Customer:</strong> <?= !empty($customer) ? htmlspecialchars($customer) : 'Guest' ?></p>

    <table>
        <thead>
            <tr>
                <th>Pen</th>
                <th>Qty</th>
                <th>Unit Price</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            $grand_total = 0;
            while ($item = mysqli_fetch_assoc($items_q)) {
                $pen = GetValue("SELECT pen_number FROM tblinventory WHERE inventory_id = " . $item['inventory_id']);
                $total = $item['qty'] * $item['price'];
                $grand_total += $total;

                echo '<tr>
                        <td>' . htmlspecialchars($pen) . '</td>
                        <td>' . (int)$item['qty'] . '</td>
                        <td>₱' . number_format($item['price'], 2) . '</td>
                        <td>₱' . number_format($total, 2) . '</td>
                    </tr>';
            }
            ?>
        </tbody>
        <tfoot>
            <tr>
                <th colspan="3" class="text-right">Grand Total</th>
                <th>₱<?= number_format($grand_total, 2) ?></th>
            </tr>
        </tfoot>
    </table>

    <p style="text-align: center;">Thank you for your purchase!</p>
</div>

</body>
</html>
