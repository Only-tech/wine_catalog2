<?php
require_once 'connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
    $sql = "DELETE FROM wines WHERE id = :id;";
    $query = $db->prepare($sql);
    $query->execute([$_POST['id']]);
    header("Location: admin.php");
    exit;
}
