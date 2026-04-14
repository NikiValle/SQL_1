<?php
session_start();
require 'db.php';

if (!isset($_SESSION["user"])) {
    header("Location: login.php");
}

$persone = $conn->query("SELECT * FROM persone")->fetchAll();
?>

<!DOCTYPE html>
<html>
<head>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>

<div class="container mt-4">

    <h2>Elenco Persone</h2>

    <a href="aggiungi.php" class="btn btn-success mb-3">Aggiungi Persona</a>

    <table class="table table-striped table-bordered">
        <thead class="table-dark">
            <tr>
                <th>Nome</th>
                <th>Cognome</th>
                <th>Data</th>
                <th>Azioni</th>
            </tr>
        </thead>

        <tbody>
        <?php foreach ($persone as $p): ?>
            <tr>
                <td><?= $p["nome"] ?></td>
                <td><?= $p["cognome"] ?></td>
                <td><?= $p["data_nascita"] ?></td>
                <td>
                    <a href="modifica.php?id=<?= $p["id"] ?>" class="btn btn-warning btn-sm">Modifica</a>
                    <a href="elimina.php?id=<?= $p["id"] ?>" class="btn btn-danger btn-sm">Elimina</a>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>

    <a href="cerca.php" class="btn btn-info">Cerca per cognome</a>
    <a href="filtro_data.php" class="btn btn-secondary">Filtro per data</a>

</div>

</body>
</html>