<div class="container">
  <div class="page-inner">
    <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row pt-2 pb-4">
      <div>
        <h3 class="fw-bold mb-3">Customer Management</h3>
      </div>
      <div class="ms-md-auto py-2 py-md-0">
        <button id="addCustomerBtn" class="btn btn-primary btn-round">
          <i class="fas fa-plus"></i> Add Customer
        </button>
      </div>
    </div>

    <!-- Customer Table -->
    <div class="row">
      <div class="col-md-12">
        <div class="card card-round">
          <div class="card-header">
            <div class="card-head-row">
              <div class="card-title">Customer List</div>
              <div class="card-tools">
                <input type="text" id="searchCustomer" class="form-control" placeholder="Search customers...">
              </div>
            </div>
          </div>
          <div class="card-body">
            <div class="table-responsive">
              <table class="table table-hover" id="customerTable">
                <thead>
                  <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Address</th>
                    <th>Actions</th>
                  </tr>
                </thead>
                <tbody>
                  <!-- Customer rows will be populated here -->
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Add/Edit Customer Modal -->
<div class="modal fade" id="customerModal" tabindex="-1" aria-labelledby="customerModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="customerModalLabel">Add Customer</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form id="customerForm">
          <input type="hidden" id="customerId">
          <div class="form-group">
            <label for="customerName">Full Name</label>
            <input type="text" class="form-control" id="customerName" required>
          </div>
          <div class="form-group">
            <label for="customerEmail">Email</label>
            <input type="email" class="form-control" id="customerEmail" required>
          </div>
          <div class="form-group">
            <label for="customerPhone">Phone</label>
            <input type="tel" class="form-control" id="customerPhone" required>
          </div>
          <div class="form-group">
            <label for="customerAddress">Address</label>
            <textarea class="form-control" id="customerAddress" rows="3" required></textarea>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary" id="saveCustomerBtn">Save</button>
      </div>
    </div>
  </div>
</div>
