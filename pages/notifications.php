<?php include('../includes/init.php'); ?>
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 fw-bold text-dark">Notifications</h1>
        <button class="btn btn-sm text-white" style="background-color: #ec4899;">
            <i class="fas fa-broom me-2"></i>Clear All (Placeholder)
        </button>
    </div>

    <!-- Warning Notification -->
    <div class="alert alert-warning d-flex align-items-start border-start border-5 border-warning-subtle rounded shadow-sm mb-3">
        <div class="me-3 pt-1"><i class="fas fa-exclamation-triangle fs-5 text-warning"></i></div>
        <div class="flex-grow-1">
            <div class="fw-bold">Low Feed Stock</div>
            <div>Feed quantity for Fatteners is below 50kg.</div>
            <div class="small text-muted">2023-11-15 08:30 AM</div>
        </div>
        <button type="button" class="btn-close ms-3" aria-label="Close"></button>
    </div>

    <!-- Info Notification -->
    <div class="alert alert-primary d-flex align-items-start border-start border-5 border-primary-subtle rounded shadow-sm mb-3">
        <div class="me-3 pt-1"><i class="fas fa-info-circle fs-5 text-primary"></i></div>
        <div class="flex-grow-1">
            <div class="fw-bold">Vaccination Reminder</div>
            <div>Sow Pen S2 is due for vaccination next week.</div>
            <div class="small text-muted">2023-11-14 10:00 AM</div>
        </div>
        <button type="button" class="btn-close ms-3" aria-label="Close"></button>
    </div>

    <!-- Success Notification -->
    <div class="alert alert-success d-flex align-items-start border-start border-5 border-success-subtle rounded shadow-sm mb-3">
        <div class="me-3 pt-1"><i class="fas fa-check-circle fs-5 text-success"></i></div>
        <div class="flex-grow-1">
            <div class="fw-bold">New Birth Recorded</div>
            <div>A new birth record was added for Sow Pen S1 (10 alive).</div>
            <div class="small text-muted">2023-11-13 02:15 PM</div>
        </div>
        <button type="button" class="btn-close ms-3" aria-label="Close"></button>
    </div>

    <!-- Info Notification -->
    <div class="alert alert-primary d-flex align-items-start border-start border-5 border-primary-subtle rounded shadow-sm mb-3">
        <div class="me-3 pt-1"><i class="fas fa-info-circle fs-5 text-primary"></i></div>
        <div class="flex-grow-1">
            <div class="fw-bold">System Update</div>
            <div>Welcome to the updated Piggery Farm Management System!</div>
            <div class="small text-muted">2023-11-10 09:00 AM</div>
        </div>
        <button type="button" class="btn-close ms-3" aria-label="Close"></button>
    </div>
</div>