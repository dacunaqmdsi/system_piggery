<?php include('../includes/init.php');
is_blocked(); ?>

<?php
if (isset($_POST['add_inventory'])) {
    $pen_number = trim($_POST['pen_number']);
    $pen_type = trim($_POST['pen_type']);
    $mothers_pen = isset($_POST['mothers_pen']) ? trim($_POST['mothers_pen']) : null;
    $count_ = intval($_POST['count_']);

    // Basic validation
    if (empty($pen_number) || empty($pen_type) || $count_ < 1) {
        echo "Invalid input data.";
        exit;
    }

    // Check if pen_number already exists
    $checkStmt = $db_connection->prepare("SELECT COUNT(*) FROM tblinventory WHERE pen_number = ?");
    $checkStmt->bind_param("s", $pen_number);
    $checkStmt->execute();
    $checkStmt->bind_result($existing);
    $checkStmt->fetch();
    $checkStmt->close();

    if ($existing > 0) {
        echo "<div class='alert alert-warning'>Pen number already exists. Please use a unique pen number.</div>";
        exit;
    }

    // Insert new pen
    $stmt = $db_connection->prepare("
        INSERT INTO tblinventory (pen_number, pen_type, mothers_pen, count_)
        VALUES (?, ?, ?, ?)
    ");

    if ($stmt) {
        $stmt->bind_param("sssi", $pen_number, $pen_type, $mothers_pen, $count_);
        if ($stmt->execute()) {
            echo "<div class='alert alert-success'>Inventory successfully added.</div>";
        } else {
            echo "<div class='alert alert-danger'>Failed to add inventory. Please try again.</div>";
        }
        $stmt->close();
    } else {
        echo "<div class='alert alert-danger'>Database error. Please contact the administrator.</div>";
    }
}
if (isset($_POST['add_birth'])) {
    $mothers_pen_id = intval($_POST['mothers_pen_id']);
    $dob = trim($_POST['dob']);
    $total_piglets = intval($_POST['total_piglets']);
    $deaths = intval($_POST['deaths']);
    $alive = intval($_POST['alive']);

    if (empty($mothers_pen_id) || empty($dob) || $total_piglets < 1) {
        echo "<div class='alert alert-warning'>Missing or invalid input values.</div>";
        exit;
    }

    // Prepare insert statement for birth record
    $stmt = $db_connection->prepare("
        INSERT INTO tblbirth (pen_number, dob, total_piglets, deaths, alive)
        VALUES (?, ?, ?, ?, ?)
    ");

    if ($stmt) {
        $stmt->bind_param("isiii", $mothers_pen_id, $dob, $total_piglets, $deaths, $alive);

        if ($stmt->execute()) {
            // Generate piglet pen_number by prefixing mother's pen number
            $piglet_pen_number = "PIGLET-" . $mothers_pen_id;

            // Check if an inventory record exists for this mother's pen
            $checkInventory = $db_connection->prepare("SELECT count_ FROM tblinventory WHERE mothers_pen = ?");
            $checkInventory->bind_param("i", $mothers_pen_id);
            $checkInventory->execute();
            $checkInventory->store_result();

            if ($checkInventory->num_rows > 0) {
                // Entry exists, update the count_
                $updateStmt = $db_connection->prepare("UPDATE tblinventory SET count_ = count_ + ? WHERE mothers_pen = ?");
                $updateStmt->bind_param("ii", $alive, $mothers_pen_id);
                $updateResult = $updateStmt->execute();
                $updateStmt->close();

                if ($updateResult) {
                    echo "<div class='alert alert-success'>Birth record successfully added and inventory updated.</div>";
                } else {
                    echo "<div class='alert alert-warning'>Birth record added, but failed to update inventory.</div>";
                }
            } else {
                // No entry exists, insert a new one with piglet pen_number and pen_type 'Piglet'
                $insertStmt = $db_connection->prepare("INSERT INTO tblinventory (mothers_pen, pen_number, pen_type, count_) VALUES (?, ?, ?, ?)");
                $pen_type = "Piglet";
                $insertStmt->bind_param("issi", $mothers_pen_id, $piglet_pen_number, $pen_type, $alive);
                $insertResult = $insertStmt->execute();
                $insertStmt->close();

                if ($insertResult) {
                    echo "<div class='alert alert-success'>Birth record successfully added and new inventory entry created.</div>";
                } else {
                    echo "<div class='alert alert-warning'>Birth record added, but failed to create inventory entry.</div>";
                }
            }

            $checkInventory->close();
        } else {
            echo "<div class='alert alert-danger'>Failed to add birth record. Try again.</div>";
        }

        $stmt->close();
    } else {
        echo "<div class='alert alert-danger'>Database error occurred while preparing statement.</div>";
    }
}


if (isset($_POST['delete_birth'])) {
    $birth_id = intval($_POST['id']); // Sanitize input

    // First, fetch the alive count and pen_number from the birth record
    $result = mysqli_query($db_connection, "SELECT alive, pen_number FROM tblbirth WHERE birth_id = $birth_id");

    if ($row = mysqli_fetch_assoc($result)) {
        $alive = intval($row['alive']);
        $pen_number = intval($row['pen_number']);

        // Delete the birth record
        $delete_query = "DELETE FROM tblbirth WHERE birth_id = $birth_id";
        if (mysqli_query($db_connection, $delete_query)) {
            // Update the inventory count
            $update_query = "UPDATE tblinventory SET count_ = count_ - $alive WHERE mothers_pen = $pen_number";
            mysqli_query($db_connection, $update_query);

            echo "<div class='alert alert-success'>Birth record deleted and inventory updated.</div>";
        } else {
            echo "<div class='alert alert-danger'>Failed to delete birth record.</div>";
        }
    } else {
        echo "<div class='alert alert-warning'>Birth record not found.</div>";
    }
}

?>


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
    <?php



    ?>

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
                            <?php
                            $rs = mysqli_query($db_connection, 'select inventory_id, pen_number, count_, pen_type from tblinventory where pen_type=\'Sow\' ');
                            while ($rw = mysqli_fetch_array($rs)) {
                                echo '<tr>
                                    <td>' . $rw['pen_number'] . '</td>
                                    <td>' . $rw['count_'] . '</td>
                                </tr>';
                            }
                            ?>
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
                            <?php
                            $rs = mysqli_query($db_connection, 'select inventory_id, pen_number, count_, pen_type from tblinventory where pen_type=\'Boar\' ');
                            while ($rw = mysqli_fetch_array($rs)) {
                                echo '<tr>
                                    <td>' . $rw['pen_number'] . '</td>
                                    <td>' . $rw['count_'] . '</td>
                                </tr>';
                            }
                            ?>
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
                            <?php
                            $rs = mysqli_query($db_connection, 'select inventory_id, pen_number, count_, pen_type from tblinventory where pen_type=\'Fattener\' ');
                            while ($rw = mysqli_fetch_array($rs)) {
                                echo '<tr>
                                    <td>' . $rw['pen_number'] . '</td>
                                    <td>' . $rw['count_'] . '</td>
                                </tr>';
                            }
                            ?>
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
                            <?php
                            $rs = mysqli_query($db_connection, "
                                SELECT 
                                    piglet.pen_number AS piglet_pen,
                                    mother.pen_number AS mother_pen,
                                    piglet.count_
                                FROM tblinventory AS piglet
                                LEFT JOIN tblinventory AS mother ON piglet.mothers_pen = mother.inventory_id
                                WHERE piglet.pen_type = 'Piglet'
                            ");

                            while ($rw = mysqli_fetch_array($rs)) {
                                echo '<tr>
                                    <td>' . htmlspecialchars($rw['piglet_pen']) . '</td>
                                    <td>' . htmlspecialchars($rw['mother_pen']) . '</td>
                                    <td>' . htmlspecialchars($rw['count_']) . '</td>
                                </tr>';
                            }
                            ?>

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
                    <?php
                    // Fetch and display birthing records
                    $query = "SELECT b.birth_id, b.dob, b.total_piglets, b.deaths, b.alive, i.pen_number 
          FROM tblbirth b 
          LEFT JOIN tblinventory i ON b.pen_number = i.inventory_id 
          ORDER BY b.dob DESC";

                    $result = $db_connection->query($query);

                    if ($result->num_rows > 0):
                        while ($row = $result->fetch_assoc()):
                    ?>
                            <tr>
                                <td><?= htmlspecialchars($row['dob']) ?></td>
                                <td><?= htmlspecialchars($row['pen_number']) ?></td>
                                <td><?= intval($row['total_piglets']) ?></td>
                                <td><?= intval($row['deaths']) ?></td>
                                <td><?= intval($row['alive']) ?></td>
                                <td>
                                  <button class="btn btn-sm text-white" 
        onclick="deleteBirth(<?= $row['birth_id'] ?>)" 
        style="background-color: #e546ad;" 
        title="Delete Birth Record">
    <i class="fas fa-trash-alt"></i>
</button>

                                </td>
                            </tr>
                        <?php
                        endwhile;
                    else:
                        ?>
                        <tr>
                            <td colspan="6" class="text-center text-muted py-4">No birthing records found.</td>
                        </tr>
                    <?php endif; ?>
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
                    <div class="mb-3">
                        <label for="pen-number" class="form-label text-dark">Pen Number</label>
                        <input id="pen_number" type="text" class="form-control border-2" placeholder="e.g., S1, B2, F10, P1-Litter" required>
                        <div class="form-text">Use a unique identifier for the pen.</div>
                    </div>

                    <div class="mb-3">
                        <label for="pen-type" class="form-label text-dark">Pen Type</label>
                        <select id="pen_type" class="form-select border-2" required onchange="togglePigletOptions()">
                            <option value="" disabled selected>Select pen type</option>
                            <option value="Sow">Sow</option>
                            <option value="Boar">Boar</option>
                            <option value="Fattener">Fattener</option>
                            <option value="Piglet">Piglet</option>
                        </select>
                    </div>

                    <div id="piglet-options" style="display: none;">
                        <div class="mb-3">
                            <label for="mother-sow-pen" class="form-label text-dark">Mother Sow Pen</label>
                            <select id="mothers_pen" class="form-select">
                                <option value="" disabled selected>Select mother sow's pen</option>
                                <?php
                                $res = mysqli_query($db_connection, "SELECT inventory_id, pen_number FROM tblinventory WHERE pen_type='Sow'");
                                while ($row = mysqli_fetch_assoc($res)) {
                                    echo '<option value="' . htmlspecialchars($row['inventory_id']) . '">' . htmlspecialchars($row['pen_number']) . '</option>';
                                }
                                ?>
                            </select>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="animal-count" class="form-label text-dark">Count of Animals</label>
                        <input id="count_" type="number" class="form-control" value="1" min="0">
                    </div>

                    <p id="add-pen-modal-error" class="text-danger small d-none mt-3"></p>
                    <div class="d-flex justify-content-end gap-2 pt-3">
                        <button type="button" class="btn btn-light text-dark" data-bs-dismiss="modal">Cancel</button>
                        <button onclick="pen_inventory()" class="btn text-white" style="background-color: #ec4899;">Add Pen</button>
                    </div>
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
                    <div class="mb-3">
                        <label for="birth-mother-sow-pen" class="form-label text-dark">Mother Sow Pen</label>
                        <select id="mothers_pen_id" class="form-select">
                            <option value="" disabled selected>Select mother sow's pen</option>
                            <?php
                            $res = mysqli_query($db_connection, "SELECT inventory_id, pen_number FROM tblinventory WHERE pen_type='Sow'");
                            while ($row = mysqli_fetch_assoc($res)) {
                                echo '<option value="' . htmlspecialchars($row['inventory_id']) . '">' . htmlspecialchars($row['pen_number']) . '</option>';
                            }
                            ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="birth-date" class="form-label text-dark">Date of Birth</label>
                        <input id="dob" type="date" class="form-control border-2" required>
                    </div>
                    <div class="mb-3">
                        <label for="birth-total-born" class="form-label text-dark">Total Piglets Born</label>
                        <!-- <input id="total_piglets" type="number" min="0" class="form-control border-2" required> -->
                        <input id="total_piglets" type="number" min="0" class="form-control border-2" required oninput="calculateAlive()">

                    </div>
                    <div class="mb-3">
                        <label for="birth-deaths" class="form-label text-dark">Number of Deaths</label>
                        <!-- <input id="deaths" type="number" value="0" min="0" class="form-control border-2" required> -->
                        <input id="deaths" type="number" value="0" min="0" class="form-control border-2" required oninput="calculateAlive()">
                    </div>
                    <div class="mb-3">
                        <label for="birth-alive" class="form-label text-dark">Number Alive</label>
                        <input id="alive" type="number" class="form-control border-2" readonly placeholder="Calculated automatically">
                    </div>

                    <p id="record-birth-modal-error" class="text-danger small d-none mt-3"></p>
                    <div class="d-flex justify-content-end gap-2 pt-3">
                        <button type="button" class="btn btn-light text-dark" data-bs-dismiss="modal">Cancel</button>
                        <button onclick="add_birth();" class="btn text-white" style="background-color: #ec4899;">Record Birth</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>