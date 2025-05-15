<?php include('../includes/init.php'); is_blocked(); ?>
<?php
if (isset($_POST['add_log'])) {
    $monitor_date = mysqli_real_escape_string($db_connection, $_POST['monitor_date']);
    $pen_number = mysqli_real_escape_string($db_connection, $_POST['pen_number']);
    $symptom_id = mysqli_real_escape_string($db_connection, $_POST['symptom_id']);
    $description = mysqli_real_escape_string($db_connection, $_POST['description']);

    $query = "
        INSERT INTO tblmonitor (monitor_date, pen_number, symptom_id, description)
        VALUES ('$monitor_date', '$pen_number', '$symptom_id', '$description')
    ";

    if (mysqli_query($db_connection, $query)) {
        echo "<div class='alert alert-success'>Monitoring log successfully added.</div>";
    } else {
        echo "<div class='alert alert-danger'>Error: " . mysqli_error($db_connection) . "</div>";
    }
}
?>

<div class="container-fluid">
    <h1 class="mb-4 fw-bold text-dark fs-2">Daily Monitoring Log</h1>

    <!-- Log Entry Form -->
    <div class="card p-4 shadow-sm mb-5">
        <h2 class="h5 fw-semibold mb-3 text-dark">Log Health Observation</h2>
        <div class="row g-3 mb-3">
            <div class="col-md-6">
                <label for="log-date" class="form-label fw-medium">Date</label>
                <input type="date" id="monitor_date" class="form-control" required />
            </div>
            <div class="col-md-6">
                <label for="log-identifier" class="form-label fw-medium">Pen ID / Pig Tag</label>
                <input type="text" id="pen_number" class="form-control" placeholder="e.g., Pen F5, Pig #102" required />
            </div>
        </div>
        <div class="mb-3">
            <label for="log-symptoms" class="form-label fw-medium">Symptoms / Observations</label>
            <select id="symptom_id" class="form-select border-2" style="border-color: #e5e7eb;" required>
                <option value="" disabled selected>Select symptoms / observations</option>
                <?php
                $res = mysqli_query($db_connection, "SELECT symptom_id, symptom, suggested_action FROM tblsymptom ");
                while ($row = mysqli_fetch_assoc($res)) {
                    echo '<option value="' . $row['symptom_id'] . '">' . htmlspecialchars($row['symptom']) . '</option>';
                }
                ?>
            </select>
            <!-- <textarea class="form-control" id="description" rows="3" placeholder="Describe symptoms like coughing, lameness, not eating, etc." required></textarea> -->
        </div>
        <div class="mb-3">
            <label for="log-symptoms" class="form-label fw-medium">Description</label>
            <textarea class="form-control" id="description" rows="3" placeholder="Describe symptoms like coughing, lameness, not eating, etc." required></textarea>
        </div>
        <div class="text-end">
            <button onclick="monitor_it();" class="btn btn-primary">
                <i class="fas fa-notes-medical me-2"></i>Log Observation & Suggest Action
            </button>
        </div>
    </div>

    <!-- Log History -->
    <?php
    $query = "
    SELECT a.monitor_id, a.pen_number, a.symptom_id, a.description,
           b.symptom, b.suggested_action, a.monitor_date
    FROM tblmonitor a
    JOIN tblsymptom b ON a.symptom_id = b.symptom_id
    ORDER BY a.monitor_date DESC
";

    $result = mysqli_query($db_connection, $query);
    ?>

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
                        <?php if (mysqli_num_rows($result) > 0): ?>
                            <?php while ($row = mysqli_fetch_assoc($result)): ?>
                                <tr>
                                    <td><?= htmlspecialchars(date('Y-m-d', strtotime($row['monitor_date']))) ?></td>
                                    <td><?= htmlspecialchars($row['pen_number']) ?></td>
                                    <td>
                                        <strong><?= htmlspecialchars($row['symptom']) ?></strong><br>
                                        <small class="text-muted"><?= nl2br(htmlspecialchars($row['description'])) ?></small>
                                    </td>
                                    <td><?= htmlspecialchars($row['suggested_action']) ?></td>
                                </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="4" class="text-center text-muted py-3">No health log entries found.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>