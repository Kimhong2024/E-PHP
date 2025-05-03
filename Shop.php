<?php
// Product.php
require_once 'admin/include/db.php';

class Product {
    private $db;

    public function __construct() {
        $this->db = (new Database())->connect();
    }

    // Fetch all products with category information
    public function getAllProducts() {
        $query = "SELECT p.*, c.name as category_name 
                  FROM products p 
                  LEFT JOIN categories c ON p.category_id = c.id 
                  ORDER BY p.id DESC";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Fetch products by category
    public function getProductsByCategory($category_id) {
        $query = "SELECT p.*, c.name as category_name 
                  FROM products p 
                  LEFT JOIN categories c ON p.category_id = c.id 
                  WHERE p.category_id = ? 
                  ORDER BY p.id DESC";
        $stmt = $this->db->prepare($query);
        $stmt->execute([$category_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}

// Initialize Product class
$product = new Product();

// Get category ID from URL if present
$category_id = isset($_GET['category']) ? (int)$_GET['category'] : null;

// Fetch products based on category or all products
$products = $category_id ? $product->getProductsByCategory($category_id) : $product->getAllProducts();
?>

<!-- Products Section -->
<section id="products" class="container mx-auto my-12 px-4">
    <div class="flex justify-between items-center mb-8">
        <h2 class="text-3xl font-bold">
            <?php 
            if ($category_id) {
                echo "Products in " . htmlspecialchars($products[0]['category_name'] ?? 'Category');
            } else {
                echo "All Products";
            }
            ?>
        </h2>
        <div class="flex gap-4">
            <a href="Shop.php" class="px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition">
                All Products
            </a>
            <a href="Category.php" class="px-4 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600 transition">
                Categories
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-8">
        <?php if (empty($products)): ?>
            <div class="col-span-full text-center text-gray-500">
                <p class="text-xl">No products found</p>
                <p class="mt-2">Please check back later or browse our other categories.</p>
            </div>
        <?php else: ?>
            <?php foreach ($products as $product): ?>
                <div class="bg-white p-6 rounded-lg shadow-lg hover:shadow-xl transition-shadow product-card">
                    <?php if (!empty($product['image'])): ?>
                        <img src="admin/<?php echo htmlspecialchars($product['image']); ?>" 
                             alt="<?php echo htmlspecialchars($product['name']); ?>" 
                             class="w-full h-48 object-cover rounded-md mb-4">
                    <?php else: ?>
                        <div class="w-full h-48 bg-gray-200 rounded-md mb-4 flex items-center justify-center">
                            <i class="fas fa-image text-gray-400 text-4xl"></i>
                        </div>
                    <?php endif; ?>
                    
                    <div class="mb-2">
                        <span class="text-sm text-gray-500"><?php echo htmlspecialchars($product['category_name'] ?? 'Uncategorized'); ?></span>
                    </div>
                    
                    <h3 class="text-lg font-semibold mb-2"><?php echo htmlspecialchars($product['name']); ?></h3>
                    <p class="text-gray-500 mb-2"><?php echo htmlspecialchars($product['description']); ?></p>
                    
                    <div class="flex items-center justify-between">
                        <span class="text-blue-500 font-bold">$<?php echo number_format($product['price'], 2); ?></span>
                        <button class="px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition add-to-cart"
                                data-id="<?php echo $product['id']; ?>"
                                data-name="<?php echo htmlspecialchars($product['name']); ?>"
                                data-price="<?php echo $product['price']; ?>"
                                data-image="<?php echo htmlspecialchars($product['image']); ?>">
                            <span class="flex items-center justify-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                                    <path d="M3 1a1 1 0 000 2h1.22l.305 1.222a.997.997 0 00.01.042l1.358 5.43-.893.892C3.74 11.846 4.632 14 6.414 14H15a1 1 0 000-2H6.414l1-1H14a1 1 0 00.894-.553l3-6A1 1 0 0017 3H6.28l-.31-1.243A1 1 0 005 1H3zM16 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0zM6.5 18a1.5 1.5 0 100-3 1.5 1.5 0 000 3z" />
                                </svg>
                                Add to Cart
                            </span>
                        </button>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</section>

<style>
.product-card {
    transition: transform 0.3s ease;
}

.product-card:hover {
    transform: translateY(-5px);
}

.add-to-cart {
    transition: background-color 0.3s ease;
}

.add-to-cart:hover {
    background-color: #2563eb;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Add to Cart functionality
    document.querySelectorAll('.add-to-cart').forEach(button => {
        button.addEventListener('click', function() {
            const productId = this.getAttribute('data-id');
            const productName = this.getAttribute('data-name');
            const productPrice = this.getAttribute('data-price');
            const productImage = this.getAttribute('data-image');
            
            // Create cart item object
            const cartItem = {
                id: productId,
                name: productName,
                price: productPrice,
                image: productImage,
                quantity: 1
            };
            
            // Get existing cart from localStorage or create new one
            let cart = JSON.parse(localStorage.getItem('cart')) || [];
            
            // Check if product already exists in cart
            const existingItem = cart.find(item => item.id === productId);
            if (existingItem) {
                existingItem.quantity += 1;
            } else {
                cart.push(cartItem);
            }
            
            // Save cart back to localStorage
            localStorage.setItem('cart', JSON.stringify(cart));
            
            // Show success message
            alert('Product added to cart!');
            
            // Update cart count in header if it exists
            const cartCount = document.getElementById('cart-count');
            if (cartCount) {
                cartCount.textContent = cart.reduce((total, item) => total + item.quantity, 0);
            }
        });
    });
});
</script>