<?php
require_once 'Product.php';

$product = new Product();

// Handle product creation
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['image'])) {
    $name = $_POST['name'];
    $category = $_POST['category'];
    $price = $_POST['price'];
    $stock = $_POST['stock'];

    // Image upload
    $targetDir = "uploads/";
    $imageName = time() . '_' . basename($_FILES["image"]["name"]);
    $targetFilePath = $targetDir . $imageName;
    
    if (move_uploaded_file($_FILES["image"]["tmp_name"], $targetFilePath)) {
        $imagePath = $targetFilePath;
        $success = $product->addProduct($name, $imagePath, $category, $price, $stock);
        echo json_encode(['success' => $success]);
    } else {
        echo json_encode(['error' => 'Image upload failed']);
    }
    exit;
}

// Fetch all products
if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    $products = $product->getAllProducts();
    echo json_encode($products);
    exit;
}
?>
