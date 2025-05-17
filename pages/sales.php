<?php include('../includes/init.php');
is_blocked(); ?>
<?php


if (isset($_POST['add_sales'])) {
    $sale_date = $_POST['sale_date'];
    $customerid = $_POST['customerid'];
    $inventory_ids = $_POST['inventory_id'];
    $qtys = $_POST['qty'];
    $prices = $_POST['price'];

    // Generate receipt_id: R0001 format
    $result = mysqli_query($db_connection, "SELECT MAX(sale_id) AS max_id FROM tblsales");
    $row = mysqli_fetch_assoc($result);
    $next_id = (int)($row['max_id'] ?? 0) + 1;
    $receipt_id = 'R' . str_pad($next_id, 4, '0', STR_PAD_LEFT);

    // Insert each sale item
    for ($i = 0; $i < count($inventory_ids); $i++) {
        $inv = mysqli_real_escape_string($db_connection, $inventory_ids[$i]);
        $qty = (int)$qtys[$i];
        $price = (float)$prices[$i];

        $insert = mysqli_query($db_connection, "
            INSERT INTO tblsales (receipt_id, sale_date, customerid, inventory_id, qty, price) 
            VALUES ('$receipt_id', '$sale_date', '$customerid', '$inv', $qty, $price)
        ");
    }

    echo "<div class='alert alert-success'>Sale successfully recorded. Receipt #: <strong>$receipt_id</strong></div>";
}

?>

<div class="container-fluid ">
    <h1 class="h3 fw-bold text-dark mb-4">Record New Sale</h1>
    <div class="bg-white p-4 rounded shadow-sm mb-5">
        <div class="row mb-4">
            <div class="col-md-6 mb-3">
                <label for="sale-date" class="form-label text-dark">Date</label>
                <input type="date" id="sale_date" class="form-control border-2" required style="border-color: #e5e7eb;">
            </div>
            <div class="col-md-6 mb-3">
                <label for="customer-name" class="form-label text-dark">Customer Name</label>
                <select id="customerid" class="form-select border-2" style="border-color: #e5e7eb;" required>
                    <option value="" disabled selected>Select Customer</option>
                    <option value="_guest_">Guest/Walk-in Customer</option>
                    <?php
                    $res = mysqli_query($db_connection, "SELECT customerid, name FROM tblcustomer ORDER BY name ASC");
                    while ($row = mysqli_fetch_assoc($res)) {
                        echo '<option value="' . $row['customerid'] . '">' . htmlspecialchars($row['name']) . '</option>';
                    }
                    ?>
                </select>
            </div>
        </div>

        <!-- Sale Items Header -->
        <h5 class="fw-semibold text-secondary border-top pt-4 mb-3">Sale Items</h5>

        <!-- Sale Items Container -->
        <div id="sale-items-container" class="mb-3">
            <div class="row g-3 border-bottom pb-3 sale-item-row">
                <div class="col-md-3">
                    <label class="form-label text-dark">Source Pen</label>
                    <select name="inventory_id[]" class="form-select border-2 item-pen-select" style="border-color: #e5e7eb;" required>
                        <option value="" disabled selected>-- Select Pen --</option>
                        <?php
                        $res = mysqli_query($db_connection, "
                SELECT 
                    child.inventory_id,
                    child.pen_number,
                    child.pen_type,
                    mother.pen_number AS mother_pen_number
                FROM 
                    tblinventory AS child
                LEFT JOIN 
                    tblinventory AS mother ON child.mothers_pen = mother.inventory_id
                ORDER BY 
                    child.pen_number ASC
            ");

                        while ($row = mysqli_fetch_assoc($res)) {
                            $penLabel = ($row['pen_type'] === 'Piglet')
                                ? 'Piglet Pen (Mother Pen: ' . htmlspecialchars($row['mother_pen_number'] ?? 'N/A') . ')'
                                : htmlspecialchars($row['pen_number'] . ' (' . $row['pen_type'] . ')');

                            echo '<option value="' . $row['inventory_id'] . '">' . $penLabel . '</option>';
                        }
                        ?>
                    </select>
                </div>
                <div class="col-md-1">
                    <label class="form-label text-dark">Qty</label>
                    <input name="qty[]" type="number" min="1" step="1" value="1" class="form-control border-2 item-quantity" required style="border-color: #e5e7eb;">
                </div>
                <div class="col-md-2">
                    <label class="form-label text-dark">Unit Price</label>
                    <input name="price[]" type="number" min="0" step="0.01" value="0.00" class="form-control border-2 item-price" required style="border-color: #e5e7eb;">
                </div>
                <div class="col-auto d-flex align-items-end">
                    <button type="button" onclick="removeSaleItem(this)" class="btn btn-danger px-3"><i class="fas fa-trash-alt"></i></button>
                </div>
            </div>

        </div>
        <div class="text-end border-top pt-3">
            <h5 class="fw-bold text-dark">Grand Total: <span id="grand-total">₱0.00</span></h5>
        </div>
        <button type="button" onclick="addSaleItem()" class="btn btn-outline-secondary btn-sm mb-3">
            <i class="fas fa-plus me-1"></i> Add Item
        </button>

        <div class="text-end mt-4">
            <!-- Preview Button -->
<button onclick="preview()" class="btn text-white px-4 py-2 fw-semibold"
    style="background: linear-gradient(135deg, #e546ad 0%, #e546ad 100%);">
    <i class="fas fa-eye me-2"></i>Preview
</button>

<!-- Record Sale Button -->
<button onclick="process_sale()" class="btn text-white px-4 py-2 fw-semibold"
    style="background: linear-gradient(135deg, #e546ad 0%, #e546ad 100%);">
    <i class="fas fa-cash-register me-2"></i>Record Sale
</button>

        </div>
    </div>

    <div>
        <h4 class="fw-bold text-dark mb-3">Sales History</h4>
        <div class="mb-3">
            <input type="text" id="sales-search-input" placeholder="Search by Receipt ID, Date, Name, or Amount..." oninput="filterSalesHistory()" class="form-control w-100" style="max-width: 400px;">
        </div>

        <div class="bg-white p-3 rounded shadow-sm table-responsive">
            <table class="table table-bordered table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th>Receipt ID</th>
                        <th>Date</th>
                        <th>Customer</th>
                        <th>Total Amount</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody id="sales-history-body">
                    <?php
                    $q = "
                        SELECT 
                            sale_id,
                            receipt_id, 
                            sale_date, 
                            customerid, 
                            SUM(qty * price) AS total_amount
                        FROM 
                            tblsales 
                        GROUP BY 
                            receipt_id 
                        ORDER BY 
                            MAX(sale_id) DESC
                    ";

                    $rs = mysqli_query($db_connection, $q);
                    while ($rw = mysqli_fetch_array($rs)) {
                        $customer = GetValue('SELECT name FROM tblcustomer WHERE customerid = ' . $rw['customerid']);
                        echo '<tr>
        <td>' . $rw['receipt_id'] . '</td>
        <td>' . date("F j, Y", strtotime($rw['sale_date'])) . '</td>
        <td>' . (!empty($customer) ? $customer : 'Guest') . '</td>
        <td style="text-align:right;">₱' . number_format($rw['total_amount'], 2) . '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
        <td>
            <a 
                href="javascript:void(0);" 
                onclick="openCustom(\'pages/sales_receipt?sale_id=' . $rw['sale_id'] . '\',700,700);" 
                class="btn btn-sm"
                style="color: #e546ad;" 
                title="Print Receipt"
            >
                <i class="fas fa-print"></i>
            </a>
        </td>
    </tr>';
                    }
                    ?>

                </tbody>
            </table>
        </div>
    </div>
</div>