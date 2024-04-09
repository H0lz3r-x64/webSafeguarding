<?php
session_start();
include "database.php";

if (!isset($_SESSION['user'])) {
    header("Location: frontend.php");
    exit;
}

if (!isset($_POST['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
    die('Invalid CSRF token');
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
    <title>SQL-Injections BACKEND</title>
</head>

<body>
    <h1>Test-Seite BACKEND</h1>
    <?= $out ?>
</body>

</html>