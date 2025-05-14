<?php
date_default_timezone_set('Asia/Manila');
// Define your secure SQL SELECT function using mysqli_fetch_array
function sql_select($db_connection, $query, $types = "", $params = [])
{
    // Prepare the SQL statement
    $stmt = mysqli_prepare($db_connection, $query);
    if ($stmt === false) {
        die('MySQL prepare error: ' . mysqli_error($db_connection));
    }

    // Only bind parameters if types and params are provided
    if (!empty($types) && !empty($params)) {
        mysqli_stmt_bind_param($stmt, $types, ...$params);
    }

    // Execute the query
    mysqli_stmt_execute($stmt);

    // Get the result of the query
    $result = mysqli_stmt_get_result($stmt);

    // Initialize an empty array to hold the results
    $data = [];

    // Fetch each row using mysqli_fetch_array and while loop
    while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
        $data[] = $row;
    }

    // Free the result set
    mysqli_free_result($result);

    // Return the fetched data
    return $data;
}
function sql($db_connection, $query, $types, $params)
{
    if ($stmt = mysqli_prepare($db_connection, $query)) {
        mysqli_stmt_bind_param($stmt, $types, ...$params);
        if (mysqli_stmt_execute($stmt)) {
            return true; // Success
        } else {
            echo "Query execution failed: " . mysqli_error($db_connection);
            return false; // Error during execution
        }

        mysqli_stmt_close($stmt);
    } else {
        echo "Query preparation failed: " . mysqli_error($db_connection);
        return false;
    }
}
function escape_str($db_connection, $str)
{
    if ($str != '') {
        $str = trim($str);
        return mysqli_real_escape_string($db_connection, $str);
    }
}
function sanitize_input($db_connection, $data)
{
    // Check if $data is null and return an empty string or an appropriate value if needed
    if ($data === null) {
        return '';  // Or return some default value if you want
    }

    // Proceed with sanitization
    return htmlspecialchars(mysqli_real_escape_string($db_connection, trim($data)), ENT_QUOTES, 'UTF-8');
}

function GetData($sql_query, $types = "", $params = [])
{
    global $db_connection;  // Declare $db_connection as global to access the database connection

    // Prepare the statement
    $stmt = mysqli_prepare($db_connection, $sql_query);
    if ($stmt === false) {
        echo "Error preparing query: " . mysqli_error($db_connection);
        return '';  // Return empty string if query preparation fails
    }

    // Bind parameters if any
    if (!empty($params)) {
        mysqli_stmt_bind_param($stmt, $types, ...$params);
    }

    // Execute the statement
    mysqli_stmt_execute($stmt);

    // Bind the result variable
    mysqli_stmt_bind_result($stmt, $result);

    // Fetch the result
    if (mysqli_stmt_fetch($stmt)) {
        mysqli_stmt_close($stmt);
        // Return the result, ensuring it's not null
        return $result ?? '';  // If $result is null, return empty string
    } else {
        mysqli_stmt_close($stmt);
        return '';  // If no data, return empty string
    }
}



function GetValue($sql_query)
{
    error_reporting(0);
    global $db_connection;
    $result = mysqli_query($db_connection, $sql_query);
    $row = mysqli_fetch_array($result);
    return $row[0];
}
function Number($float)
{
    if ($float == 0) {
        return "";
    } else {
        if ($float > 0) {
            return number_format($float, 2, ".", ",");
        } else {
            return '(' . number_format(abs($float), 2, ".", ",") . ')';
        }
    }
}

function secureData($string, $action = 'e')
{
    // you may change these values to your own
    $secret_key = 'monkey.d.luffy';
    $secret_iv = 'monkey.d.garp';
    $output = false;
    $encrypt_method = "AES-256-CBC";
    $key = hash('sha256', $secret_key);
    $iv = substr(hash('sha256', $secret_iv), 0, 16);
    if ($action == 'e') {
        $output = base64_encode(openssl_encrypt($string, $encrypt_method, $key, 0, $iv));
    } else if ($action == 'd') {
        $output = openssl_decrypt(base64_decode($string), $encrypt_method, $key, 0, $iv);
    }
    return $output;
}
function jsonResponse($status, $message)
{
    echo json_encode(["status" => $status, "message" => $message]);
    exit();
}
function middleware($accountid, $accounttypeid, $allowedTypes = [])
{
    if (!$accountid || !in_array($accounttypeid, $allowedTypes)) {
        header('Location: ../forbidden');
        exit;
    }
}
function is_maintenance()
{
    global $db_connection;

    // Check if maintenance mode is active
    $query = "SELECT is_maintenance FROM tblsystem_maintenance WHERE id = 1";
    $result = mysqli_query($db_connection, $query);

    if ($result) {
        $row = mysqli_fetch_assoc($result);
        if (!empty($row['is_maintenance'])) {
            // If maintenance is active, allow only Admins (accounttypeid = 1)
            if (!isset($_SESSION['accounttypeid']) || $_SESSION['accounttypeid'] != 1) {
                header('Location: ../forbidden');
                exit();
            }
        }
    }
}


function Audit($accountid, $activity, $description)
{
    global $db_connection;
    return mysqli_query($db_connection, 'insert into tblaudittrail SET accountid=\'' . $accountid . '\', description=\'' . $activity . '\', activity=\'' . $description . '\'  ');
}

function Rank($id)
{
    global $db_connection;
    return GetData('SELECT rank FROM tblrank WHERE rankid = ?', 'i', [$id]);
}

function Region($id)
{
    global $db_connection;
    return GetData('SELECT SUBSTRING(region, 1, 10) FROM tblregion WHERE regionid = ?', 'i', [$id]);
}

function AccountName($id)
{
    global $db_connection;
    return GetData('SELECT account_name FROM tblaccounts WHERE accountid = ?', 's', [$id]);
}

function AccountType($id)
{
    global $db_connection;
    return GetData('SELECT accounttype FROM tblaccounttype WHERE accounttypeid = ?', 'i', [$id]);
}

function CategoryCode($id)
{
    global $db_connection;
    return GetData('SELECT category_code FROM tblcategory WHERE categoryid = ?', 'i', [$id]);
}

function Category($id)
{
    global $db_connection;
    return GetData('SELECT a.category_code FROM tblcategory a, tblsubcategory b WHERE a.categoryid = b.categoryid AND b.subcategoryid = ?', 'i', [$id]);
}

function Subcategory($id)
{
    global $db_connection;
    return GetData('SELECT subcategory FROM tblsubcategory WHERE subcategoryid = ?', 'i', [$id]);
}
