<div class="container">
  <div class="page-inner">
    <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row pt-2 pb-4">
      <div>
        <h3 class="fw-bold mb-3">Coupon Management</h3>
      </div>
      <div class="ms-md-auto py-2 py-md-0">
        <button id="addCouponBtn" class="btn btn-primary btn-round">
          <i class="fas fa-plus"></i> Add Coupon
        </button>
      </div>
    </div>

    <!-- Coupons Table -->
    <div class="row">
      <div class="col-md-12">
        <div class="card card-round">
          <div class="card-header">
            <div class="card-head-row">
              <div class="card-title">Coupons List</div>
              <div class="card-tools">
                <input type="text" id="searchCoupon" class="form-control" placeholder="Search coupons...">
              </div>
            </div>
          </div>
          <div class="card-body">
            <div class="table-responsive">
              <table class="table table-hover" id="couponsTable">
                <thead>
                  <tr>
                    <th>Code</th>
                    <th>Discount</th>
                    <th>Type</th>
                    <th>Start Date</th>
                    <th>End Date</th>
                    <th>Status</th>
                    <th>Actions</th>
                  </tr>
                </thead>
                <tbody>
                  <!-- Coupons rows populated here -->
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Add/Edit Coupon Modal -->
<div class="modal fade" id="couponModal" tabindex="-1" aria-labelledby="couponModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="couponModalLabel">Add Coupon</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form id="couponForm">
          <input type="hidden" id="couponId">
          <div class="form-group">
            <label for="couponCode">Coupon Code</label>
            <input type="text" class="form-control" id="couponCode" required>
          </div>
          <div class="form-group">
            <label for="discountValue">Discount Value</label>
            <input type="number" class="form-control" id="discountValue" required>
          </div>
          <div class="form-group">
            <label for="discountType">Discount Type</label>
            <select class="form-control" id="discountType" required>
              <option value="percentage">Percentage</option>
              <option value="fixed">Fixed Amount</option>
            </select>
          </div>
          <div class="form-group">
            <label for="startDate">Start Date</label>
            <input type="date" class="form-control" id="startDate" required>
          </div>
          <div class="form-group">
            <label for="endDate">End Date</label>
            <input type="date" class="form-control" id="endDate" required>
          </div>
          <div class="form-group">
            <label for="couponStatus">Status</label>
            <select class="form-control" id="couponStatus" required>
              <option value="active">Active</option>
              <option value="inactive">Inactive</option>
            </select>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary" id="saveCouponBtn">Save</button>
      </div>
    </div>
  </div>
</div>
