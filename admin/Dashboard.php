<?php
// Include database connection
require_once 'include/db.php';
$database = new Database();
$db = $database->connect();

// Get statistics from database
try {
    // Get total customers count
    $stmt = $db->query("SELECT COUNT(*) as total FROM customers");
    $customersCount = $stmt->fetch()['total'];
    
    // Get total orders count
    $stmt = $db->query("SELECT COUNT(*) as total FROM orders");
    $ordersCount = $stmt->fetch()['total'];
    
    // Get total products count
    $stmt = $db->query("SELECT COUNT(*) as total FROM products");
    $productsCount = $stmt->fetch()['total'];
    
    // Get total categories count
    $stmt = $db->query("SELECT COUNT(*) as total FROM categories");
    $categoriesCount = $stmt->fetch()['total'];
    
    // Get total sales amount
    $stmt = $db->query("SELECT SUM(total_amount) as total FROM orders WHERE status = 'completed'");
    $totalSales = $stmt->fetch()['total'] ?? 0;
    
    // Get recent orders
    $stmt = $db->query("SELECT o.*, c.name as customer_name 
                        FROM orders o 
                        JOIN customers c ON o.customer_id = c.id 
                        ORDER BY o.created_at DESC LIMIT 5");
    $recentOrders = $stmt->fetchAll();
    
    // Get top selling products
    $stmt = $db->query("SELECT p.*, COUNT(oi.id) as order_count 
                        FROM products p 
                        JOIN order_items oi ON p.id = oi.product_id 
                        GROUP BY p.id 
                        ORDER BY order_count DESC 
                        LIMIT 5");
    $topProducts = $stmt->fetchAll();
    
    // Get sales by month for chart
    $stmt = $db->query("SELECT DATE_FORMAT(created_at, '%Y-%m') as month, 
                        SUM(total_amount) as total 
                        FROM orders 
                        WHERE status = 'completed' 
                        GROUP BY month 
                        ORDER BY month DESC 
                        LIMIT 6");
    $salesByMonth = $stmt->fetchAll();
    
    // Reverse the array for chronological order in chart
    $salesByMonth = array_reverse($salesByMonth);
    
    // Prepare data for chart
    $months = [];
    $sales = [];
    foreach ($salesByMonth as $sale) {
        $months[] = date('M Y', strtotime($sale['month']));
        $sales[] = $sale['total'];
    }
    
} catch (PDOException $e) {
    // Log error and set default values
    error_log("Dashboard error: " . $e->getMessage());
    $customersCount = 0;
    $ordersCount = 0;
    $productsCount = 0;
    $categoriesCount = 0;
    $totalSales = 0;
    $recentOrders = [];
    $topProducts = [];
    $months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'];
    $sales = [0, 0, 0, 0, 0, 0];
}
?>

<div class="container">
  <div class="page-inner">
    <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row pt-2 pb-4">
      <div>
        <h3 class="fw-bold mb-3">Dashboard</h3>
      </div>
      <div class="ms-md-auto py-2 py-md-0">
        <a href="index.php?p=Order" class="btn btn-label-info btn-round me-2">View Orders</a>
        <a href="index.php?p=Customer" class="btn btn-primary btn-round">Add Customer</a>
      </div>
    </div>
    
    <!-- Statistics Cards -->
    <div class="row">
      <div class="col-sm-6 col-md-3">
        <div class="card card-stats card-round">
          <div class="card-body">
            <div class="row align-items-center">
              <div class="col-icon">
                <div class="icon-big text-center icon-primary bubble-shadow-small">
                  <i class="fas fa-users"></i>
                </div>
              </div>
              <div class="col col-stats ms-3 ms-sm-0">
                <div class="numbers">
                  <p class="card-category">Customers</p>
                  <h4 class="card-title"><?php echo number_format($customersCount); ?></h4>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="col-sm-6 col-md-3">
        <div class="card card-stats card-round">
          <div class="card-body">
            <div class="row align-items-center">
              <div class="col-icon">
                <div class="icon-big text-center icon-info bubble-shadow-small">
                  <i class="fas fa-box"></i>
                </div>
              </div>
              <div class="col col-stats ms-3 ms-sm-0">
                <div class="numbers">
                  <p class="card-category">Products</p>
                  <h4 class="card-title"><?php echo number_format($productsCount); ?></h4>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="col-sm-6 col-md-3">
        <div class="card card-stats card-round">
          <div class="card-body">
            <div class="row align-items-center">
              <div class="col-icon">
                <div class="icon-big text-center icon-success bubble-shadow-small">
                  <i class="fas fa-dollar-sign"></i>
                </div>
              </div>
              <div class="col col-stats ms-3 ms-sm-0">
                <div class="numbers">
                  <p class="card-category">Sales</p>
                  <h4 class="card-title">$<?php echo number_format($totalSales, 2); ?></h4>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="col-sm-6 col-md-3">
        <div class="card card-stats card-round">
          <div class="card-body">
            <div class="row align-items-center">
              <div class="col-icon">
                <div class="icon-big text-center icon-secondary bubble-shadow-small">
                  <i class="far fa-check-circle"></i>
                </div>
              </div>
              <div class="col col-stats ms-3 ms-sm-0">
                <div class="numbers">
                  <p class="card-category">Orders</p>
                  <h4 class="card-title"><?php echo number_format($ordersCount); ?></h4>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    
    <!-- Charts Row -->
    <div class="row">
      <div class="col-md-8">
        <div class="card card-round">
          <div class="card-header">
            <div class="card-head-row">
              <div class="card-title">Sales Statistics</div>
              <div class="card-tools">
                <a href="#" class="btn btn-label-success btn-round btn-sm me-2">
                  <span class="btn-label">
                    <i class="fa fa-download"></i>
                  </span>
                  Export
                </a>
                <a href="#" class="btn btn-label-info btn-round btn-sm">
                  <span class="btn-label">
                    <i class="fa fa-print"></i>
                  </span>
                  Print
                </a>
              </div>
            </div>
          </div>
          <div class="card-body">
            <div class="chart-container" style="min-height: 375px">
              <canvas id="salesChart"></canvas>
            </div>
          </div>
        </div>
      </div>
      <div class="col-md-4">
        <div class="card card-primary card-round">
          <div class="card-header">
            <div class="card-head-row">
              <div class="card-title">Categories</div>
              <div class="card-tools">
                <a href="index.php?p=Category" class="btn btn-sm btn-label-light">
                  View All
                </a>
              </div>
            </div>
          </div>
          <div class="card-body pb-0">
            <div class="mb-4 mt-2">
              <h1><?php echo number_format($categoriesCount); ?></h1>
              <p>Total Categories</p>
            </div>
            <div class="pull-in">
              <canvas id="categoriesChart"></canvas>
            </div>
          </div>
        </div>
        <div class="card card-round">
          <div class="card-body pb-0">
            <div class="h1 fw-bold float-end text-primary"><?php echo $ordersCount > 0 ? round(($ordersCount / max(1, $customersCount)) * 100) : 0; ?>%</div>
            <h2 class="mb-2"><?php echo number_format($ordersCount); ?></h2>
            <p class="text-muted">Total Orders</p>
            <div class="pull-in sparkline-fix">
              <div id="lineChart"></div>
            </div>
          </div>
        </div>
      </div>
    </div>
    
    <!-- Recent Orders and Top Products -->
    <div class="row">
      <div class="col-md-8">
        <div class="card card-round">
          <div class="card-header">
            <div class="card-head-row card-tools-still-right">
              <div class="card-title">Recent Orders</div>
              <div class="card-tools">
                <a href="index.php?p=Order" class="btn btn-sm btn-label-light">
                  View All
                </a>
              </div>
            </div>
          </div>
          <div class="card-body p-0">
            <div class="table-responsive">
              <table class="table align-items-center mb-0">
                <thead class="thead-light">
                  <tr>
                    <th scope="col">Order ID</th>
                    <th scope="col">Customer</th>
                    <th scope="col" class="text-end">Date</th>
                    <th scope="col" class="text-end">Amount</th>
                    <th scope="col" class="text-end">Status</th>
                  </tr>
                </thead>
                <tbody>
                  <?php if (empty($recentOrders)): ?>
                    <tr>
                      <td colspan="5" class="text-center">No orders found</td>
                    </tr>
                  <?php else: ?>
                    <?php foreach ($recentOrders as $order): ?>
                      <tr>
                        <th scope="row">
                          <a href="index.php?p=Order&id=<?php echo $order['id']; ?>" class="text-primary">
                            #<?php echo $order['id']; ?>
                          </a>
                        </th>
                        <td><?php echo htmlspecialchars($order['customer_name']); ?></td>
                        <td class="text-end"><?php echo date('M d, Y', strtotime($order['created_at'])); ?></td>
                        <td class="text-end">$<?php echo number_format($order['total_amount'], 2); ?></td>
                        <td class="text-end">
                          <?php 
                          $statusClass = 'secondary';
                          if ($order['status'] == 'completed') $statusClass = 'success';
                          if ($order['status'] == 'pending') $statusClass = 'warning';
                          if ($order['status'] == 'cancelled') $statusClass = 'danger';
                          ?>
                          <span class="badge bg-<?php echo $statusClass; ?>"><?php echo ucfirst($order['status']); ?></span>
                        </td>
                      </tr>
                    <?php endforeach; ?>
                  <?php endif; ?>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
      <div class="col-md-4">
        <div class="card card-round">
          <div class="card-header">
            <div class="card-head-row card-tools-still-right">
              <div class="card-title">Top Products</div>
              <div class="card-tools">
                <a href="index.php?p=Product" class="btn btn-sm btn-label-light">
                  View All
                </a>
              </div>
            </div>
          </div>
          <div class="card-body">
            <div class="card-list py-4">
              <?php if (empty($topProducts)): ?>
                <p class="text-center">No products found</p>
              <?php else: ?>
                <?php foreach ($topProducts as $product): ?>
                  <div class="item-list">
                    <div class="avatar">
                      <?php if (!empty($product['image'])): ?>
                        <img src="<?php echo htmlspecialchars($product['image']); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>" class="avatar-img rounded-circle" />
                      <?php else: ?>
                        <span class="avatar-title rounded-circle border border-white bg-primary">
                          <?php echo substr($product['name'], 0, 1); ?>
                        </span>
                      <?php endif; ?>
                    </div>
                    <div class="info-user ms-3">
                      <div class="username"><?php echo htmlspecialchars($product['name']); ?></div>
                      <div class="status"><?php echo $product['order_count']; ?> orders</div>
                    </div>
                    <div class="ms-auto">
                      <span class="badge bg-primary">$<?php echo number_format($product['price'], 2); ?></span>
                    </div>
                  </div>
                <?php endforeach; ?>
              <?php endif; ?>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Chart Initialization Scripts -->
<script>
  document.addEventListener('DOMContentLoaded', function() {
    // Sales Chart
    var salesCtx = document.getElementById('salesChart').getContext('2d');
    var salesChart = new Chart(salesCtx, {
      type: 'line',
      data: {
        labels: <?php echo json_encode($months); ?>,
        datasets: [{
          label: 'Monthly Sales',
          data: <?php echo json_encode($sales); ?>,
          backgroundColor: 'rgba(0, 123, 255, 0.1)',
          borderColor: 'rgba(0, 123, 255, 1)',
          borderWidth: 2,
          pointBackgroundColor: 'rgba(0, 123, 255, 1)',
          pointBorderColor: '#fff',
          pointBorderWidth: 2,
          pointRadius: 4,
          tension: 0.3
        }]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
          legend: {
            display: false
          }
        },
        scales: {
          y: {
            beginAtZero: true,
            grid: {
              drawBorder: false
            }
          },
          x: {
            grid: {
              display: false
            }
          }
        }
      }
    });
    
    // Categories Chart (Doughnut)
    var categoriesCtx = document.getElementById('categoriesChart').getContext('2d');
    var categoriesChart = new Chart(categoriesCtx, {
      type: 'doughnut',
      data: {
        labels: ['Categories'],
        datasets: [{
          data: [<?php echo $categoriesCount; ?>, 100 - <?php echo $categoriesCount; ?>],
          backgroundColor: [
            'rgba(0, 123, 255, 0.8)',
            'rgba(0, 0, 0, 0.05)'
          ],
          borderWidth: 0
        }]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        cutout: '80%',
        plugins: {
          legend: {
            display: false
          }
        }
      }
    });
    
    // Line Chart (Sparkline)
    var lineCtx = document.getElementById('lineChart');
    var lineChart = new Chart(lineCtx, {
      type: 'line',
      data: {
        labels: <?php echo json_encode($months); ?>,
        datasets: [{
          data: <?php echo json_encode($sales); ?>,
          borderColor: 'rgba(0, 123, 255, 1)',
          borderWidth: 2,
          pointRadius: 0,
          tension: 0.3
        }]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
          legend: {
            display: false
          }
        },
        scales: {
          y: {
            display: false
          },
          x: {
            display: false
          }
        }
      }
    });
  });
</script>