<?php
// File: api/delete_product.php

require_once '../include/db_connect.php';

$database = new Database();
$conn = $database->getConnection();

$data = json_decode(file_get_contents("php://input"), true);

$id = $data['id'];

$query = "DELETE FROM products WHERE id = :id";
$stmt = $conn->prepare($query);

$stmt->bindParam(':id', $id);

if ($stmt->execute()) {
    echo json_encode(['status' => 'success', 'message' => 'Product deleted successfully.']);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Failed to delete product.']);
}
?>