<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
    $id = (int)$_POST['id'];
    unset($_SESSION['cart'][$id]);
}

header("Location: cart.php");
exit;
