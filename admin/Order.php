

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

    <!-- Order Table -->
    <div class="row">
      <div class="col-md-12">
        <div class="card card-round">
          <div class="card-header">
            <div class="card-head-row">
              <div class="card-title">Order List</div>
              <div class="card-tools">
                <input type="text" id="searchOrder" class="form-control" placeholder="Search orders...">
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
                    <th>Date</th>
                    <th>Total</th>
                    <th>Status</th>
                    <th>Actions</th>
                  </tr>
                </thead>
                <tbody>
                  <!-- Order rows populated here -->
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
          <input type="hidden" id="orderId">
          
          <div class="row mb-3">
            <div class="col-md-6">
              <div class="form-group">
                <label for="customerSelect">Customer</label>
                <select class="form-control" id="customerSelect" required></select>
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
            <div class="table-responsive">
              <table class="table" id="orderItemsTable">
                <thead>
                  <tr>
                    <th>Product</th>
                    <th>Quantity</th>
                    <th>Price</th>
                    <th>Total</th>
                    <th></th>
                  </tr>
                </thead>
                <tbody id="orderItemsBody">
                  <!-- Order items dynamically added -->
                </tbody>
                <tfoot>
                  <tr>
                    <td colspan="3" class="text-end fw-bold">Order Total:</td>
                    <td id="orderTotal">$0.00</td>
                    <td></td>
                  </tr>
                </tfoot>
              </table>
            </div>
            <button type="button" class="btn btn-sm btn-secondary" id="addOrderItem">
              <i class="fas fa-plus"></i> Add Item
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

