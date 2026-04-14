<?php
require 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $password = password_hash($_POST["password"], PASSWORD_DEFAULT);

    $sql = "INSERT INTO utenti (username, password) VALUES (?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$username, $password]);

    echo "Registrazione completata!";
}
?>

<form method="POST">
    Username: <input type="text" name="username" required><br>
    Password: <input type="password" name="password" required><br>
    <button type="submit">Registrati</button>
</form>