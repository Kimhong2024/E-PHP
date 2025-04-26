<div class="container">
  <div class="page-inner">
    <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row pt-2 pb-4">
      <div>
        <h3 class="fw-bold mb-3">Sales Reports</h3>
      </div>
      <div class="ms-md-auto py-2 py-md-0">
        <button id="generateReportBtn" class="btn btn-primary btn-round">
          <i class="fas fa-chart-line"></i> Generate Report
        </button>
      </div>
    </div>

    <!-- Filters -->
    <div class="row mb-4">
      <div class="col-md-3">
        <div class="form-group">
          <label for="reportType">Report Type</label>
          <select class="form-control" id="reportType">
            <option value="daily">Daily</option>
            <option value="weekly">Weekly</option>
            <option value="monthly">Monthly</option>
            <option value="yearly">Yearly</option>
            <option value="custom">Custom Date Range</option>
          </select>
        </div>
      </div>
      <div class="col-md-3">
        <div class="form-group">
          <label for="startDate">Start Date</label>
          <input type="date" class="form-control" id="startDate">
        </div>
      </div>
      <div class="col-md-3">
        <div class="form-group">
          <label for="endDate">End Date</label>
          <input type="date" class="form-control" id="endDate">
        </div>
      </div>
      <div class="col-md-3">
        <div class="form-group">
          <label for="groupBy">Group By</label>
          <select class="form-control" id="groupBy">
            <option value="day">Day</option>
            <option value="week">Week</option>
            <option value="month">Month</option>
            <option value="year">Year</option>
          </select>
        </div>
      </div>
    </div>

    <!-- Sales Report -->
    <div class="row">
      <div class="col-md-12">
        <div class="card card-round">
          <div class="card-header">
            <div class="card-head-row">
              <div class="card-title">Sales Report</div>
              <div class="card-tools">
                <button class="btn btn-label-success btn-round btn-sm me-2" id="exportReportBtn">
                  <span class="btn-label">
                    <i class="fa fa-file-export"></i>
                  </span>
                  Export
                </button>
                <button class="btn btn-label-info btn-round btn-sm" id="printReportBtn">
                  <span class="btn-label">
                    <i class="fa fa-print"></i>
                  </span>
                  Print
                </button>
              </div>
            </div>
          </div>
          <div class="card-body">
            <div class="chart-container" style="min-height: 375px">
              <canvas id="salesChart"></canvas>
            </div>
            <div class="table-responsive mt-4">
              <table class="table table-hover" id="salesTable">
                <thead>
                  <tr>
                    <th>Date</th>
                    <th>Total Sales</th>
                    <th>Total Orders</th>
                    <th>Average Order Value</th>
                  </tr>
                </thead>
                <tbody>
                  <!-- Sales data populated here -->
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>