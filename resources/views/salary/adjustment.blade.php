<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Salary Adjustment/Differential - MCC Payroll</title>

  <!-- Bootstrap 5 -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

  <style>
    /* Theme tokens */
    :root{
      --brand:#3498db;
      --brand-600:#2980b9;
      --muted:#f5f6f8;
      --card:#ffffff;
      --text:#111111;
    }
    .night-mode {
      --brand:#222831;
      --brand-600:#393e46;
      --muted:#18191a;
      --card:#c5c8ce;
      --text:#ffffff;
    }

    /* Base */
    body{
      background:var(--muted);
      color:var(--text);
      transition:background .3s, color .3s;
      font-family: system-ui, -apple-system, Segoe UI, Roboto, "Helvetica Neue", Arial, "Noto Sans", "Apple Color Emoji", "Segoe UI Emoji";
      -webkit-font-smoothing: antialiased;
      -moz-osx-font-smoothing: grayscale;
      line-height: 1.5;
    }
    .app{ min-height:100vh; }

    /* Sidebar */
    .sidebar{
      background: linear-gradient(180deg, var(--brand), var(--brand-600));
      color:#fff; width:260px; position:sticky; top:0; height:100vh; padding:1.25rem 1rem;
      box-shadow: 0 10px 25px rgba(52,152,219,.25);
    }
    .sidebar .nav-link{
      color:#e3f2fd; border-radius:.75rem; padding:.6rem .8rem; font-weight:500;
    }
    .sidebar .nav-link:hover, .sidebar .nav-link.active{
      background:#fff; color:var(--brand-600);
    }
    .sidebar .section-title{ font-size:.8rem; text-transform:uppercase; opacity:.85; margin:.9rem .5rem .3rem; }

    /* Content + Topbar */
    .content{ flex:1; }
    .topbar{
      background:var(--card);
      border-bottom:1px solid #eef1f4;
      padding:.75rem 1rem;
      position:sticky; top:0; z-index:1020;
      box-shadow: 0 6px 12px rgba(0,0,0,.03);
    }
    .logout-icon{ font-size:1.4rem; color:var(--brand); }
    .logout-icon:hover{ color:#85c1e9; }

    /* Cards */
    .card-soft{
      background:var(--card);
      border:1px solid #eef1f4;
      border-radius:1rem;
      box-shadow:0 8px 24px rgba(0,0,0,.04);
    }

    /* Sidebar buttons */
    .sidebar-btn {
      background-color: var(--brand); color: white; text-align: left; margin-bottom: 6px; border: none; width: 100%; padding: 9px 12px; border-radius: 8px; transition: all 0.25s ease;
    }
    .sidebar-btn:hover, .sidebar-btn:focus { background-color: white; color: var(--brand); border: 1px solid var(--brand); transform: translateY(-1px); }

    /* Salary Adjustment Specific Styles */
    .table thead th {
      text-align: center; vertical-align: middle;
      background: var(--brand) !important; color: #fff !important;
    }
    .highlight-green { background-color: #d4edda; }
    .highlight-pink { background-color: #f8d7da; }
    .highlight-yellow {
      background-color: #fff3cd; font-weight: 600; border: 1px solid #ffe69c; border-radius: .5rem; padding:.5rem 1rem; display:inline-block;
    }
    .title { text-align: center; font-weight: 700; }
    .college-header { text-align: center; font-size: 20px; font-weight: 700; }

    /* Actions */
    .delete-employee { transition: transform .2s ease, box-shadow .2s ease; }
    .delete-employee:hover { transform: scale(1.06); box-shadow: 0 4px 8px rgba(220, 53, 69, 0.25); }

    /* SweetAlert2 */
    .swal-delete-popup { border-radius: 15px !important; border: 2px solid #dc3545 !important; }
    .swal-delete-title { color: #dc3545 !important; font-weight: 700 !important; }
    .swal-delete-content { color: #495057 !important; }
    .swal-delete-button { background: linear-gradient(135deg, #dc3545, #c82333) !important; border: none !important; border-radius: 8px !important; font-weight: 600 !important; padding: 12px 24px !important; }
    .swal-cancel-button { background: linear-gradient(135deg, #6c757d, #5a6268) !important; border: none !important; border-radius: 8px !important; font-weight: 600 !important; padding: 12px 24px !important; }
    .swal-success-popup { border-radius: 15px !important; border: 2px solid #28a745 !important; }

    /* Date Selection */
    .date-selection-card {
      background: linear-gradient(135deg, rgba(52, 152, 219, 0.05), rgba(52, 152, 219, 0.1));
      border: 1px solid rgba(52, 152, 219, 0.2);
      border-radius: 12px; box-shadow: 0 4px 15px rgba(52, 152, 219, 0.1);
    }
    .date-selection-card .card-header { background: linear-gradient(135deg, var(--brand), var(--brand-600)) !important; border-radius: 12px 12px 0 0 !important; font-weight: 600; }
    .quick-preset-btn { transition: all 0.2s ease; border-radius: 6px !important; }
    .quick-preset-btn:hover { transform: translateY(-1px); box-shadow: 0 4px 8px rgba(52, 152, 219, 0.25); }
    .date-update-btn { background: linear-gradient(135deg, #28a745, #20c997) !important; border: none !important; transition: all 0.2s ease; }
    .date-update-btn:hover { background: linear-gradient(135deg, #20c997, #17a2b8) !important; transform: translateY(-1px); }

    /* Dynamic date display */
    #dateDisplay { transition: all 0.4s ease; animation: dateGlow 2s ease-in-out infinite alternate; border-radius:.75rem; }
    @keyframes dateGlow { 0% { box-shadow: 0 0 5px rgba(255, 193, 7, 0.5);} 100% { box-shadow: 0 0 20px rgba(255, 193, 7, 0.7), 0 0 30px rgba(255, 193, 7, 0.35);} }

    /* Selects */
    .form-select-sm { border-radius: 8px; border: 2px solid #e9ecef; transition: all 0.2s ease; }
    .form-select-sm:focus { border-color: var(--brand); box-shadow: 0 0 0 0.2rem rgba(52, 152, 219, 0.25); }

    /* Table usability */
    .table thead th.sticky-top, .table thead th { position: sticky; top: 0; z-index: 5; box-shadow: inset 0 -1px 0 rgba(0,0,0,.05); }
    .table tbody tr:hover { background-color: rgba(52, 152, 219, 0.06); }
    .table > :not(caption) > * > * { vertical-align: middle; }

    /* Day columns */
    #dayHeaders th { min-width: 70px; padding: 6px 4px; }
    .day-col-header { min-width: 70px; }
    .day-cell { min-width: 90px; }
    .day-input { width: 80px; min-width: 80px; padding: 2px 6px; text-align: center; }

    /* Optional stacked day header (if applied later) */
    .day-header { text-align: center; padding: 4px 2px !important; border: 1px solid #dee2e6; }
    .day-header .day-num { font-size: 12px; line-height: 1; opacity: 0.9; }
    .day-header .day-dow { font-weight: 700; font-size: 12px; line-height: 1.1; text-transform: uppercase; }
    #dayHeaders th.day-header { color: #000 !important; }
    #dayHeaders th.day-header.first-half { background-color: #d4edda !important; }
    #dayHeaders th.day-header.second-half { background-color: #f8d7da !important; }

    /* Print */
    @media print {
      .sidebar, .topbar, .no-print { display: none !important; }
      .content { margin-left: 0 !important; }
      .table { font-size: 10px; }
      .college-header { font-size: 16px; }
    }
  </style>
</head>
<body>
  <div class="app d-flex">
    <!-- SIDEBAR -->
    <aside class="sidebar" id="sidebar">
      <a href="{{ route('admin.dashboard') }}" class="nav-link"><i class="bi bi-speedometer2 me-2"></i>Dashboard</a>
      <div class="section-title">Menu</div>
      <nav class="nav flex-column">
        <!-- Employee Dropdown -->
        <div class="dropdown">
          <button class="sidebar-btn dropdown-toggle w-100 text-start" type="button" data-bs-toggle="dropdown" aria-expanded="false">
            <i class="bi bi-archive me-2"></i>Employee
          </button>
          <ul class="dropdown-menu w-100">
            <li><a class="dropdown-item" href="{{ route('fulltime.index') }}"><i class="bi bi-person-workspace me-2"></i>Instructor - Full Time</a></li>
            <li><a class="dropdown-item" href="{{ route('parttime.index') }}"><i class="bi bi-person-workspace me-2"></i>Instructor - Part Time</a></li>
            <li><a class="dropdown-item" href="{{ route('utility.index') }}"><i class="bi bi-tools me-2"></i>Utility</a></li>
            <li><a class="dropdown-item" href="{{ route('staff.index') }}"><i class="bi bi-people-fill me-2"></i>Staff</a></li>
          </ul>
        </div>

        <!-- Department Dropdown -->
        <div class="dropdown">
          <button class="sidebar-btn dropdown-toggle w-100 text-start" type="button" data-bs-toggle="dropdown" aria-expanded="false">
            <i class="bi bi-building me-2"></i>Department
          </button>
          <ul class="dropdown-menu w-100">
            <li><a class="dropdown-item" href="{{ route('departments.index') }}"><i class="bi bi-gear me-2"></i>Manage Departments</a></li>
            <li><hr class="dropdown-divider"></li>
            <li><a class="dropdown-item" href="{{ route('bsit.index') }}"><i class="bi bi-laptop me-2"></i>BSIT</a></li>
            <li><a class="dropdown-item" href="{{ route('bsba.index') }}"><i class="bi bi-briefcase me-2"></i>BSBA</a></li>
            <li><a class="dropdown-item" href="{{ route('bshm.index') }}"><i class="bi bi-cup-hot me-2"></i>BSHM</a></li>
            <li><a class="dropdown-item" href="{{ route('education.index') }}"><i class="bi bi-book me-2"></i>EDUCATION</a></li>
          </ul>
        </div>

        <button class="sidebar-btn"><i class="bi bi-clipboard-data me-2"></i>History Records</button>
        <a href="{{ route('master.list') }}" class="sidebar-btn text-decoration-none"><i class="bi bi-list-ul me-2"></i>Master List</a>
        <a href="{{ route('salary.adjustment') }}" class="sidebar-btn text-decoration-none active" style="background: #fff; color: var(--brand-600);"><i class="bi bi-calculator me-2"></i>Salary Adjustment/Differential</a>
      </nav>
    </aside>

    <!-- MAIN CONTENT -->
    <div class="content w-100">
      <!-- TOPBAR -->
      <div class="topbar d-flex align-items-center justify-content-between no-print">
        <!-- Welcome message -->
        <div class="d-flex align-items-center">
          <div class="user-welcome">
            <h6 class="mb-0 text-muted">Salary Adjustment/Differential</h6>
          </div>
        </div>
        
        <!-- Action buttons -->
        <div class="d-flex align-items-center gap-2">
          <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-secondary btn-sm">
            <i class="bi bi-arrow-left me-1"></i>Back to Dashboard
          </a>
        </div>
      </div>

      <!-- PAGE BODY -->
      <div class="container-fluid py-4">
        <div class="card-soft p-4">
          <!-- Header -->
          <div class="college-header">
            <img src="{{ asset('images/logo.png') }}" alt="Logo" style="height:80px;" onerror="this.style.display='none'"><br>
            MADRIDEJOS COMMUNITY COLLEGE <br>
            SUMMARY OF SERVICES RENDERED <br>
            <span style="font-size:22px;">SALARY ADJUSTMENT/ DIFFERENTIAL</span>
          </div>
          
          <!-- Date Selection Controls -->
          <div class="row justify-content-center mt-3 no-print">
            <div class="col-md-6">
              <div class="card date-selection-card">
                <div class="card-header bg-primary text-white text-center">
                  <i class="bi bi-calendar3 me-2"></i>Select Period
                </div>
                <div class="card-body">
                  <div class="row g-2">
                    <div class="col-md-5">
                      <label class="form-label small">Month:</label>
                      <select class="form-select form-select-sm" id="monthSelect">
                        <option value="1">January</option>
                        <option value="2">February</option>
                        <option value="3">March</option>
                        <option value="4">April</option>
                        <option value="5">May</option>
                        <option value="6">June</option>
                        <option value="7" selected>July</option>
                        <option value="8">August</option>
                        <option value="9">September</option>
                        <option value="10">October</option>
                        <option value="11">November</option>
                        <option value="12">December</option>
                      </select>
                    </div>
                    <div class="col-md-4">
                      <label class="form-label small">Year:</label>
                      <select class="form-select form-select-sm" id="yearSelect">
                        <option value="2023">2023</option>
                        <option value="2024">2024</option>
                        <option value="2025" selected>2025</option>
                        <option value="2026">2026</option>
                        <option value="2027">2027</option>
                      </select>
                    </div>
                    <div class="col-md-3">
                      <label class="form-label small">&nbsp;</label>
                      <button class="btn btn-success btn-sm w-100 date-update-btn" onclick="updateDateDisplay()">
                        <i class="bi bi-arrow-clockwise me-1"></i>Update
                      </button>
                    </div>
                  </div>
                  
                  <!-- Quick Presets -->
                  <div class="row mt-2">
                    <div class="col-12">
                      <small class="text-muted">Quick Presets:</small>
                      <div class="btn-group w-100 mt-1" role="group">
                        <button type="button" class="btn btn-outline-success btn-sm quick-preset-btn" onclick="setCurrentMonth()">
                          <i class="bi bi-calendar-check me-1"></i>Current
                        </button>
                        <button type="button" class="btn btn-outline-primary btn-sm quick-preset-btn" onclick="setQuickDate(1, 2025)">
                          Jan 2025
                        </button>
                        <button type="button" class="btn btn-outline-primary btn-sm quick-preset-btn" onclick="setQuickDate(6, 2025)">
                          Jun 2025
                        </button>
                        <button type="button" class="btn btn-outline-primary btn-sm quick-preset-btn" onclick="setQuickDate(12, 2025)">
                          Dec 2025
                        </button>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          
          <!-- Dynamic Date Display -->
          <div class="highlight-yellow text-center mt-3" id="dateDisplay">
            JULY 1-31, 2025
          </div>
          
          <!-- Table -->
          <div class="table-responsive mt-2">
            <table class="table table-bordered table-striped table-hover table-sm text-center align-middle">
              <thead>
                <tr>
                  <th rowspan="2" class="no-print">
                    <input type="checkbox" id="selectAll" title="Select All">
                  </th>
                  <th rowspan="2">#</th>
                  <th rowspan="2" class="text-start">NAME OF EMPLOYEES</th>
                  <th rowspan="2" class="text-start">Designation</th>
                  <th colspan="15" class="highlight-green" id="firstHalfHeader">Adjustment July 1-15</th>
                  <th colspan="16" class="highlight-pink" id="secondHalfHeader">Adjustment July 16-31</th>
                  <th rowspan="2">Deduction</th>
                  <th rowspan="2">TOTAL Hours/Day</th>
                  <th rowspan="2">Rate per unit/Day</th>
                  <th rowspan="2">TOTAL HONORARIUM</th>
                  <th rowspan="2" class="no-print">Actions</th>
                </tr>
                <tr id="dayHeaders">
                  <!-- First Half Days (1-15) -->
                  <th class="day-col-header">1</th><th class="day-col-header">2</th><th class="day-col-header">3</th><th class="day-col-header">4</th><th class="day-col-header">5</th>
                  <th class="day-col-header">6</th><th class="day-col-header">7</th><th class="day-col-header">8</th><th class="day-col-header">9</th><th class="day-col-header">10</th>
                  <th class="day-col-header">11</th><th class="day-col-header">12</th><th class="day-col-header">13</th><th class="day-col-header">14</th><th class="day-col-header">15</th>
                  <!-- Second Half Days (16-31) -->
                  <th class="day-col-header">16</th><th class="day-col-header">17</th><th class="day-col-header">18</th><th class="day-col-header">19</th><th class="day-col-header">20</th>
                  <th class="day-col-header">21</th><th class="day-col-header">22</th><th class="day-col-header">23</th><th class="day-col-header">24</th><th class="day-col-header">25</th>
                  <th class="day-col-header">26</th><th class="day-col-header">27</th><th class="day-col-header">28</th><th class="day-col-header">29</th><th class="day-col-header">30</th><th class="day-col-header">31</th>
                </tr>
              </thead>
              <tbody id="employeeTableBody"></tbody>
            </table>
          </div>
          
          <!-- Action Buttons -->
          <div class="row mt-4 no-print">
            <div class="col-md-6">
              <button class="btn btn-primary me-2" onclick="addEmployee()">
                <i class="bi bi-plus-circle me-1"></i>Add Employee
              </button>
              <button class="btn btn-danger me-2" onclick="deleteSelected()" id="deleteSelectedBtn" disabled>
                <i class="bi bi-trash me-1"></i>Delete Selected
              </button>
              <button class="btn btn-warning me-2" onclick="editMode()">
                <i class="bi bi-pencil me-1"></i>Edit Mode
              </button>
            </div>
            <div class="col-md-6 text-end">
              <button class="btn btn-success me-2" onclick="saveChanges()">
                <i class="bi bi-check-circle me-1"></i>Save Changes
              </button>
              <button class="btn btn-info me-2" onclick="calculateTotals()">
                <i class="bi bi-calculator me-1"></i>Calculate Totals
              </button>
              <button class="btn btn-secondary me-2" onclick="window.print()">
                <i class="bi bi-printer me-1"></i>Print
              </button>
              <button class="btn btn-success" onclick="exportToExcel()">
                <i class="bi bi-file-earmark-excel me-1"></i>Export
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Scripts -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    // Add Employee Function
    function addEmployee() {
      Swal.fire({
        title: 'Add New Employee',
        html: `
          <div class="mb-3">
            <label class="form-label">Employee Name</label>
            <input type="text" id="employeeName" class="form-control" placeholder="Enter employee name">
          </div>
          <div class="mb-3">
            <label class="form-label">Designation</label>
            <select id="designation" class="form-control">
              <option value="Instructor">Instructor</option>
              <option value="Staff">Staff</option>
              <option value="Utility">Utility</option>
            </select>
          </div>
        `,
        showCancelButton: true,
        confirmButtonText: 'Add Employee',
        cancelButtonText: 'Cancel',
        preConfirm: () => {
          const name = document.getElementById('employeeName').value;
          const designation = document.getElementById('designation').value;
          
          if (!name) {
            Swal.showValidationMessage('Please enter employee name');
            return false;
          }
          
          return { name, designation };
        }
      }).then((result) => {
        if (result.isConfirmed) {
          // Add new row to table
          const tbody = document.getElementById('employeeTableBody');
          const rowCount = tbody.children.length + 1;
          const newRow = createEmployeeRow(rowCount, result.value.name, result.value.designation);
          tbody.appendChild(newRow);

          // Ensure day cells match current month and are inputtable
          const daysInMonth = document.querySelectorAll('#dayHeaders th').length || 31;
          updateEmployeeRows(daysInMonth);
          calculateTotals();
          
          Swal.fire('Success!', 'Employee added successfully', 'success');
        }
      });
    }

    // Create Employee Row
    function createEmployeeRow(number, name, designation) {
      const row = document.createElement('tr');
      row.id = `employee-row-${number}`;
      row.innerHTML = `
        <td class="no-print">
          <input type="checkbox" class="employee-checkbox" data-employee-id="${number}">
        </td>
        <td>${number}</td>
        <td class="employee-name">${name}</td>
        <td class="designation">${designation}</td>
        <!-- Placeholder day cells (will be replaced according to month) -->
        <td></td><td></td><td></td><td></td><td></td>
        <td></td><td></td><td></td><td></td><td></td>
        <td></td><td></td><td></td><td></td><td></td>
        <td></td><td></td><td></td><td></td><td></td>
        <td></td><td></td><td></td><td></td><td></td>
        <td></td><td></td><td></td><td></td><td></td><td></td>
        <!-- After day cells: Deduction | Total Hours/Day | Rate | Total Honorarium -->
        <td>
          <input type="number" class="form-control form-control-sm deduction-input" min="0" step="0.25" value="0" title="Deduction (hours)">
        </td>
        <td class="total-units-cell">0.00</td>
        <td>
          <div class="input-group input-group-sm">
            <input type="number" class="form-control rate-input" min="0" step="0.01" value="0" title="Rate value">
          </div>
        </td>
        <td class="total-honorarium-cell">₱ 0.00</td>
        <!-- Actions -->
        <td class="no-print">
          <button type="button" class="btn btn-danger btn-sm delete-employee" 
                  data-employee-id="${number}" 
                  data-employee-name="${name}"
                  title="Delete Employee">
            <i class="bi bi-trash"></i>
          </button>
        </td>
      `;
      return row;
    }

    // Edit Mode Function
    function editMode() {
      // Only transform day cells into inputs; keep control cells (deduction/rate) as inputs
      const rows = document.querySelectorAll('#employeeTableBody tr');
      rows.forEach(row => {
        // Day cells are between designation and the control columns
        const cells = row.querySelectorAll('td');
        // Find index of the first control column (deduction) by class marker if present
        let controlIndex = -1;
        cells.forEach((c, idx) => { if (c.querySelector('.deduction-input')) controlIndex = idx; });

        for (let i = 4; i < (controlIndex > -1 ? controlIndex : cells.length); i++) {
          const cell = cells[i];
          if (!cell.querySelector('input')) {
            const currentValue = cell.textContent.trim();
            cell.innerHTML = `<input type="number" class="form-control form-control-sm day-input" min="0" step="0.25" value="${currentValue}">`;
          }
        }
      });
      Swal.fire('Edit Mode', 'You can now edit the values in the table', 'info');
    }

    // Save Changes Function
    function saveChanges() {
      // Only persist day inputs into text and keep controls for rate/deduction
      const rows = document.querySelectorAll('#employeeTableBody tr');
      rows.forEach(row => {
        // Convert day inputs to plain text
        const dayInputs = row.querySelectorAll('.day-input');
        dayInputs.forEach(input => {
          const td = input.parentElement;
          td.textContent = input.value;
        });
      });

      calculateTotals();
      Swal.fire('Saved!', 'Changes have been saved successfully', 'success');
    }

    // Calculate Totals Function
    function calculateTotals() {
      const rows = document.querySelectorAll('#employeeTableBody tr');

      rows.forEach(row => {
        // Sum all day inputs as hours
        const dayInputs = row.querySelectorAll('.day-input');
        let totalHours = 0;
        dayInputs.forEach(input => {
          totalHours += parseFloat(input.value) || 0;
        });

        // Deduction in hours
        const deductionInput = row.querySelector('.deduction-input');
        const deductionHours = deductionInput ? (parseFloat(deductionInput.value) || 0) : 0;

        // Rate and basis
        const rateInput = row.querySelector('.rate-input');
        const rate = rateInput ? (parseFloat(rateInput.value) || 0) : 0;
        const basisSelect = row.querySelector('.rate-basis');
        const basis = basisSelect ? basisSelect.value : 'hour';

        const HOURS_PER_DAY = 8; // conversion used when basis is per day

        // Compute billable units (hours or days depending on basis)
        const netHours = Math.max(totalHours - deductionHours, 0);
        const units = basis === 'hour' ? netHours : (netHours / HOURS_PER_DAY);

        // Update total units (Hours/Day column)
        const totalUnitsCell = row.querySelector('.total-units-cell');
        if (totalUnitsCell) totalUnitsCell.textContent = units.toFixed(2);

        // Compute and update Total Honorarium
        const totalHonorariumCell = row.querySelector('.total-honorarium-cell');
        const honorarium = units * rate;
        if (totalHonorariumCell) totalHonorariumCell.textContent = `₱ ${honorarium.toFixed(2)}`;
      });
    }

    // Export to Excel Function
    function exportToExcel() {
      Swal.fire('Export', 'Excel export functionality would be implemented here', 'info');
    }

    // Delete Employee Function
    function deleteEmployee(employeeId, employeeName) {
      Swal.fire({
        title: 'Delete Employee?',
        html: `Are you sure you want to delete <strong>${employeeName}</strong>?<br><small class="text-muted">This action cannot be undone.</small>`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc3545',
        cancelButtonColor: '#6c757d',
        confirmButtonText: '<i class="bi bi-trash"></i> Yes, Delete',
        cancelButtonText: '<i class="bi bi-x-circle"></i> Cancel',
        customClass: {
          popup: 'swal-delete-popup',
          title: 'swal-delete-title',
          content: 'swal-delete-content',
          confirmButton: 'swal-delete-button',
          cancelButton: 'swal-cancel-button'
        },
        focusCancel: true
      }).then((result) => {
        if (result.isConfirmed) {
          // Remove the row from the table
          const row = document.getElementById(`employee-row-${employeeId}`);
          if (row) {
            row.remove();
            
            // Renumber remaining rows
            renumberRows();
            
            // Show success message
            Swal.fire({
              title: 'Deleted!',
              text: `${employeeName} has been removed from the salary adjustment list.`,
              icon: 'success',
              timer: 2000,
              showConfirmButton: false,
              customClass: {
                popup: 'swal-success-popup'
              }
            });
          }
        }
      });
    }

    // Renumber table rows after deletion
    function renumberRows() {
      const rows = document.querySelectorAll('#employeeTableBody tr');
      rows.forEach((row, index) => {
        const numberCell = row.querySelector('td:first-child');
        if (numberCell) {
          numberCell.textContent = index + 1;
        }
        // Update row ID
        row.id = `employee-row-${index + 1}`;
        
        // Update delete button data attributes
        const deleteBtn = row.querySelector('.delete-employee');
        if (deleteBtn) {
          deleteBtn.setAttribute('data-employee-id', index + 1);
        }
      });
    }

    // Delete Selected Employees Function
    function deleteSelected() {
      const selectedCheckboxes = document.querySelectorAll('.employee-checkbox:checked');
      
      if (selectedCheckboxes.length === 0) {
        Swal.fire('No Selection', 'Please select employees to delete', 'warning');
        return;
      }
      
      const employeeNames = Array.from(selectedCheckboxes).map(checkbox => {
        const row = checkbox.closest('tr');
        return row.querySelector('td:nth-child(3)').textContent; // Employee name column
      });
      
      Swal.fire({
        title: 'Delete Selected Employees?',
        html: `Are you sure you want to delete <strong>${selectedCheckboxes.length}</strong> employee(s)?<br><br>
               <div class="text-start"><strong>Selected employees:</strong><br>
               ${employeeNames.map(name => `• ${name}`).join('<br>')}</div>
               <br><small class="text-muted">This action cannot be undone.</small>`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc3545',
        cancelButtonColor: '#6c757d',
        confirmButtonText: '<i class="bi bi-trash"></i> Yes, Delete All',
        cancelButtonText: '<i class="bi bi-x-circle"></i> Cancel',
        customClass: {
          popup: 'swal-delete-popup',
          title: 'swal-delete-title',
          content: 'swal-delete-content',
          confirmButton: 'swal-delete-button',
          cancelButton: 'swal-cancel-button'
        }
      }).then((result) => {
        if (result.isConfirmed) {
          // Remove selected rows
          selectedCheckboxes.forEach(checkbox => {
            const row = checkbox.closest('tr');
            row.remove();
          });
          
          // Renumber remaining rows
          renumberRows();
          
          // Update checkbox states
          updateCheckboxStates();
          
          // Show success message
          Swal.fire({
            title: 'Deleted!',
            text: `${selectedCheckboxes.length} employee(s) have been removed.`,
            icon: 'success',
            timer: 2000,
            showConfirmButton: false
          });
        }
      });
    }

    // Select All Checkbox Functionality
    document.getElementById('selectAll').addEventListener('change', function() {
      const checkboxes = document.querySelectorAll('.employee-checkbox');
      checkboxes.forEach(checkbox => {
        checkbox.checked = this.checked;
      });
      updateDeleteSelectedButton();
    });

    // Individual Checkbox Change Handler
    document.addEventListener('change', function(e) {
      if (e.target.classList.contains('employee-checkbox')) {
        updateCheckboxStates();
        updateDeleteSelectedButton();
      }
    });

    // Update Checkbox States
    function updateCheckboxStates() {
      const selectAllCheckbox = document.getElementById('selectAll');
      const employeeCheckboxes = document.querySelectorAll('.employee-checkbox');
      const checkedBoxes = document.querySelectorAll('.employee-checkbox:checked');
      
      if (checkedBoxes.length === 0) {
        selectAllCheckbox.indeterminate = false;
        selectAllCheckbox.checked = false;
      } else if (checkedBoxes.length === employeeCheckboxes.length) {
        selectAllCheckbox.indeterminate = false;
        selectAllCheckbox.checked = true;
      } else {
        selectAllCheckbox.indeterminate = true;
        selectAllCheckbox.checked = false;
      }
    }

    // Update Delete Selected Button State
    function updateDeleteSelectedButton() {
      const deleteBtn = document.getElementById('deleteSelectedBtn');
      const checkedBoxes = document.querySelectorAll('.employee-checkbox:checked');
      
      if (checkedBoxes.length > 0) {
        deleteBtn.disabled = false;
        deleteBtn.innerHTML = `<i class="bi bi-trash me-1"></i>Delete Selected (${checkedBoxes.length})`;
      } else {
        deleteBtn.disabled = true;
        deleteBtn.innerHTML = '<i class="bi bi-trash me-1"></i>Delete Selected';
      }
    }

    // Event delegation for delete buttons
    document.addEventListener('click', function(e) {
      if (e.target.closest('.delete-employee')) {
        const button = e.target.closest('.delete-employee');
        const employeeId = button.getAttribute('data-employee-id');
        const employeeName = button.getAttribute('data-employee-name');
        deleteEmployee(employeeId, employeeName);
      }
    });

    // Update Date Display Function
    function updateDateDisplay() {
      const monthSelect = document.getElementById('monthSelect');
      const yearSelect = document.getElementById('yearSelect');
      const selectedMonth = parseInt(monthSelect.value);
      const selectedYear = parseInt(yearSelect.value);
      
      // Month names array
      const monthNames = [
        'JANUARY', 'FEBRUARY', 'MARCH', 'APRIL', 'MAY', 'JUNE',
        'JULY', 'AUGUST', 'SEPTEMBER', 'OCTOBER', 'NOVEMBER', 'DECEMBER'
      ];
      
      // Get days in month
      const daysInMonth = new Date(selectedYear, selectedMonth, 0).getDate();
      const monthName = monthNames[selectedMonth - 1];
      
      // Update main date display
      const dateDisplay = document.getElementById('dateDisplay');
      dateDisplay.textContent = `${monthName} 1-${daysInMonth}, ${selectedYear}`;
      
      // Update table headers
      updateTableHeaders(monthName, daysInMonth);
      
      // Show success message
      Swal.fire({
        title: 'Date Updated!',
        text: `Period changed to ${monthName} ${selectedYear}`,
        icon: 'success',
        timer: 1500,
        showConfirmButton: false,
        toast: true,
        position: 'top-end'
      });
    }

    // Update Table Headers Function
    function updateTableHeaders(monthName, daysInMonth) {
      // Update column headers
      const firstHalfHeader = document.getElementById('firstHalfHeader');
      const secondHalfHeader = document.getElementById('secondHalfHeader');
      
      const midPoint = Math.ceil(daysInMonth / 2);
      
      firstHalfHeader.textContent = `Adjustment ${monthName} 1-${midPoint}`;
      secondHalfHeader.textContent = `Adjustment ${monthName} ${midPoint + 1}-${daysInMonth}`;
      
      // Update day number headers
      updateDayHeaders(daysInMonth);
      
      // Update column spans based on days in month
      updateColumnSpans(daysInMonth);
      
      // Update employee rows to match new day structure
      updateEmployeeRows(daysInMonth);
    }

    // Update Day Headers Function
    function updateDayHeaders(daysInMonth) {
      const dayHeadersRow = document.getElementById('dayHeaders');
      const midPoint = Math.ceil(daysInMonth / 2);
      
      // Clear existing day headers
      dayHeadersRow.innerHTML = '';
      
      // Utilities to compute weekday labels
      const month = parseInt(document.getElementById('monthSelect').value); // 1-12
      const year = parseInt(document.getElementById('yearSelect').value);
      const dows = ['S','M','T','W','TH','F','S'];

      const makeDayHeader = (day, isFirstHalf) => {
        const th = document.createElement('th');
        th.classList.add('day-header');
        th.classList.add(isFirstHalf ? 'first-half' : 'second-half');
        // Build stacked number and weekday
        const num = document.createElement('div');
        num.className = 'day-num';
        num.textContent = day;
        const dow = document.createElement('div');
        dow.className = 'day-dow';
        // JS Date: month is 0-based
        const dateObj = new Date(year, month - 1, day);
        dow.textContent = dows[dateObj.getDay()];
        th.appendChild(num);
        th.appendChild(dow);
        return th;
      };

      // Add first half days
      for (let i = 1; i <= midPoint; i++) {
        dayHeadersRow.appendChild(makeDayHeader(i, true));
      }
      
      // Add second half days
      for (let i = midPoint + 1; i <= daysInMonth; i++) {
        dayHeadersRow.appendChild(makeDayHeader(i, false));
      }
    }

    // Update Column Spans Function
    function updateColumnSpans(daysInMonth) {
      const firstHalfHeader = document.getElementById('firstHalfHeader');
      const secondHalfHeader = document.getElementById('secondHalfHeader');
      
      const midPoint = Math.ceil(daysInMonth / 2);
      const firstHalfDays = midPoint;
      const secondHalfDays = daysInMonth - midPoint;
      
      firstHalfHeader.setAttribute('colspan', firstHalfDays);
      secondHalfHeader.setAttribute('colspan', secondHalfDays);
    }

    // Update Employee Rows Function
    function updateEmployeeRows(daysInMonth) {
      const rows = document.querySelectorAll('#employeeTableBody tr');
      const midPoint = Math.ceil(daysInMonth / 2);
      
      rows.forEach(row => {
        const cells = row.querySelectorAll('td');
        
        // Skip if this is not a data row (might be empty or different structure)
        if (cells.length < 10) return;
        
        // Find the day columns (after checkbox, #, name, designation)
        const startIndex = 4; // After checkbox, #, name, designation
        const endIndex = startIndex + 31; // Maximum days (31)
        
        // Remove existing day cells
        for (let i = endIndex - 1; i >= startIndex; i--) {
          if (cells[i]) {
            cells[i].remove();
          }
        }
        
        // Add new day cells based on daysInMonth (numeric inputs)
        const nameCell = cells[3]; // Designation cell
        
        const makeDayInput = () => {
          const newCell = document.createElement('td');
          newCell.classList.add('day-cell');
          newCell.innerHTML = '<input type="number" class="form-control form-control-sm day-input" min="0" step="0.25" value="">';
          return newCell;
        };
        
        // Insert day cells in correct order (append after the last inserted cell)
        let insertionPoint = nameCell;
        // Add first half days
        for (let day = 1; day <= midPoint; day++) {
          const newCell = makeDayInput();
          insertionPoint.insertAdjacentElement('afterend', newCell);
          insertionPoint = newCell; // move pointer forward
        }
        
        // Add second half days
        for (let day = midPoint + 1; day <= daysInMonth; day++) {
          const newCell = makeDayInput();
          insertionPoint.insertAdjacentElement('afterend', newCell);
          insertionPoint = newCell; // move pointer forward
        }
      });
    }

    // Quick Date Presets Function
    function setQuickDate(month, year) {
      document.getElementById('monthSelect').value = month;
      document.getElementById('yearSelect').value = year;
      updateDateDisplay();
    }

    // Set Current Month Function
    function setCurrentMonth() {
      const now = new Date();
      const currentMonth = now.getMonth() + 1;
      const currentYear = now.getFullYear();
      
      document.getElementById('monthSelect').value = currentMonth;
      document.getElementById('yearSelect').value = currentYear;
      updateDateDisplay();
      
      // Special notification for current month
      Swal.fire({
        title: 'Current Month Set!',
        text: `Switched to current month: ${now.toLocaleDateString('en-US', { month: 'long', year: 'numeric' })}`,
        icon: 'info',
        timer: 2000,
        showConfirmButton: false,
        toast: true,
        position: 'top-end'
      });
    }

    // Initialize checkbox states on page load
    document.addEventListener('DOMContentLoaded', function() {
      updateCheckboxStates();
      updateDeleteSelectedButton();
      
      // Set current date as default
      const now = new Date();
      const currentMonth = now.getMonth() + 1;
      const currentYear = now.getFullYear();
      
      // Real-time: set current month/year and update headers immediately
      document.getElementById('monthSelect').value = currentMonth;
      document.getElementById('yearSelect').value = currentYear;
      updateDateDisplay();

      // Recalculate totals when any relevant input changes
      document.addEventListener('input', function(e) {
        if (
          e.target && (
            e.target.classList.contains('day-input') ||
            e.target.classList.contains('deduction-input') ||
            e.target.classList.contains('rate-input')
          )
        ) {
          calculateTotals();
        }
      });
      // Handle basis change (/Hour vs /Day)
      document.addEventListener('change', function(e) {
        if (e.target && e.target.classList.contains('rate-basis')) {
          calculateTotals();
        }
      });
    });

    // Theme support (basic)
    const savedTheme = localStorage.getItem('theme');
    if (savedTheme === 'night-mode') {
      document.body.classList.add('night-mode');
    }
  </script>
</body>
</html>