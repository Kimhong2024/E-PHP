<?php
// File: api/update_product.php

require_once '../include/db_connect.php';

$database = new Database();
$conn = $database->getConnection();

$data = json_decode(file_get_contents("php://input"), true);

$id = $data['id'];
$name = $data['name'];
$category = $data['category'];
$price = $data['price'];
$stock = $data['stock'];
$image = $data['image']; // Assuming image is handled separately

$query = "UPDATE products SET name = :name, category = :category, price = :price, stock = :stock, image = :image WHERE id = :id";
$stmt = $conn->prepare($query);

$stmt->bindParam(':id', $id);
$stmt->bindParam(':name', $name);
$stmt->bindParam(':category', $category);
$stmt->bindParam(':price', $price);
$stmt->bindParam(':stock', $stock);
$stmt->bindParam(':image', $image);

if ($stmt->execute()) {
    echo json_encode(['status' => 'success', 'message' => 'Product updated successfully.']);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Failed to update product.']);
}
?>