<?php
// getProduct.php

require_once '../include/Database.php';
require_once 'ProductClass.php';

$database = new Database();
$db = $database->connect();

$product = new Product($db);

if (isset($_GET['id'])) {
    $product->id = $_GET['id'];
    $stmt = $product->read_single();
    $product = $stmt->fetch(PDO::FETCH_ASSOC);
    echo json_encode($product);
}
?>