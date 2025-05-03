<?php
// Product.php
require 'admin/include/db.php';

class Product {
    private $db;

    public function __construct() {
        $this->db = (new Database())->connect();
    }

    // Fetch all products
    public function getAllProducts() {
        $query = "SELECT * FROM products";
        $stmt = $this->db->prepare($query);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
// Remove the 'uploads/' prefix from the image path
// Fetch all products to display
$product = new Product();
$products = $product->getAllProducts();
?>
<!-- Featured Products Section -->
<section id="featured-products" class="container mx-auto my-12">
    <h2 class="text-3xl font-bold text-center mb-8">Featured Products</h2>
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-8">
    <?php
// Fetch all products
$products = $product->getAllProducts();

foreach ($products as $product) {
    // Remove the 'uploads/' prefix from the image path
    $imagePath = str_replace('uploads/', '', $product['image']);
    ?>
    <div class="bg-white p-6 rounded-lg shadow-lg hover:shadow-xl transition-shadow product-card">
        <img src="admin/uploads/<?php echo $imagePath; ?>" alt="<?php echo $product['name']; ?>" class="w-full h-48 object-cover rounded-md mb-4">
        <h3 class="text-lg font-semibold mb-2"><?php echo $product['name']; ?></h3>
        <p class="text-gray-500 mb-2"><?php echo $product['description']; ?></p>
        <div class="flex items-center justify-between">
            <span class="text-blue-500 font-bold">$<?php echo number_format($product['price'], 2); ?></span>
            <button
                class="px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition add-to-cart"
                data-name="<?php echo $product['name']; ?>"
                data-price="<?php echo $product['price']; ?>"
                data-image="<?php echo $imagePath; ?>">
                <span class="flex items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                        <path d="M3 1a1 1 0 000 2h1.22l.305 1.222a.997.997 0 00.01.042l1.358 5.43-.893.892C3.74 11.846 4.632 14 6.414 14H15a1 1 0 000-2H6.414l1-1H14a1 1 0 00.894-.553l3-6A1 1 0 0017 3H6.28l-.31-1.243A1 1 0 005 1H3zM16 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0zM6.5 18a1.5 1.5 0 100-3 1.5 1.5 0 000 3z" />
                    </svg>
                    Add to Cart
                </span>
            </button>
        </div>
    </div>
    <?php
}
?>
    </div>
</section>