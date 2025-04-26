<div class="container">
  <div class="page-inner">
    <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row pt-2 pb-4">
      <div>
        <h3 class="fw-bold mb-3">Review & Ratings Management</h3>
      </div>
      <div class="ms-md-auto py-2 py-md-0">
        <button id="addReviewBtn" class="btn btn-primary btn-round">
          <i class="fas fa-plus"></i> Add Review
        </button>
      </div>
    </div>

    <!-- Reviews Table -->
    <div class="row">
      <div class="col-md-12">
        <div class="card card-round">
          <div class="card-header">
            <div class="card-head-row">
              <div class="card-title">Reviews List</div>
              <div class="card-tools">
                <input type="text" id="searchReview" class="form-control" placeholder="Search reviews...">
              </div>
            </div>
          </div>
          <div class="card-body">
            <div class="table-responsive">
              <table class="table table-hover" id="reviewsTable">
                <thead>
                  <tr>
                    <th>Review ID</th>
                    <th>Product</th>
                    <th>Customer</th>
                    <th>Rating</th>
                    <th>Review</th>
                    <th>Date</th>
                    <th>Status</th>
                    <th>Actions</th>
                  </tr>
                </thead>
                <tbody>
                  <!-- Reviews rows populated here -->
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Add/Edit Review Modal -->
<div class="modal fade" id="reviewModal" tabindex="-1" aria-labelledby="reviewModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="reviewModalLabel">Add Review</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form id="reviewForm">
          <input type="hidden" id="reviewId">
          <div class="form-group">
            <label for="productId">Product</label>
            <select class="form-control" id="productId" required>
              <!-- Products populated dynamically -->
            </select>
          </div>
          <div class="form-group">
            <label for="customerId">Customer</label>
            <select class="form-control" id="customerId" required>
              <!-- Customers populated dynamically -->
            </select>
          </div>
          <div class="form-group">
            <label for="rating">Rating</label>
            <select class="form-control" id="rating" required>
              <option value="1">1 Star</option>
              <option value="2">2 Stars</option>
              <option value="3">3 Stars</option>
              <option value="4">4 Stars</option>
              <option value="5">5 Stars</option>
            </select>
          </div>
          <div class="form-group">
            <label for="reviewText">Review</label>
            <textarea class="form-control" id="reviewText" rows="3" required></textarea>
          </div>
          <div class="form-group">
            <label for="reviewDate">Review Date</label>
            <input type="date" class="form-control" id="reviewDate" required>
          </div>
          <div class="form-group">
            <label for="reviewStatus">Status</label>
            <select class="form-control" id="reviewStatus" required>
              <option value="pending">Pending</option>
              <option value="approved">Approved</option>
              <option value="rejected">Rejected</option>
            </select>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary" id="saveReviewBtn">Save</button>
      </div>
    </div>
  </div>
</div>