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

    $sql = "SELECT * FROM tblaccounts WHERE username='$username' AND account_password='$password' LIMIT 1";
    $rs = mysqli_query($db_connection, $sql);

    if ($rs && mysqli_num_rows($rs) === 1) {
        $user = mysqli_fetch_assoc($rs);
        $_SESSION['accountid'] = $user['accountid'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['account_type'] = $user['account_type'];
        $_SESSION['is_blocked'] = $user['is_blocked'];
        Audit($_SESSION['accountid'], 'Login','Login');
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
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="icon" type="image/png" href="docs/logo.png-removebg-preview.png" />
    <title>Dimayacyac's Piggery Farm Management System</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        .blink {
            animation: condemned_blink_effect 2s linear infinite;
        }

        @keyframes condemned_blink_effect {
            0% {
                opacity: 1;
            }

            50% {
                opacity: 0;
            }

            100% {
                opacity: 1;
            }
        }
    </style>
</head>

<body class="bg-gradient-to-r from-pink-50 to-blue-50 min-h-screen flex items-center justify-center">

    <form method="POST" class="w-full max-w-sm" id="loginForm">
        <!-- STEP 1: SELECT ROLE -->
        <div id="step1" class="bg-white rounded-2xl shadow-lg p-8 text-center">
            <img src="docs/logo.png-removebg-preview.png" alt="Logo" class="w-100 mx-auto mb-4" />
            <h2 class="text-xl font-bold text-gray-800">Dimayacyac's Piggery Farm System Management</h2>
            <p class="text-gray-600 mb-5">Please select your account type</p>
            <select name="role" id="roleSelect" class="w-full p-3 rounded-md border-2 border-gray-300 focus:border-pink-400 mb-6" required>
                <option disabled selected>Choose account type</option>
                <option value="Farm Owner">Farm Owner</option>
                <option value="Care Taker">Care Taker</option>
            </select>
            <button type="button" onclick="nextStep()" class="bg-pink-500 hover:bg-pink-600 text-white font-semibold w-full py-2 rounded-lg transition">Continue</button>
        </div>

        <!-- STEP 2: LOGIN -->
        <div id="step2" class="bg-white rounded-2xl shadow-lg p-8 text-center hidden relative">
            <!-- Back Button -->
            <button style="text-decoration:none;" type="button" onclick="goBack()" class="absolute top-4 left-4 text-pink-600 font-semibold text-sm hover:underline">← Back</button>

            <div class="mb-6 mt-2">
                <img src="docs/logo.png-removebg-preview.png" alt="Logo" class="w-100 mx-auto mb-4" />
                <h2 class="text-xl font-bold text-gray-800 mb-2">Dimayacyac's Piggery Farm System Management</h2>
                <p class="text-gray-600">Sign in to start your session</p>
            </div>

            <input type="hidden" name="token" value="<?= $_SESSION['token']; ?>">

            <div class="text-left space-y-4">
                <div>
                    <label class="block text-gray-700 font-medium mb-1">Username</label>
                    <input name="username" type="text" placeholder="Enter your username" class="w-full p-3 border-2 border-gray-300 rounded-lg focus:outline-none focus:border-pink-400" required>
                </div>
                <div>
                    <label class="block text-gray-700 font-medium mb-1">Password</label>
                    <input name="account_password" type="password" placeholder="Enter your password" class="w-full p-3 border-2 border-gray-300 rounded-lg focus:outline-none focus:border-pink-400" required>
                </div>
            </div>

            <br>
            <button type="submit" name="signin" class="bg-pink-500 hover:bg-pink-600 text-white w-full py-2 rounded-lg font-semibold">Log In</button>

            <?php if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($err)) : ?>
                <div class="mt-3 text-sm"><?= $err ?></div>
            <?php endif; ?>
        </div>
    </form>

    <script>
        function nextStep() {
            const role = document.getElementById('roleSelect').value;
            if (!role || role === "Choose account type") {
                alert("Please select an account type.");
                return;
            }
            document.getElementById('step1').classList.add('hidden');
            document.getElementById('step2').classList.remove('hidden');
        }

        function goBack() {
            document.getElementById('step2').classList.add('hidden');
            document.getElementById('step1').classList.remove('hidden');
        }
    </script>

</body>

</html>