<?php include('../includes/init.php');
is_blocked(); 

if(isset($_GET['audit_id_del'])){
    mysqli_query($db_connection, 'update tblaudittrail set is_view=1 where audit_id='.$_GET['audit_id_del']);
}
if(isset($_GET['audit_id_deleeeeeeee'])){
    mysqli_query($db_connection, 'update tblaudittrail set is_view=1');
}

?>
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 fw-bold text-dark">Notifications</h1>
        <button onclick="update_notifX()" class="btn btn-sm text-white" style="background-color: #ec4899;">
            <i class="fas fa-broom me-2"></i>Clear All (Placeholder)
        </button>
    </div>

    <?php
    // Assuming the query has been executed
    $rs = mysqli_query($db_connection, "
       SELECT 
    a.audit_id, 
    a.activity, 
    a.description, 
    a.accountid, 
    a.created_at, 
    a.is_view, 
    b.username, 
    b.account_type
FROM 
    tblaudittrail a
INNER JOIN 
    tblaccounts b 
ON 
    a.accountid = b.accountid 
WHERE 
    a.is_view = 0;

    ");

    // Alternate background color flag
    $alternate = false;

    if (mysqli_num_rows($rs) > 0) {
        while ($row = mysqli_fetch_assoc($rs)) {
            // Toggle the $alternate flag to change colors
            $alert_class = $alternate ? 'alert-light' : 'alert-info'; // Alternating colors
            $alternate = !$alternate; // Flip the flag for next iteration
    ?>

            <!-- Notification -->
            <div class="alert <?php echo $alert_class; ?> d-flex align-items-start border-start border-5 border-primary-subtle rounded shadow-sm mb-3">
                <div class="me-3 pt-1"><i class="fas fa-info-circle fs-5 text-primary"></i></div>
                <div class="flex-grow-1">
                    <div class="fw-bold"><?php echo htmlspecialchars($row['activity']); ?></div>
                    <div><?php echo htmlspecialchars($row['description']); ?></div>
                    <div class="small text-muted"><?php echo date('Y-m-d H:i A', strtotime($row['created_at'])); ?></div>
                </div>
                <button type="button" onclick="update_notif(<?php echo $row['audit_id']; ?>);" class="btn-close ms-3" aria-label="Close"></button>
            </div>

    <?php
        }
    } else {
        echo "No records found.";
    }
    ?>
</div>