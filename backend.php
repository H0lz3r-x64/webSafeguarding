<?php
session_start();

$time_in_seconds = 10;
// Check if session is expired
if (isset($_SESSION['LAST_ACTIVITY']) && (time() - $_SESSION['LAST_ACTIVITY'] > $time_in_seconds)) {
    // last request was more than $time_in_seconds seconds ago
    session_unset();     // unset $_SESSION variable for the run-time 
    session_destroy();   // destroy session data in storage
    header("Location: index.php");
    exit;
}
$_SESSION['LAST_ACTIVITY'] = time(); // update last activity time stamp

// Regenerate session ID if needed
if (!isset($_SESSION['CREATED'])) {
    $_SESSION['CREATED'] = time();
} else if (time() - $_SESSION['CREATED'] > $time_in_seconds) {
    // session started more than $time_in_seconds seconds ago
    session_regenerate_id(true);    // change session ID for the current session and invalidate old session ID
    $_SESSION['CREATED'] = time();  // update creation time
}

include "database.php";

// check if user is logged in
if (!isset($_SESSION['user'])) {
    header("Location: index.php");
    exit;
}


// CSRF protection on post request
if (isset($_SESSION['post_data'])) {
    $_POST = $_SESSION['post_data'];
    unset($_SESSION['post_data']);

    if (!isset($_POST['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
        die('Invalid CSRF token');
    }
}

$sql = "SELECT * FROM `user`";
$result = database::dbConnection()->query($sql);
$out = "";
while ($row = $result->fetch_assoc()) {
    $out .= "ID: " . htmlspecialchars($row["id"], ENT_QUOTES, 'UTF-8') . " || Name: " . htmlspecialchars($row["name"], ENT_QUOTES, 'UTF-8') . "|| Passwort: " . htmlspecialchars($row["password"], ENT_QUOTES, 'UTF-8') . "<br>";
}
database::dbConnection()->close();
?>

<html lang="de">

<head>
    <title>Web Safeguarding BACKEND</title>
</head>

<body>
    <h1>Test-Seite BACKEND</h1>
    <?= $out ?>
</body>

</html>