<?php
session_start();
include 'database.php';

// Generate CSRF token
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

// Insert
if (isset($_REQUEST['submit_insert'])) {
    // Check CSRF token
    if (!isset($_POST['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
        die('Invalid CSRF token');
    }

    $stmt = database::dbConnection()->prepare("INSERT INTO user (name, password) VALUES (?, ?)");
    $stmt->bind_param("ss", $_REQUEST['Name'], $_REQUEST['Passwort']);
    if ($stmt->execute() === true) {
        echo "<p>Daten wurden gespeichert!</p>";
    } else {
        echo "<p>Error: " . htmlspecialchars($stmt->error, ENT_QUOTES, 'UTF-8') . "</p>";
    }
    $stmt->close();
}

// Login
if (isset($_REQUEST['submit_login'])) {
    // Check CSRF token
    if (!isset($_POST['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
        die('Invalid CSRF token');
    }

    $stmt = database::dbConnection()->prepare("SELECT * FROM `user` WHERE (password = ? AND name = ?)");
    $stmt->bind_param("ss", $_REQUEST['Passwort'], $_REQUEST['Name']);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows) {
        // Store user data in session
        $_SESSION['user'] = $result->fetch_assoc();

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
    $out .= "ID: " . htmlspecialchars($row["id"], ENT_QUOTES, 'UTF-8') . " || Name: " . htmlspecialchars($row["name"], ENT_QUOTES, 'UTF-8') . "|| Passwort: " . htmlspecialchars($row["password"], ENT_QUOTES, 'UTF-8') . "<br>";
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
    <form action="frontend.php" method="post">
        <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
        <input type="text" name="Name" placeholder="Name" required><br>
        <input type="password" name="Passwort" placeholder="Passwort" required><br>
        <input type="submit" name="submit_insert" value="Insert">
    </form>
    <hr>
    <h2>Login</h2>
    <form action="frontend.php" method="post">
        <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
        <input type="text" name="Name" placeholder="Name" required><br>
        <input type="password" name="Passwort" placeholder="Passwort" required><br>
        <input type="submit" name="submit_login" value="Login">
    </form>
    <hr>
    <h2>Output</h2>
    <?= $out ?>
</body>

</html>