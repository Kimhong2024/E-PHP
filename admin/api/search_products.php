<?php
// File: api/search_products.php

require_once '../include/db_connect.php';

$database = new Database();
$conn = $database->getConnection();

$search = $_GET['search'];

$query = "SELECT * FROM products WHERE name LIKE :search OR category LIKE :search";
$stmt = $conn->prepare($query);

$searchTerm = "%$search%";
$stmt->bindParam(':search', $searchTerm);

$stmt->execute();

$products = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo json_encode($products);
?>