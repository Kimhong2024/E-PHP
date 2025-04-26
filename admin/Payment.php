<div class="container">
  <div class="page-inner">
    <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row pt-2 pb-4">
      <div>
        <h3 class="fw-bold mb-3">Payment Management</h3>
      </div>
      <div class="ms-md-auto py-2 py-md-0">
        <button id="addPaymentBtn" class="btn btn-primary btn-round">
          <i class="fas fa-plus"></i> Add Payment
        </button>
      </div>
    </div>

    <!-- Payments Table -->
    <div class="row">
      <div class="col-md-12">
        <div class="card card-round">
          <div class="card-header">
            <div class="card-head-row">
              <div class="card-title">Payments List</div>
              <div class="card-tools">
                <input type="text" id="searchPayment" class="form-control" placeholder="Search payments...">
              </div>
            </div>
          </div>
          <div class="card-body">
            <div class="table-responsive">
              <table class="table table-hover" id="paymentsTable">
                <thead>
                  <tr>
                    <th>Payment ID</th>
                    <th>Order ID</th>
                    <th>Amount</th>
                    <th>Payment Method</th>
                    <th>Payment Date</th>
                    <th>Status</th>
                    <th>Actions</th>
                  </tr>
                </thead>
                <tbody>
                  <!-- Payments rows populated here -->
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Add/Edit Payment Modal -->
<div class="modal fade" id="paymentModal" tabindex="-1" aria-labelledby="paymentModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="paymentModalLabel">Add Payment</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form id="paymentForm">
          <input type="hidden" id="paymentId">
          <div class="form-group">
            <label for="orderId">Order ID</label>
            <input type="text" class="form-control" id="orderId" required>
          </div>
          <div class="form-group">
            <label for="paymentAmount">Amount</label>
            <input type="number" class="form-control" id="paymentAmount" step="0.01" required>
          </div>
          <div class="form-group">
            <label for="paymentMethod">Payment Method</label>
            <select class="form-control" id="paymentMethod" required>
              <option value="credit_card">Credit Card</option>
              <option value="paypal">PayPal</option>
              <option value="bank_transfer">Bank Transfer</option>
              <option value="cash">Cash</option>
            </select>
          </div>
          <div class="form-group">
            <label for="paymentDate">Payment Date</label>
            <input type="date" class="form-control" id="paymentDate" required>
          </div>
          <div class="form-group">
            <label for="paymentStatus">Status</label>
            <select class="form-control" id="paymentStatus" required>
              <option value="pending">Pending</option>
              <option value="completed">Completed</option>
              <option value="failed">Failed</option>
            </select>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary" id="savePaymentBtn">Save</button>
      </div>
    </div>
  </div>
</div>
