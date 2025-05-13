<?php
if (session_id() == '') {
    session_start();
}
if (isset($_SESSION['accountid'])) {
    if (file_exists('includes/dbconfig.php')) {
        include_once('includes/dbconfig.php');
    }
} else {
    header('location: ./');
    exit(0);
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Dimayacyac's Piggery Farm Management System</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" />

    <style>
        html,
        body {
            height: 100%;
            margin: 0;
        }

        #app-container {
            display: flex;
            height: 100vh;
            overflow: hidden;
        }

        .sidebar {
            background: linear-gradient(180deg, #e546ad 0%, #e546ad 100%);
            color: #e0e7ff;
            width: 250px;
            flex-shrink: 0;
            display: flex;
            flex-direction: column;
            padding: 1rem;
            overflow-y: auto;
        }

        .sidebar a {
            transition: all 0.2s ease;
            border-left: 4px solid transparent;
            padding: 0.5rem 1rem;
            display: flex;
            align-items: center;
            color: #e0e7ff;
            text-decoration: none;
        }

        .sidebar a:hover {
            background-color: rgba(255, 255, 255, 0.1);
            border-left-color: #e546ad;
            color: white;
        }

        .sidebar a.active {
            background-color: rgba(255, 255, 255, 0.2);
            font-weight: 600;
            border-left-color: white;
        }

        .sidebar-header {
            border-bottom: 1px solid rgba(255, 255, 255, 0.2);
        }

        .user-info {
            border-top: 1px solid rgba(255, 255, 255, 0.2);
            margin-top: auto;
        }

        .logout-btn {
            background-color: rgba(255, 255, 255, 0.1);
            transition: background-color 0.2s ease;
        }

        .logout-btn:hover {
            background-color: rgba(255, 255, 255, 0.2);
        }

        main {
            flex-grow: 1;
            overflow-y: auto;
            padding: 1rem;
            background-color: #f8f9fa;
        }

        .auth-container {
            background: linear-gradient(135deg, #fcf6fc 0%, #e9f0f7 100%);
        }

        .btn-primary {
            background: linear-gradient(135deg, #e546b5 0%, #e546b5 100%);
            border: none;
        }

        .btn-primary:hover {
            background: linear-gradient(135deg, #e546b5 0%, #e546b5 100%);
            transform: translateY(-1px);
        }

        .btn-secondary {
            background: #f3f4f6;
            color: #e54698;
        }

        .btn-secondary:hover {
            background: #e5e7eb;
        }

        .input-field,
        .select-field {
            border: 2px solid #e5e7eb;
        }

        .input-field:focus,
        .select-field:focus {
            border-color: #e546b5;
            box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.1);
        }

        .form-control:focus,
        .form-select:focus {
            border-color: #ec4899 !important;
            box-shadow: 0 0 0 0.2rem rgba(236, 72, 153, 0.25);
        }
    </style>

    <script>
        function param(w, h) {
            var width = w;
            var height = h;
            var left = (screen.width - width) / 2;
            var top = (screen.height - height) / 2;
            var params = 'width=' + width + ', height=' + height;
            params += ', top=' + top + ', left=' + left;
            params += ', directories=no, location=no, resizable=no, status=no, toolbar=no';
            return params;
        }

        function openWin(url) {
            window.open(url, 'mywin', param(800, 500)).focus();
        }

        function openCustom(url, w, h) {
            window.open(url, 'mywin', param(w, h)).focus();
        }

        function openCustom2(url, w, h) {
            let newWindow = window.open(url, '_blank', param(w, h));
            if (!newWindow) {
                alert("Popup blocked! Please allow popups for this site.");
            } else {
                newWindow.focus();
            }
        }

        function ajax_fn(url, elementId) {
            const xmlhttp = new XMLHttpRequest();
            xmlhttp.onreadystatechange = function() {
                if (xmlhttp.readyState === 4 && xmlhttp.status === 200) {
                    document.getElementById(elementId).innerHTML = xmlhttp.responseText;
                }
            };
            xmlhttp.open("GET", url, true);
            xmlhttp.send();
        }
    </script>
</head>

<body class="auth-container">
    <div id="app-container">
        <!-- Sidebar -->
        <aside class="sidebar">
            <div class="sidebar-header pb-3 mb-3 text-center">
                <h1 class="h5 text-white fw-bold">Dimayacyac's Piggery Farm MS</h1>
            </div>
            <div class="text-center mb-3">
                <img src="docs/logo.png-removebg-preview.png" alt="Website Logo" class="img-fluid" style="width: 80px;" />
            </div>

            <nav>
                <a href="javascript:void(0);" onclick="location.reload();"><i class="fas fa-tachometer-alt me-2"></i> Dashboard</a>
                <a href="javascript:void(0);" onclick="ajax_fn('pages/inventory','main_content')"><i class="fas fa-boxes me-2"></i> Pig Inventory</a>
                <a href="javascript:void(0);" onclick="ajax_fn('pages/products','main_content')"><i class="fas fa-shopping-basket me-2"></i> Products</a>
                <a href="javascript:void(0);" onclick="ajax_fn('pages/sales','main_content')"><i class="fas fa-dollar-sign me-2"></i> Sales</a>
                <a href="javascript:void(0);" onclick="ajax_fn('pages/expenses','main_content')"><i class="fas fa-receipt me-2"></i> Expenses</a>
                <a href="javascript:void(0);" onclick="ajax_fn('pages/monitoring','main_content')"><i class="fas fa-clipboard-list me-2"></i> Daily Monitoring</a>
                <a href="javascript:void(0);" onclick="ajax_fn('pages/reports','main_content')"><i class="fas fa-chart-bar me-2"></i> Reports</a>
                <a href="javascript:void(0);" onclick="ajax_fn('pages/notifications','main_content')"><i class="fas fa-bell me-2"></i> Notifications</a>
                <a href="javascript:void(0);" onclick="ajax_fn('pages/user_management','main_content')"><i class="fas fa-users-cog me-2"></i> User Management</a>
                <a href="javascript:void(0);" onclick="ajax_fn('pages/customers','main_content')"><i class="fas fa-address-book me-2"></i> Customers</a>
            </nav>

            <div class="user-info pt-3">
                <div class="d-flex align-items-center mb-3">
                    <i class="fas fa-user-circle fa-2x text-white me-2"></i>
                    <div>
                        <p id="user-name" class="mb-0 fw-semibold text-white">User Name</p>
                        <small id="user-role" class="text-light">User Role</small>
                    </div>
                </div>
                <button onclick="window.location.href='pages/logout.php'" class="logout-btn w-100 btn btn-sm text-start text-white">
                    <i class="fas fa-sign-out-alt me-2"></i> Logout
                </button>
            </div>
        </aside>

        <!-- Main Content -->
        <main id="main_content">
            <?php include('pages/dashboard.php'); ?>
        </main>
    </div>

    <!-- Bootstrap JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>