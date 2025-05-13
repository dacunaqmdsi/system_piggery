<?php
if (session_id() == '') {
    session_start();
}

if (!isset($_SESSION['token'])) {
    $_SESSION['token'] = rand(1, 100);
}

$err = '';

if (isset($_POST['signin']) && isset($_SESSION['token']) && $_SESSION['token'] == $_POST['token']) {

    include('includes/dbconfig.php');

    $username = mysqli_real_escape_string($db_connection, $_POST['username']);
    $password = mysqli_real_escape_string($db_connection, $_POST['account_password']);
    // $password = secureData(mysqli_real_escape_string($db_connection, $_POST['accountpassword']));

    $sql = "SELECT * FROM tblaccounts WHERE username='$username' AND account_password='$password' LIMIT 1";
    $rs = mysqli_query($db_connection, $sql);

    if ($rs && mysqli_num_rows($rs) === 1) {
        $user = mysqli_fetch_assoc($rs);
        $_SESSION['accountid'] = $user['accountid'];
        include('home.php');
        exit();
    } else {
        $err = '<span style="color:red; font-size:16px;" class="fw-bold blink" >Invalid Username or Password</span>';
    }
}
if (isset($_SESSION['accountid'])) {
    include_once('includes/dbconfig.php');
    include('home.php');
    exit(0);
}
$_SESSION['token'] = rand(1, 100);
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dimayacyac's Piggery Farm Management System</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/aja x/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .blink {
            animation: 2s linear infinite condemned_blink_effect;
        }

        @keyframes spin-slow {
            from {
                transform: rotate(0deg);
            }

            to {
                transform: rotate(360deg);
            }
        }

        .animate-spin-slow {
            animation: spin-slow 3s linear infinite;
        }

        .transform {
            transition: all 0.3s ease;
        }

        .hover\:scale-110:hover {
            transform: scale(1.1);
        }

        .hover\:-translate-y-1:hover {
            transform: translateY(-4px);
        }

        /* Ensure iframe takes full height */
        html,
        body,
        #app-container,
        .flex-1 {
            height: 100%;
        }

        body {
            margin: 0;
        }

        /* Reset default body margin */

        /* Sidebar Styles */
        .sidebar {
            background: linear-gradient(180deg, #e546ad 0%, #e546ad 100%);
            color: #e0e7ff;
            box-shadow: 2px 0 5px rgba(0, 0, 0, 0.1);
            transition: width 0.3s ease;
        }

        .sidebar a {
            transition: background-color 0.2s ease, color 0.2s ease, padding-left 0.2s ease;
            border-left: 4px solid transparent;
        }

        .sidebar a:hover {
            background-color: rgba(255, 255, 255, 0.1);
            color: white;
            border-left-color: #e546ad;
        }

        .sidebar a.active {
            background-color: rgba(255, 255, 255, 0.2);
            color: white;
            font-weight: 600;
            border-left-color: white;
        }

        .sidebar-header {
            border-bottom: 1px solid rgba(255, 255, 255, 0.2);
        }

        .user-info {
            border-top: 1px solid rgba(255, 255, 255, 0.2);
        }

        .logout-btn {
            background-color: rgba(255, 255, 255, 0.1);
            transition: background-color 0.2s ease;
        }

        .logout-btn:hover {
            background-color: rgba(255, 255, 255, 0.2);
        }

        /* Enhanced UI Styles */
        .auth-container {
            background: linear-gradient(135deg, #fcf6fc 0%, #e9f0f7 100%);
        }

        .auth-card {
            background: white;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            border-radius: 1rem;
            transition: transform 0.2s ease-in-out;
        }

        .auth-card:hover {
            transform: translateY(-2px);
        }

        .btn-primary {
            background: linear-gradient(135deg, #e546b5 0%, #e546b5 100%);
            transition: all 0.2s ease;
        }

        .btn-primary:hover {
            background: linear-gradient(135deg, #e546b5 0%, #e546b5 100%);
            transform: translateY(-1px);
        }

        .btn-secondary {
            background: #f3f4f6;
            color: #e54698;
            transition: all 0.2s ease;
        }

        .btn-secondary:hover {
            background: #e5e7eb;
            transform: translateY(-1px);
        }

        .input-field {
            border: 2px solid #e5e7eb;
            transition: all 0.2s ease;
        }

        .input-field:focus {
            border-color: #e546b5;
            box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.1);
        }

        .select-field {
            border: 2px solid #e5e7eb;
            transition: all 0.2s ease;
        }

        .select-field:focus {
            border-color: #e546b5;
            box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.1);
        }
    </style>
</head>

<body class="auth-container h-full">
    <!-- Step 1: Account Type Selection -->
    <div id="account-type-screen" class="min-h-screen flex justify-center items-center">
        <form method="POST">
            <div class="auth-card p-8 w-96 text-center">
                <div class="mb-6">
                    <img src="logo.png-removebg-preview.png" alt="Farm Logo" class="w-30 h-auto mx-auto mb-5">
                    <h2 class="text-2xl font-bold text-gray-800 mb-2"> Dimayacyac's Piggery Farm System Management</h2>
                    <p class="text-gray-600">Sign in to Start your session</p>
                </div>
                <div class="text-left space-y-4">
                    <input hidden type="text" value="<?= $_SESSION['token']; ?>" name="token">
                    <div>
                        <label class="block text-gray-700 font-medium mb-1">Username</label>
                        <input name="username" id="login-username" type="text" placeholder="Enter your username"
                            class="input-field w-full p-3 rounded-lg focus:outline-none" required>
                    </div>
                    <div>
                        <label class="block text-gray-700 font-medium mb-1">Password</label>
                        <input name="account_password" id="login-password" type="password" placeholder="Enter your password"
                            class="input-field w-full p-3 rounded-lg focus:outline-none" required>
                    </div>
                </div>
                <br>
                <button type="submit" name="signin" class="btn-primary text-white px-4 py-2 rounded-lg w-full font-semibold mb-2">Log In</button>
                <?php if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($err)) : ?>
                    <div class="mt-2 text-sm">
                        <?= $err ?>
                    </div>
                <?php endif; ?>
            </div>
        </form>
    </div>
</body>

</html>