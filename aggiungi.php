<?php
session_start();
require 'db.php';

// Protezione accesso
if (!isset($_SESSION["user"])) {
    header("Location: login.php");
    exit;
}

// Salvataggio dati
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $stmt = $conn->prepare("INSERT INTO persone (nome, cognome, data_nascita) VALUES (?, ?, ?)");
    $stmt->execute([
        $_POST["nome"],
        $_POST["cognome"],
        $_POST["data"]
    ]);

    header("Location: persone.php");
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Aggiungi Persona</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">

<div class="container mt-5">
    <div class="card p-4 shadow">

        <h3 class="mb-3">Aggiungi Persona</h3>

        <form method="POST">
            <input class="form-control mb-3" name="nome" placeholder="Nome" required>
            <input class="form-control mb-3" name="cognome" placeholder="Cognome" required>
            <input class="form-control mb-3" type="date" name="data" required>

            <button class="btn btn-success">Salva</button>
            <a href="persone.php" class="btn btn-secondary">Indietro</a>
        </form>

    </div>
</div>

</body>
</html>