<?php
include "database.php";

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