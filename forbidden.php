<?php
// forbidden.php
session_start();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Access Denied</title>
    <style>
        /* body {
            background-color: #f8d7da;
            color: #721c24;
            font-family: Arial, sans-serif;
            text-align: center;
            padding: 100px;
        } */

        .box {
            background-color: #f5c6cb;
            border: 1px solid #f5c2c7;
            padding: 30px;
            border-radius: 8px;
            display: inline-block;
        }

        h1 {
            font-size: 48px;
            margin-bottom: 10px;
        }

        p {
            font-size: 20px;
        }

        /* 
        a {
            display: inline-block;
            margin-top: 20px;
            padding: 10px 20px;
            background-color: #721c24;
            color: white;
            text-decoration: none;
            border-radius: 5px;
        } */

        a:hover {
            background-color: #501217;
        }
    </style>
</head>

<body>

    <div style="display: flex; justify-content: center; align-items: center; height: 100vh;">
        <div class="box">
            <h1>403 ERROR</h1>
            <p>We're sorry, but your access has been restricted.</p>
            <a href="./">Return to Home</a>
        </div>
    </div>


</body>

</html>