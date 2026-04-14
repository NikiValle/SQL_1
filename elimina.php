<?php
require 'db.php';

$id = $_GET["id"];
$conn->query("DELETE FROM persone WHERE id=$id");

header("Location: persone.php");
?>