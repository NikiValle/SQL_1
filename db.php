<?php
require 'config.php';

try {
    // Connessione SENZA database (serve per crearlo)
    $conn = new PDO("mysql:host=$host", $user, $pass);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Creazione database se non esiste
    $conn->exec("CREATE DATABASE IF NOT EXISTS $dbname");

    // Selezione database
    $conn->exec("USE $dbname");

    // =========================
    // Creazione tabella utenti
    // =========================
    $conn->exec("
        CREATE TABLE IF NOT EXISTS utenti (
            id INT AUTO_INCREMENT PRIMARY KEY,
            username VARCHAR(100) NOT NULL UNIQUE,
            password VARCHAR(255) NOT NULL
        )
    ");

    // =========================
    // Creazione tabella persone
    // =========================
    $conn->exec("
        CREATE TABLE IF NOT EXISTS persone (
            id INT AUTO_INCREMENT PRIMARY KEY,
            nome VARCHAR(100) NOT NULL,
            cognome VARCHAR(100) NOT NULL,
            data_nascita DATE NOT NULL
        )
    ");

    // =========================
    // Tabella originale (facoltativa)
    // =========================
    $conn->exec("
        CREATE TABLE IF NOT EXISTS records (
            id INT AUTO_INCREMENT PRIMARY KEY,
            nome VARCHAR(255),
            valore INT
        )
    ");

} catch (PDOException $e) {
    die("Errore: " . $e->getMessage());
}
?>