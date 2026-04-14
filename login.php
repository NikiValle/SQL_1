<?php
session_start();
require 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $password = $_POST["password"];

    $stmt = $conn->prepare("SELECT * FROM utenti WHERE username = ?");
    $stmt->execute([$username]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user["password"])) {
        $_SESSION["user"] = $user["username"];
        header("Location: dashboard.php");
    } else {
        $errore = "Credenziali errate";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Login</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">

<div class="container mt-5">
    <div class="card mx-auto p-4 shadow" style="max-width:400px;">
        <h3 class="text-center mb-3">Login</h3>

        <?php if(isset($errore)): ?>
            <div class="alert alert-danger"><?= $errore ?></div>
        <?php endif; ?>

        <form method="POST">
            <input class="form-control mb-3" type="text" name="username" placeholder="Username" required>
            <input class="form-control mb-3" type="password" name="password" placeholder="Password" required>
            <button class="btn btn-primary w-100">Accedi</button>
        </form>

        <a href="register.php" class="mt-3 d-block text-center">Registrati</a>
    </div>
</div>

</body>
</html>