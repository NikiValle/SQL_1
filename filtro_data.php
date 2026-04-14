<?php
require 'db.php';

if ($_POST) {
    $stmt = $conn->prepare("SELECT * FROM persone WHERE data_nascita > ?");
    $stmt->execute([$_POST["data"]]);
    $risultati = $stmt->fetchAll();
}
?>

<form method="POST">
    Data: <input type="date" name="data">
    <button>Filtra</button>
</form>

<?php if (!empty($risultati)): ?>
    <?php foreach ($risultati as $p): ?>
        <p><?= $p["nome"] ?> <?= $p["cognome"] ?> (<?= $p["data_nascita"] ?>)</p>
    <?php endforeach; ?>
<?php endif; ?>