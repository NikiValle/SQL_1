<?php
require 'db.php';

$id = $_GET["id"];

if ($_POST) {
    $stmt = $conn->prepare("UPDATE persone SET nome=?, cognome=?, data_nascita=? WHERE id=?");
    $stmt->execute([$_POST["nome"], $_POST["cognome"], $_POST["data"], $id]);
    header("Location: persone.php");
}

$p = $conn->query("SELECT * FROM persone WHERE id=$id")->fetch();
?>

<form method="POST">
    Nome: <input name="nome" value="<?= $p["nome"] ?>"><br>
    Cognome: <input name="cognome" value="<?= $p["cognome"] ?>"><br>
    Data: <input type="date" name="data" value="<?= $p["data_nascita"] ?>"><br>
    <button>Aggiorna</button>
</form>