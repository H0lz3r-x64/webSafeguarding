<?php
include "database.php";

$sql = "SELECT * FROM `user`";
$result = database::dbConnection()->query($sql);
$out = "";
while($row = $result->fetch_assoc()) {
    $out .= "ID: " .$row["id"]. " || Name: " . $row["name"]. "|| Passwort: " . $row["password"]. "<br>";
}
database::dbConnection()->close();
?>

<html lang="de">
<head>
    <title>SQL-Injections BACKEND</title>
</head>
<body>
<h1>Test-Seite BACKEND</h1>
<?=$out?>
</body>
</html>
