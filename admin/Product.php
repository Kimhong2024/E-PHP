<?php
// Product.php
require 'include/db.php';

class Product {
    private $db;

    public function __construct() {
        $this->db = (new Database())->connect();
    }

    // Add a new product
    public function addProduct($name, $image, $category, $price, $stock) {
        $query = "INSERT INTO products (name, image, category, price, stock) VALUES (:name, :image, :category, :price, :stock)";
        $stmt = $this->db->prepare($query);

        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':image', $image);
        $stmt->bindParam(':category', $category);
        $stmt->bindParam(':price', $price);
        $stmt->bindParam(':stock', $stock);

        return $stmt->execute();
    }

    // Fetch all products
    public function getAllProducts() {
        $query = "SELECT * FROM products";
        $stmt = $this->db->prepare($query);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}

// Handle form submission for adding a product
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $product = new Product();

    // Handle file upload
    $imagePath = '';
    if ($_FILES['productImage']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = 'uploads/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }
        $uploadFile = $uploadDir . basename($_FILES['productImage']['name']);

        if (move_uploaded_file($_FILES['productImage']['tmp_name'], $uploadFile)) {
            $imagePath = $uploadFile;
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to upload image.']);
            exit;
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'No image uploaded.']);
        exit;
    }

    // Add product to the database
    if ($product->addProduct($_POST['productName'], $imagePath, $_POST['productCategory'], $_POST['productPrice'], $_POST['productStock'])) {
        echo json_encode(['success' => true, 'message' => 'Product added successfully!']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to add product.']);
    }
    exit;
}

// Fetch all products to display
$product = new Product();
$products = $product->getAllProducts();
?>
<div class="container">
<div id="message"></div>
  <div class="page-inner">
    <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row pt-2 pb-4">
      <div>
        <h3 class="fw-bold mb-3">Product Management</h3>
      </div>
      <div class="ms-md-auto py-2 py-md-0">
        <button id="addProductBtn" class="btn btn-primary btn-round" data-bs-toggle="modal" data-bs-target="#productModal">
          <i class="fas fa-plus"></i> Add Product
        </button>
      </div>
    </div>

    <!-- Product Table -->
    <div class="row">
      <div class="col-md-12">
        <div class="card card-round">
          <div class="card-header">
            <div class="card-head-row">
              <div class="card-title">Product List</div>
              <div class="card-tools">
                <input type="text" id="searchProduct" class="form-control" placeholder="Search products...">
              </div>
            </div>
          </div>
          <div class="card-body">
            <div class="table-responsive">
              <table class="table table-hover" id="productTable">
                <thead>
                  <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Image</th>
                    <th>Category</th>
                    <th>Price</th>
                    <th>Stock</th>
                    <th>Actions</th>
                  </tr>
                </thead>
                <tbody>
                  <?php foreach ($products as $product): ?>
                    <tr>
                      <td><?php echo $product['id']; ?></td>
                      <td><?php echo $product['name']; ?></td>
                      <td>
                        <img src="<?php echo $product['image']; ?>" alt="<?php echo $product['name']; ?>" width="50">
                      </td>
                      <td><?php echo $product['category']; ?></td>
                      <td>$<?php echo number_format($product['price'], 2); ?></td>
                      <td><?php echo $product['stock']; ?></td>
                      <td>
                        <button class="btn btn-sm btn-warning">Edit</button>
                        <button class="btn btn-sm btn-danger">Delete</button>
                      </td>
                    </tr>
                  <?php endforeach; ?>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Add/Edit Product Modal -->
<div class="modal fade" id="productModal" tabindex="-1" aria-labelledby="productModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="productModalLabel">Add Product</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form id="productForm" method="POST" enctype="multipart/form-data">
          <input type="hidden" id="productId">
          <div class="form-group">
            <label for="productImage">Product Image</label>
            <input type="file" class="form-control" id="productImage" name="productImage" accept="image/*" required>
            <div id="imagePreview" class="mt-2"></div>
          </div>
          <div class="form-group">
            <label for="productName">Product Name</label>
            <input type="text" class="form-control" id="productName" name="productName" required>
          </div>
          <div class="form-group">
            <label for="productCategory">Category</label>
            <select class="form-control" id="productCategory" name="productCategory" required>
              <option value="Electronics">Electronics</option>
              <option value="Clothing">Clothing</option>
              <option value="Home & Kitchen">Home & Kitchen</option>
            </select>
          </div>
          <div class="form-group">
            <label for="productPrice">Price</label>
            <input type="number" class="form-control" id="productPrice" name="productPrice" step="0.01" required>
          </div>
          <div class="form-group">
            <label for="productStock">Stock</label>
            <input type="number" class="form-control" id="productStock" name="productStock" required>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="submit" form="productForm" class="btn btn-primary">Save</button>
      </div>
    </div>
  </div>
</div>


