<?php
const SERVER_NAME = "db";
const DB_NAME = "winecatalog";
const USER_NAME = "wine";
const PASSWORD = "wine";
const PORT = "5432";

try {
    $db = new PDO("pgsql:host=" . SERVER_NAME . ";port=" . PORT . ";dbname=" . DB_NAME, USER_NAME, PASSWORD);
    // echo "connexion réussie";
} catch (PDOException $e) {
    echo "Echec de connexion : " . $e->getMessage() . "<br>";
}
