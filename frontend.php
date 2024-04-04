<?php
include 'database.php';

// Insert
if (isset($_REQUEST['submit_insert'])) {
    $stmt = database::dbConnection()->prepare("INSERT INTO user (name, password) VALUES (?, ?)");
    $stmt->bind_param("ss", $_REQUEST['Name'], $_REQUEST['Passwort']);
    if ($stmt->execute() === true) {
        echo "<p>Daten wurden gespeichert!</p>";
    } else {
        echo "<p>Error: " . $stmt->error . "</p>";
    }
    $stmt->close();
}

// Login
if (isset($_REQUEST['submit_login'])) {
    $stmt = database::dbConnection()->prepare("SELECT * FROM `user` WHERE (password = ? AND name = ?)");
    $stmt->bind_param("ss", $_REQUEST['Passwort'], $_REQUEST['Name']);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows) {
        header("Location: backend.php");
    } else {
        echo "Passwort oder User-Name falsch";
    }
    $stmt->close();
}

// Output
$sql = "SELECT * FROM `user`";
$result = database::dbConnection()->query($sql);
$out = "";
// Ausgabe
while ($row = $result->fetch_assoc()) {
    $out .= "ID: " . $row["id"] . " || Name: " . $row["name"] . "|| Passwort: " . $row["password"] . "<br>";
}
database::dbConnection()->close();
?>

<html lang="de">

<head>
    <title>SQL-Injections</title>
</head>

<body>

    <h1>Test-Seite SQL Injections</h1>
    <h2>Insert</h2>
    <form action="frontend.php" method="get">
        <input type="text" name="Name" placeholder="Name" required><br>
        <input type="password" name="Passwort" placeholder="Passwort" required><br>
        <input type="submit" name="submit_insert" value="Insert">
    </form>
    <hr>
    <h2>Login</h2>
    <form action="frontend.php" method="get">
        <input type="text" name="Name" placeholder="Name" required><br>
        <input type="password" name="Passwort" placeholder="Passwort" required><br>
        <input type="submit" name="submit_login" value="Login">
    </form>
    <hr>
    <h2>Output</h2>
    <?= $out ?>
</body>

</html>