<?php
require 'db.php';

if ($_POST) {
    $stmt = $conn->prepare("SELECT * FROM persone WHERE cognome = ?");
    $stmt->execute([$_POST["cognome"]]);
    $risultati = $stmt->fetchAll();
}
?>

<form method="POST">
    Cognome: <input name="cognome">
    <button>Cerca</button>
</form>

<?php if (!empty($risultati)): ?>
    <?php foreach ($risultati as $p): ?>
        <p><?= $p["nome"] ?> <?= $p["cognome"] ?></p>
    <?php endforeach; ?>
<?php endif; ?>