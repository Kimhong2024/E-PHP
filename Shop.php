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
<section id="products" class="py-12 bg-gray-50">
    <div class="container mx-auto px-4">
        <!-- Header Section -->
        <div class="flex flex-col md:flex-row justify-between items-center mb-12">
            <div class="mb-4 md:mb-0">
                <h2 class="text-4xl font-bold text-gray-800">
                    <?php 
                    if ($category_id) {
                        echo "Products in " . htmlspecialchars($products[0]['category_name'] ?? 'Category');
                    } else {
                        echo "All Products";
                    }
                    ?>
                </h2>
                <p class="text-gray-600 mt-2">Discover our amazing collection of products</p>
            </div>
            <div class="flex gap-4">
                <a href="Shop.php" class="px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-all duration-300 shadow-md hover:shadow-lg">
                    <i class="fas fa-th-large mr-2"></i>All Products
                </a>
                <a href="Category.php" class="px-6 py-3 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition-all duration-300 shadow-md hover:shadow-lg">
                    <i class="fas fa-tags mr-2"></i>Categories
                </a>
            </div>
        </div>

        <!-- Products Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-8">
            <?php if (empty($products)): ?>
                <div class="col-span-full text-center py-12">
                    <div class="bg-white p-8 rounded-lg shadow-lg">
                        <i class="fas fa-box-open text-6xl text-gray-400 mb-4"></i>
                        <h3 class="text-2xl font-semibold text-gray-700 mb-2">No Products Found</h3>
                        <p class="text-gray-500">Please check back later or browse our other categories.</p>
                    </div>
                </div>
            <?php else: ?>
                <?php foreach ($products as $product): ?>
                    <div class="bg-white rounded-xl shadow-md hover:shadow-xl transition-all duration-300 product-card">
                        <!-- Product Image -->
                        <div class="relative overflow-hidden rounded-t-xl">
                            <?php if (!empty($product['image'])): ?>
                                <img src="admin/<?php echo htmlspecialchars($product['image']); ?>" 
                                     alt="<?php echo htmlspecialchars($product['name']); ?>" 
                                     class="w-full h-64 object-cover transform hover:scale-105 transition-transform duration-300">
                            <?php else: ?>
                                <div class="w-full h-64 bg-gray-100 flex items-center justify-center">
                                    <i class="fas fa-image text-gray-400 text-4xl"></i>
                                </div>
                            <?php endif; ?>
                            <div class="absolute top-4 left-4">
                                <span class="px-3 py-1 bg-blue-600 text-white text-sm rounded-full">
                                    <?php echo htmlspecialchars($product['category_name'] ?? 'Uncategorized'); ?>
                                </span>
                            </div>
                        </div>

                        <!-- Product Info -->
                        <div class="p-6">
                            <h3 class="text-xl font-semibold text-gray-800 mb-2">
                                <?php echo htmlspecialchars($product['name']); ?>
                            </h3>
                            <p class="text-gray-600 mb-4 line-clamp-2">
                                <?php echo htmlspecialchars($product['description']); ?>
                            </p>
                            
        <div class="flex items-center justify-between">
                                <span class="text-2xl font-bold text-blue-600">
                                    $<?php echo number_format($product['price'], 2); ?>
                                </span>
                                <button class="add-to-cart px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-all duration-300 flex items-center"
                                        data-id="<?php echo $product['id']; ?>"
                                        data-name="<?php echo htmlspecialchars($product['name']); ?>"
                data-price="<?php echo $product['price']; ?>"
                                        data-image="<?php echo htmlspecialchars($product['image']); ?>">
                                    <i class="fas fa-shopping-cart mr-2"></i>
                    Add to Cart
            </button>
        </div>
    </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</section>

<style>
.product-card {
    transition: all 0.3s ease;
    border: 1px solid rgba(0, 0, 0, 0.1);
}

.product-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
}

.add-to-cart {
    transition: all 0.3s ease;
}

.add-to-cart:hover {
    transform: scale(1.05);
}

.line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

/* Custom scrollbar */
::-webkit-scrollbar {
    width: 8px;
}

::-webkit-scrollbar-track {
    background: #f1f1f1;
}

::-webkit-scrollbar-thumb {
    background: #888;
    border-radius: 4px;
}

::-webkit-scrollbar-thumb:hover {
    background: #555;
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
            
            // Show success message with animation
            const button = this;
            const originalText = button.innerHTML;
            button.innerHTML = '<i class="fas fa-check mr-2"></i>Added!';
            button.classList.add('bg-green-600');
            
            setTimeout(() => {
                button.innerHTML = originalText;
                button.classList.remove('bg-green-600');
            }, 2000);
            
            // Update cart count in header if it exists
            const cartCount = document.getElementById('cart-count');
            if (cartCount) {
                cartCount.textContent = cart.reduce((total, item) => total + item.quantity, 0);
                cartCount.classList.add('animate-bounce');
                setTimeout(() => cartCount.classList.remove('animate-bounce'), 1000);
            }
        });
    });
});
</script>