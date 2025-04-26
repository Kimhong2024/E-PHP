<?php
// Product.php

class Product {
    private $conn;
    private $table = 'products';

    public $id;
    public $name;
    public $image;
    public $category;
    public $price;
    public $stock;

    public function __construct($db) {
        $this->conn = $db;
    }

    // Create Product
    public function create() {
        $query = 'INSERT INTO ' . $this->table . ' 
                  SET name = :name, 
                      image = :image, 
                      category = :category, 
                      price = :price, 
                      stock = :stock';

        $stmt = $this->conn->prepare($query);

        // Bind data
        $stmt->bindParam(':name', $this->name);
        $stmt->bindParam(':image', $this->image);
        $stmt->bindParam(':category', $this->category);
        $stmt->bindParam(':price', $this->price);
        $stmt->bindParam(':stock', $this->stock);

        if($stmt->execute()) {
            return true;
        }

        return false;
    }

    // Read Products
    public function read() {
        $query = 'SELECT * FROM ' . $this->table;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    // Update Product
    public function update() {
        $query = 'UPDATE ' . $this->table . ' 
                  SET name = :name, 
                      image = :image, 
                      category = :category, 
                      price = :price, 
                      stock = :stock 
                  WHERE id = :id';

        $stmt = $this->conn->prepare($query);

        // Bind data
        $stmt->bindParam(':name', $this->name);
        $stmt->bindParam(':image', $this->image);
        $stmt->bindParam(':category', $this->category);
        $stmt->bindParam(':price', $this->price);
        $stmt->bindParam(':stock', $this->stock);
        $stmt->bindParam(':id', $this->id);

        if($stmt->execute()) {
            return true;
        }

        return false;
    }

    // Delete Product
    public function delete() {
        $query = 'DELETE FROM ' . $this->table . ' WHERE id = :id';
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $this->id);

        if($stmt->execute()) {
            return true;
        }

        return false;
    }
}
?>