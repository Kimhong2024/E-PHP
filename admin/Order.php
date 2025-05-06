<?php
require_once 'include/db.php';

class Order {
    private $db;
    
    public function __construct($db) {
        $this->db = $db;
    }
    
    public function handleFormSubmission() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
        $customer_id = (int)$_POST['customer_id'];
        $status = trim($_POST['status']);
        $order_items = json_decode($_POST['order_items'], true);
        
        // Validate input
        if (empty($customer_id)) {
            return ['success' => false, 'message' => 'Customer is required'];
        }
        
        if (empty($order_items) || !is_array($order_items)) {
            return ['success' => false, 'message' => 'Order must contain at least one item'];
        }

        try {
            $this->db->beginTransaction();

            // Add new order
            $stmt = $this->db->prepare("INSERT INTO orders (customer_id, status, order_date) VALUES (?, ?, NOW())");
            $stmt->execute([$customer_id, $status]);
            $order_id = $this->db->lastInsertId();

                // Add order items
            foreach ($order_items as $item) {
                $product_id = (int)$item['product_id'];
                $quantity = (int)$item['quantity'];
                    $price = floatval($item['price']);
                
                $stmt = $this->db->prepare("INSERT INTO order_items (order_id, product_id, quantity, price) VALUES (?, ?, ?, ?)");
                $stmt->execute([$order_id, $product_id, $quantity, $price]);

                    // Update product stock (optional)
                    // $stmt = $this->db->prepare("UPDATE products SET stock = stock - ? WHERE id = ?");
                    // $stmt->execute([$quantity, $product_id]);
            }

            $this->db->commit();
                return ['success' => true, 'message' => 'Order added successfully'];
        } catch (PDOException $e) {
            $this->db->rollBack();
            return ['success' => false, 'message' => 'Database error: ' . $e->getMessage()];
        }
    }
        return null;
    }
    
    public function getCustomers() {
        try {
            $stmt = $this->db->query("SELECT id, name FROM customers WHERE status = 'active' ORDER BY name");
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error fetching customers: " . $e->getMessage());
            return [];
        }
    }
    
    public function getProducts() {
        try {
            // First check if the products table exists
            $tableCheck = $this->db->query("SHOW TABLES LIKE 'products'");
            if ($tableCheck->rowCount() == 0) {
                error_log("Products table does not exist");
                return [];
            }

            // Check database connection
            error_log("Database connection status: " . ($this->db ? "Connected" : "Not connected"));

            // Get all products
            $query = "SELECT id, name, price, stock FROM products ORDER BY name";
            error_log("Executing query: " . $query);
            
            $stmt = $this->db->query($query);
            $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            // Log the number of products found
            error_log("Number of products found: " . count($products));
            
            // Log the first product (if any) for debugging
            if (!empty($products)) {
                error_log("First product sample: " . print_r($products[0], true));
            } else {
                error_log("No products found in the database");
            }
            
            return $products;
        } catch (PDOException $e) {
            error_log("Error in getProducts: " . $e->getMessage());
            error_log("SQL State: " . $e->getCode());
            return [];
        }
    }
    
    public function getAllOrders($search = '') {
        try {
            $query = "SELECT o.id, o.status, o.order_date, 
                             c.name AS customer_name, 
                             GROUP_CONCAT(
                                 CONCAT(
                                     COALESCE(p.name, '[Deleted Product]'), 
                                     ' (', oi.quantity, ' × $', COALESCE(oi.price, 0), ')'
                                 ) SEPARATOR ', '
                             ) AS products,
                             SUM(oi.quantity * oi.price) AS total_amount
                      FROM orders o 
                      JOIN customers c ON o.customer_id = c.id
                      LEFT JOIN order_items oi ON o.id = oi.order_id
                      LEFT JOIN products p ON oi.product_id = p.id";
            
            $params = [];
            if (!empty($search)) {
                $query .= " WHERE o.id LIKE ? OR c.name LIKE ? OR p.name LIKE ?";
                $searchTerm = "%$search%";
                $params = [$searchTerm, $searchTerm, $searchTerm];
            }

            $query .= " GROUP BY o.id ORDER BY o.order_date DESC";
            $stmt = $this->db->prepare($query);
            $stmt->execute($params);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error fetching orders: " . $e->getMessage());
            return [];
        }
    }
}

// Initialize database connection
$db = (new Database())->connect();
$orderManager = new Order($db);

// Handle form submission
$formResult = $orderManager->handleFormSubmission();
if (isset($formResult['success']) && $formResult['success']) {
    $_SESSION['success_message'] = $formResult['message'];
    header("Location: index.php?p=Order");
    exit;
} elseif (isset($formResult['success']) && !$formResult['success']) {
    $_SESSION['error_message'] = $formResult['message'];
}

// Get data for display
$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$orders = $orderManager->getAllOrders($search);
$customers = $orderManager->getCustomers();
$products = $orderManager->getProducts();
?>

<div class="container">
  <div class="page-inner">
    <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row pt-2 pb-4">
      <div>
        <h3 class="fw-bold mb-3">Order Management</h3>
      </div>
      <div class="ms-md-auto py-2 py-md-0">
        <button id="addOrderBtn" class="btn btn-primary btn-round">
          <i class="fas fa-plus"></i> Create Order
        </button>
      </div>
    </div>

    <?php if (isset($_SESSION['success_message'])): ?>
      <div class="alert alert-success alert-dismissible fade show" role="alert">
        <?= htmlspecialchars($_SESSION['success_message']) ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        <?php unset($_SESSION['success_message']); ?>
      </div>
    <?php endif; ?>

    <?php if (isset($_SESSION['error_message'])): ?>
      <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <?= htmlspecialchars($_SESSION['error_message']) ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        <?php unset($_SESSION['error_message']); ?>
      </div>
    <?php endif; ?>

    <!-- Order Table -->
    <div class="row">
      <div class="col-md-12">
        <div class="card card-round">
          <div class="card-header">
            <div class="card-head-row">
              <div class="card-title">Order List</div>
              <div class="card-tools">
                <form method="GET" action="index.php" class="d-flex">
                  <input type="hidden" name="p" value="Order">
                  <input type="text" name="search" class="form-control" placeholder="Search orders..." value="<?= htmlspecialchars($search) ?>">
                  <button type="submit" class="btn btn-primary ms-2">Search</button>
                </form>
              </div>
            </div>
          </div>
          <div class="card-body">
            <div class="table-responsive">
              <table class="table table-hover" id="orderTable">
                <thead>
                  <tr>
                    <th>Order ID</th>
                    <th>Customer</th>
                    <th>Products</th>
                    <th>Date</th>
                    <th>Total</th>
                    <th>Status</th>
                    <th>Actions</th>
                  </tr>
                </thead>
                <tbody>
                  <?php foreach ($orders as $order): ?>
                    <tr>
                      <td><?= htmlspecialchars($order['id']) ?></td>
                      <td><?= htmlspecialchars($order['customer_name']) ?></td>
                      <td><?= htmlspecialchars($order['products'] ?? 'No products') ?></td>
                      <td><?= date('M d, Y', strtotime($order['order_date'])) ?></td>
                      <td>$<?= number_format($order['total_amount'] ?? 0, 2) ?></td>
                      <td>
                        <span class="badge bg-<?= 
                          $order['status'] == 'completed' ? 'success' : 
                          ($order['status'] == 'processing' ? 'warning' : 
                          ($order['status'] == 'cancelled' ? 'danger' : 'info')) 
                        ?>">
                          <?= ucfirst($order['status']) ?>
                        </span>
                      </td>
                      <td>
                        <button class="btn btn-sm btn-info view-order" data-id="<?= $order['id'] ?>">
                          <i class="fas fa-eye"></i>
                        </button>
                        <button class="btn btn-sm btn-danger delete-order" data-id="<?= $order['id'] ?>">
                          <i class="fas fa-trash"></i>
                        </button>
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

<!-- Order Modal -->
<div class="modal fade" id="orderModal" tabindex="-1" aria-labelledby="orderModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="orderModalLabel">Create New Order</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form id="orderForm">
          <div class="row mb-3">
            <div class="col-md-6">
              <div class="form-group">
                <label for="customerSelect">Customer</label>
                <select class="form-control" id="customerSelect" required>
                  <option value="">Select Customer</option>
                  <?php foreach ($customers as $customer): ?>
                    <option value="<?= $customer['id'] ?>">
                      <?= htmlspecialchars($customer['name']) ?>
                    </option>
                  <?php endforeach; ?>
                </select>
                <div class="invalid-feedback">Please select a customer</div>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label for="orderStatus">Order Status</label>
                <select class="form-control" id="orderStatus" required>
                  <option value="pending">Pending</option>
                  <option value="processing">Processing</option>
                  <option value="completed">Completed</option>
                  <option value="cancelled">Cancelled</option>
                </select>
              </div>
            </div>
          </div>

          <div class="order-items mb-4">
            <h6>Order Items</h6>
            <div class="alert alert-info" id="noProductsAlert" style="display: <?= empty($products) ? 'block' : 'none' ?>">
              No active products available. Please add products first.
            </div>
            <div class="table-responsive">
              <table class="table table-bordered" id="orderItemsTable">
                <thead class="table-light">
                  <tr>
                    <th style="width: 40%">Product</th>
                    <th style="width: 15%">Quantity</th>
                    <th style="width: 15%">Price</th>
                    <th style="width: 15%">Total</th>
                    <th style="width: 15%">Actions</th>
                  </tr>
                </thead>
                <tbody id="orderItemsBody">
                  <!-- Order items dynamically added -->
                </tbody>
                <tfoot class="table-light">
                  <tr>
                    <td colspan="3" class="text-end fw-bold">Order Total:</td>
                    <td id="orderTotal" class="fw-bold">$0.00</td>
                    <td></td>
                  </tr>
                </tfoot>
              </table>
            </div>
            <button type="button" class="btn btn-primary" id="addOrderItem" <?= empty($products) ? 'disabled' : '' ?>>
              <i class="fas fa-plus"></i> Add Product
            </button>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary" id="saveOrderBtn">Save Order</button>
      </div>
    </div>
  </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Confirm Delete</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        Are you sure you want to delete this order? This action cannot be undone.
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        <button type="button" class="btn btn-danger" id="confirmDelete">Delete</button>
      </div>
    </div>
  </div>
</div>

<script>
$(document).ready(function() {
  // Initialize DataTable
  $('#orderTable').DataTable({
    "order": [[0, "desc"]],
    "pageLength": 10,
    "language": {
      "search": "Search orders:",
      "lengthMenu": "Show _MENU_ orders per page",
      "info": "Showing _START_ to _END_ of _TOTAL_ orders",
      "infoEmpty": "No orders found",
      "infoFiltered": "(filtered from _MAX_ total orders)"
    }
  });

  // Show modal when add button is clicked
  $('#addOrderBtn').click(function() {
    $('#orderModal').modal('show');
    $('#orderItemsBody').empty();
    $('#customerSelect').val('').removeClass('is-invalid');
    $('#orderStatus').val('pending');
    updateOrderTotal();
  });

  // Add order item
  $('#addOrderItem').click(function() {
    try {
        console.log("Add Order Item button clicked");
        
        // Get products from PHP
        const products = <?php 
            $products = $orderManager->getProducts();
            echo json_encode($products, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP);
        ?>;
        
        console.log("Products data received:", products);
        
        const productSelect = $('<select class="form-control product-select" required>');
        productSelect.append('<option value="">Select Product</option>');
        
        if (Array.isArray(products) && products.length > 0) {
            console.log("Processing " + products.length + " products");
            
            products.forEach(function(product, index) {
                console.log("Processing product " + index + ":", product);
                
                if (product && product.id && product.name) {
                    const stockInfo = product.stock > 0 ? `(Stock: ${product.stock})` : '(Out of Stock)';
                    const displayPrice = product.price ? `$${parseFloat(product.price).toFixed(2)}` : '$0.00';
                    
                    const option = $('<option>')
                        .val(product.id)
                        .attr('data-price', product.price || 0)
                        .attr('data-stock', product.stock || 0)
                        .text(`${product.name} - ${displayPrice} ${stockInfo}`);
                    
                    productSelect.append(option);
                } else {
                    console.warn("Invalid product data:", product);
                }
            });
        } else {
            console.warn("No products available or invalid products data");
            productSelect.append('<option value="" disabled>No products available</option>');
        }

        const row = $('<tr>').append(
            $('<td>').append(productSelect),
            $('<td>').append('<input type="number" class="form-control quantity" min="1" value="1" required>'),
            $('<td>').append('<span class="price">$0.00</span>'),
            $('<td>').append('<span class="total">$0.00</span>'),
            $('<td>').append('<button type="button" class="btn btn-sm btn-danger remove-item"><i class="fas fa-trash"></i></button>')
        );

        $('#orderItemsBody').append(row);
        updateOrderTotal();
        
        console.log("Order item added successfully");
    } catch (e) {
        console.error("Error in addOrderItem:", e);
        alert("Error adding product: " + e.message);
    }
  });

  // Remove order item
  $(document).on('click', '.remove-item', function() {
    $(this).closest('tr').remove();
    updateOrderTotal();
  });

  // Update item price and total when product changes
  $(document).on('change', '.product-select', function() {
    const row = $(this).closest('tr');
    const selectedOption = $(this).find('option:selected');
    const price = parseFloat(selectedOption.data('price')) || 0;
    const stock = parseInt(selectedOption.data('stock')) || 0;
    const quantityInput = row.find('.quantity');
    let quantity = parseInt(quantityInput.val()) || 0;
    
    // Update price display
    row.find('.price').text('$' + price.toFixed(2));
    
    // Update max quantity based on stock
    quantityInput.attr('max', stock);
    
    // If current quantity exceeds stock, set it to stock
    if (quantity > stock) {
      quantity = stock;
      quantityInput.val(quantity);
    }

    // Calculate and update total
    const total = price * quantity;
    row.find('.total').text('$' + total.toFixed(2));
    updateOrderTotal();
  });

  // Update total when quantity changes
  $(document).on('change', '.quantity', function() {
    const row = $(this).closest('tr');
    const selectedOption = row.find('.product-select option:selected');
    const price = parseFloat(selectedOption.data('price')) || 0;
    const stock = parseInt(selectedOption.data('stock')) || 0;
    const quantity = parseInt($(this).val()) || 0;
    
    // Validate quantity against stock
    if (quantity > stock) {
      alert(`Quantity cannot exceed available stock (${stock})`);
      $(this).val(stock);
      return;
    }

    // Calculate and update total
    const total = price * quantity;
    row.find('.total').text('$' + total.toFixed(2));
    updateOrderTotal();
  });

  // Update order total
  function updateOrderTotal() {
    let orderTotal = 0;
    $('.total').each(function() {
      orderTotal += parseFloat($(this).text().replace('$', '')) || 0;
    });
    $('#orderTotal').text('$' + orderTotal.toFixed(2));
  }

  // Save order
  $('#saveOrderBtn').click(function() {
    const customerId = $('#customerSelect').val();
    const status = $('#orderStatus').val();
    const items = [];
    let hasErrors = false;

    // Validate customer selection
    if (!customerId) {
      $('#customerSelect').addClass('is-invalid');
      hasErrors = true;
    } else {
      $('#customerSelect').removeClass('is-invalid');
    }

    // Validate order items
    $('#orderItemsBody tr').each(function() {
      const productSelect = $(this).find('.product-select');
      const quantityInput = $(this).find('.quantity');
      const productId = productSelect.val();
      const quantity = quantityInput.val();
      const price = parseFloat($(this).find('.price').text().replace('$', '')) || 0;
      
      // Validate product selection
      if (!productId) {
        productSelect.addClass('is-invalid');
        hasErrors = true;
      } else {
        productSelect.removeClass('is-invalid');
      }

      // Validate quantity
      if (!quantity || quantity <= 0) {
        quantityInput.addClass('is-invalid');
        hasErrors = true;
      } else {
        quantityInput.removeClass('is-invalid');
      }

      if (productId && quantity) {
        items.push({
          product_id: productId,
          quantity: quantity,
          price: price
        });
      }
    });

    // Check for errors
    if (hasErrors) {
      alert('Please fix all errors before submitting');
      return;
    }

    // Check for at least one item
    if (items.length === 0) {
      alert('Please add at least one item to the order');
      return;
    }

    // Show loading state
    const saveBtn = $(this);
    saveBtn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Saving...');

    // Submit order via AJAX
    $.ajax({
      url: 'index.php?p=Order',
      method: 'POST',
      data: {
        action: 'add',
        customer_id: customerId,
        status: status,
        order_items: JSON.stringify(items)
      },
      success: function(response) {
        if (response.success) {
          window.location.reload();
        } else {
          alert(response.message || 'An error occurred while saving the order');
          saveBtn.prop('disabled', false).html('Save Order');
        }
      },
      error: function(xhr, status, error) {
        alert('An error occurred while saving the order: ' + error);
        saveBtn.prop('disabled', false).html('Save Order');
      }
    });
  });

  // Delete order functionality
  let orderIdToDelete = null;
  
  $(document).on('click', '.delete-order', function() {
    orderIdToDelete = $(this).data('id');
    $('#deleteModal').modal('show');
  });

  $('#confirmDelete').click(function() {
    if (!orderIdToDelete) return;
    
    const deleteBtn = $(this);
    deleteBtn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Deleting...');
    
    $.ajax({
      url: 'index.php?p=Order',
      method: 'POST',
      data: {
        action: 'delete',
        order_id: orderIdToDelete
      },
      success: function(response) {
        if (response && response.success) {
          window.location.reload();
        } else {
          alert(response?.message || 'Failed to delete order');
          deleteBtn.prop('disabled', false).html('Delete');
          $('#deleteModal').modal('hide');
        }
      },
      error: function() {
        alert('An error occurred while deleting the order');
        deleteBtn.prop('disabled', false).html('Delete');
        $('#deleteModal').modal('hide');
      }
    });
  });
});
</script>