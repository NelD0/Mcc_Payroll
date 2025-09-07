<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>Master List - All Employees</title>

  <!-- Bootstrap 5 -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

  <style>
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
    body{ background:var(--muted); color:var(--text); transition:background .3s, color .3s; }
    .app{ min-height:100vh; }
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
    .content{ flex:1; }
    .topbar{ background:#fff; border-bottom:1px solid #f0f0f0; padding:.75rem 1rem; position:sticky; top:0; z-index:1020; }
    .logout-icon{ font-size:1.4rem; color:var(--brand); }
    .logout-icon:hover{ color:#85c1e9; }
    .card-soft{ background:var(--card); border:1px solid #f0f0f0; border-radius:1rem; box-shadow:0 8px 20px rgba(0,0,0,.03); }
    .stat-card .icon-wrap{ width:46px; height:46px; border-radius:999px; display:grid; place-items:center; background:rgba(52,152,219,.1); }
    .stat-value{ font-weight:800; font-size:1.35rem; color:var(--brand-600); }
    .stat-card{ transition: transform 0.2s ease, box-shadow 0.2s ease; }
    .stat-card:hover{ transform: translateY(-2px); box-shadow: 0 12px 30px rgba(0,0,0,.08); }
    .badge-soft{ background:rgba(52,152,219,.1); color:var(--brand-600); border-radius:999px; }
    thead th{ background:var(--brand); color:#fff; }
    @media (max-width: 992px){
      .sidebar{ position:fixed; transform:translateX(-100%); transition:.25s; z-index:1030; }
      .sidebar.show{ transform:none; }
      .content{ margin-left:0!important; }
    }

    .sidebar-btn {
      background-color: #3498db; color: white; text-align: left; margin-bottom: 5px; border: none; width: 100%; padding: 8px 12px; border-radius: 5px; transition: 0.3s;
    }
    .sidebar-btn:hover, .sidebar-btn:focus {
      background-color: white; color: #3498db; border: 1px solid #3498db;
    }

    .dropdown-menu {
      border-radius: 8px;
      padding: 0;
      overflow: hidden;
    }
    .dropdown-menu .dropdown-item {
      padding: 10px 15px;
      transition: 0.3s;
    }
    .dropdown-menu .dropdown-item:hover {
      background-color: #3498db;
      color: #fff;
    }

    /* Table styling */
    .table-responsive {
      border-radius: 12px;
      overflow: hidden;
      box-shadow: 0 8px 25px rgba(0,0,0,0.1);
    }
    
    .table {
      margin-bottom: 0;
    }
    
    .table thead th {
      border: none;
      font-weight: 600;
      text-transform: uppercase;
      font-size: 0.85rem;
      letter-spacing: 0.5px;
      padding: 1rem 0.75rem;
    }
    
    .table tbody td {
      border-color: #f0f0f0;
      padding: 0.75rem;
      vertical-align: middle;
    }
    
    .table tbody tr:hover {
      background-color: rgba(52, 152, 219, 0.05);
    }
    
    .employee-name {
      font-weight: 600;
      color: var(--brand-600);
    }
    
    .employee-type {
      font-size: 0.85rem;
      padding: 0.25rem 0.5rem;
      border-radius: 12px;
      font-weight: 500;
    }
    
    .type-staff {
      background: rgba(220, 53, 69, 0.1);
      color: #dc3545;
    }
    
    .type-utility {
      background: rgba(111, 66, 193, 0.1);
      color: #6f42c1;
    }
    
    .type-fulltime {
      background: rgba(40, 167, 69, 0.1);
      color: #28a745;
    }
    
    .type-parttime {
      background: rgba(255, 193, 7, 0.1);
      color: #ffc107;
    }
    
    .department-badge {
      font-size: 0.75rem;
      padding: 0.2rem 0.4rem;
      border-radius: 8px;
      font-weight: 600;
      background: rgba(52, 152, 219, 0.1);
      color: var(--brand-600);
    }
    
    /* Filter controls */
    .filter-controls {
      background: var(--card);
      border-radius: 12px;
      padding: 1.5rem;
      margin-bottom: 1.5rem;
      box-shadow: 0 4px 15px rgba(0,0,0,0.05);
    }
    
    /* Action buttons */
    .action-buttons {
      background: var(--card);
      border-radius: 12px;
      padding: 1rem;
      margin-bottom: 1.5rem;
      box-shadow: 0 4px 15px rgba(0,0,0,0.05);
    }
    
    .btn-action {
      border-radius: 8px;
      font-weight: 500;
      padding: 0.5rem 1rem;
      transition: all 0.3s ease;
    }
    
    .btn-action:hover {
      transform: translateY(-2px);
      box-shadow: 0 4px 15px rgba(0,0,0,0.2);
    }
    
    /* Checkbox styling */
    .form-check-input:checked {
      background-color: var(--brand);
      border-color: var(--brand);
    }
    
    .select-all-checkbox {
      transform: scale(1.2);
    }
    
    /* Print styles */
    @media print {
      .no-print {
        display: none !important;
      }
      
      .sidebar {
        display: none !important;
      }
      
      .content {
        margin-left: 0 !important;
        width: 100% !important;
      }
      
      .topbar {
        display: none !important;
      }
      
      .filter-controls,
      .action-buttons {
        display: none !important;
      }
      
      .table {
        font-size: 12px;
      }
      
      .table thead th {
        background: #333 !important;
        color: white !important;
        -webkit-print-color-adjust: exact;
      }
    }

    /* Night mode adjustments */
    .night-mode .filter-controls,
    .night-mode .action-buttons {
      background: var(--card);
      border: 1px solid #444;
    }
    
    .night-mode .table tbody tr:hover {
      background-color: rgba(255, 255, 255, 0.05);
    }
    
    .night-mode .table tbody td {
      border-color: #444;
    }
  </style>
</head>
<body>
  <div class="app d-flex">
    <!-- SIDEBAR -->
    <aside class="sidebar no-print" id="sidebar">
      <a class="nav-link" href="{{ route('dashboard') }}"><i class="bi bi-speedometer2 me-2"></i>Dashboard</a>
      <a class="nav-link active" href="{{ route('master.list') }}"><i class="bi bi-list-ul me-2"></i>Master List</a>
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
      </nav>
    </aside>

    <!-- MAIN CONTENT -->
    <div class="content w-100">
      <!-- TOPBAR -->
      <div class="topbar d-flex align-items-center justify-content-between no-print">
        <div class="d-flex align-items-center">
          <h5 class="mb-0 text-muted">
            <i class="bi bi-list-ul me-2"></i>Master List - All Employees
          </h5>
        </div>
        
        <div class="d-flex align-items-center gap-2">
          <a href="{{ route('dashboard') }}" class="btn btn-outline-primary btn-sm">
            <i class="bi bi-arrow-left me-1"></i>Back to Dashboard
          </a>
          <a href="{{ url('/logout') }}" class="logout-icon" title="Log out">
            <i class="bi bi-box-arrow-right"></i>
          </a>
        </div>
      </div>

      <!-- PAGE BODY -->
      <div class="container-fluid py-4">
        <!-- Filter Controls -->
        <div class="filter-controls no-print">
          <form method="GET" action="{{ route('master.list') }}" class="row g-3 align-items-end">
            <div class="col-md-6">
              <label for="department" class="form-label fw-semibold">
                <i class="bi bi-building me-1"></i>Filter by Department
              </label>
              <select name="department" id="department" class="form-select" onchange="this.form.submit()">
                <option value="all" {{ $selectedDepartment === 'all' ? 'selected' : '' }}>All Departments</option>
                @foreach($departments as $dept)
                  <option value="{{ $dept }}" {{ $selectedDepartment === $dept ? 'selected' : '' }}>{{ $dept }}</option>
                @endforeach
                <option value="staff" {{ $selectedDepartment === 'staff' ? 'selected' : '' }}>Staff</option>
                <option value="utility" {{ $selectedDepartment === 'utility' ? 'selected' : '' }}>Utility</option>
              </select>
            </div>
            
            <div class="col-md-6">
              <button type="button" class="btn btn-outline-secondary" onclick="resetFilters()">
                <i class="bi bi-arrow-clockwise me-1"></i>Reset Filters
              </button>
            </div>
          </form>
        </div>

        <!-- Action Buttons -->
        <div class="action-buttons no-print">
          <div class="d-flex flex-wrap gap-2 align-items-center">
            <div class="me-auto">
              <span class="text-muted">
                <i class="bi bi-info-circle me-1"></i>
                Showing {{ $employees->count() }} employee(s)
              </span>
            </div>
            
            <a href="{{ route('master.list.add') }}" class="btn btn-primary btn-action">
              <i class="bi bi-person-plus me-1"></i>Add Employee
            </a>
            
            <button type="button" class="btn btn-danger btn-action" id="deleteSelectedBtn" disabled>
              <i class="bi bi-trash me-1"></i>Delete Selected
            </button>
            
            <button type="button" class="btn btn-success btn-action" id="printSelectedBtn" disabled>
              <i class="bi bi-printer me-1"></i>Print Selected
            </button>
            
            <button type="button" class="btn btn-info btn-action" onclick="window.print()">
              <i class="bi bi-printer-fill me-1"></i>Print All
            </button>
          </div>
        </div>

        <!-- Employee Table -->
        <div class="table-responsive">
          <table class="table table-hover">
            <thead>
              <tr>
                <th class="no-print" style="width: 50px;">
                  <div class="form-check">
                    <input class="form-check-input select-all-checkbox" type="checkbox" id="selectAll">
                  </div>
                </th>
                <th>Employee Name</th>
                <th>Type</th>
                <th>Department</th>
                <th>Designation</th>
                <th>Rate</th>
                <th class="no-print">Actions</th>
              </tr>
            </thead>
            <tbody>
              @forelse($employees as $employee)
                <tr>
                  <td class="no-print">
                    <div class="form-check">
                      <input class="form-check-input employee-checkbox" type="checkbox" 
                             value="{{ $employee->id }}" 
                             data-type="{{ 
                               $employee->type === 'Staff' ? 'staff' : 
                               ($employee->type === 'Utility' ? 'utility' : 
                               ($employee->type === 'Full-time Instructor' ? 'fulltime' : 'parttime'))
                             }}">
                    </div>
                  </td>
                  <td>
                    <div class="employee-name">{{ $employee->employee_name }}</div>
                  </td>
                  <td>
                    <span class="employee-type {{ 
                      $employee->type === 'Staff' ? 'type-staff' : 
                      ($employee->type === 'Utility' ? 'type-utility' : 
                      ($employee->type === 'Full-time Instructor' ? 'type-fulltime' : 'type-parttime'))
                    }}">
                      {{ $employee->type }}
                    </span>
                  </td>
                  <td>
                    @if($employee->department)
                      <span class="department-badge">{{ $employee->department }}</span>
                    @else
                      <span class="text-muted">-</span>
                    @endif
                  </td>
                  <td>{{ $employee->designation ?? 'N/A' }}</td>
                  <td>
                    @php
                      $rate = $employee->rate ?? null;
                      $unit = strpos(strtolower($employee->type), 'instructor') !== false ? '' : '/day';
                    @endphp
                    {{ $rate !== null ? '₱ '.number_format((float)$rate, 2).($unit ? ' '.$unit : '') : 'N/A' }}
                  </td>
                  <td class="no-print">
                    @php
                      $typeRoute = $employee->type === 'Staff' ? 'staff' : ($employee->type === 'Utility' ? 'utility' : ($employee->type === 'Full-time Instructor' ? 'fulltime' : 'parttime'));
                    @endphp
                    <div class="btn-group btn-group-sm" role="group">
                      <a class="btn btn-outline-primary" href="{{ route($typeRoute.'.show', $employee->id) }}" title="View"><i class="bi bi-eye"></i></a>
                      <a class="btn btn-outline-secondary" href="{{ route($typeRoute.'.edit', $employee->id) }}" title="Edit"><i class="bi bi-pencil"></i></a>
                      <button type="button" class="btn btn-outline-danger" title="Delete" onclick="(function(){
                        const t='{{ strtolower($employee->type) }}'.includes('staff')?'staff':('{{ strtolower($employee->type) }}'.includes('utility')?'utility':('{{ strtolower($employee->type) }}'.includes('full')?'fulltime':'parttime'));
                        deleteEmployees(t==='staff'?[{{ $employee->id }}]:[], t==='utility'?[{{ $employee->id }}]:[], t==='fulltime'?[{{ $employee->id }}]:[], t==='parttime'?[{{ $employee->id }}]:[]);
                      })()">
                        <i class="bi bi-trash"></i>
                      </button>
                    </div>
                  </td>
                </tr>
              @empty
                <tr>
                  <td colspan="7" class="text-center py-4">
                    <div class="text-muted">
                      <i class="bi bi-inbox display-4 d-block mb-2"></i>
                      <h6>No employees found</h6>
                      <p class="mb-0">Try adjusting your filters or add some employees first.</p>
                    </div>
                  </td>
                </tr>
              @endforelse
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>

  <!-- Scripts -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    // CSRF token setup
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    
    // DOM elements
    const selectAllCheckbox = document.getElementById('selectAll');
    const employeeCheckboxes = document.querySelectorAll('.employee-checkbox');
    const deleteSelectedBtn = document.getElementById('deleteSelectedBtn');
    const printSelectedBtn = document.getElementById('printSelectedBtn');

    // Select all functionality
    selectAllCheckbox.addEventListener('change', function() {
      employeeCheckboxes.forEach(checkbox => {
        checkbox.checked = this.checked;
      });
      updateActionButtons();
    });

    // Individual checkbox change
    employeeCheckboxes.forEach(checkbox => {
      checkbox.addEventListener('change', function() {
        updateSelectAllState();
        updateActionButtons();
      });
    });

    // Update select all checkbox state
    function updateSelectAllState() {
      const checkedBoxes = document.querySelectorAll('.employee-checkbox:checked');
      const totalBoxes = employeeCheckboxes.length;
      
      if (checkedBoxes.length === 0) {
        selectAllCheckbox.indeterminate = false;
        selectAllCheckbox.checked = false;
      } else if (checkedBoxes.length === totalBoxes) {
        selectAllCheckbox.indeterminate = false;
        selectAllCheckbox.checked = true;
      } else {
        selectAllCheckbox.indeterminate = true;
        selectAllCheckbox.checked = false;
      }
    }

    // Update action buttons state
    function updateActionButtons() {
      const checkedBoxes = document.querySelectorAll('.employee-checkbox:checked');
      const hasSelection = checkedBoxes.length > 0;
      
      deleteSelectedBtn.disabled = !hasSelection;
      printSelectedBtn.disabled = !hasSelection;
      
      if (hasSelection) {
        deleteSelectedBtn.classList.remove('btn-outline-danger');
        deleteSelectedBtn.classList.add('btn-danger');
        printSelectedBtn.classList.remove('btn-outline-success');
        printSelectedBtn.classList.add('btn-success');
      } else {
        deleteSelectedBtn.classList.add('btn-outline-danger');
        deleteSelectedBtn.classList.remove('btn-danger');
        printSelectedBtn.classList.add('btn-outline-success');
        printSelectedBtn.classList.remove('btn-success');
      }
    }

    // Delete selected employees
    deleteSelectedBtn.addEventListener('click', function() {
      const checkedBoxes = document.querySelectorAll('.employee-checkbox:checked');
      
      if (checkedBoxes.length === 0) {
        Swal.fire({
          icon: 'warning',
          title: 'No Selection',
          text: 'Please select employees to delete.',
          confirmButtonColor: '#3498db'
        });
        return;
      }

      // Group by type
      const staffIds = [];
      const utilityIds = [];
      const fulltimeIds = [];
      const parttimeIds = [];
      
      checkedBoxes.forEach(checkbox => {
        const type = checkbox.getAttribute('data-type');
        const id = parseInt(checkbox.value);
        
        if (type === 'staff') {
          staffIds.push(id);
        } else if (type === 'utility') {
          utilityIds.push(id);
        } else if (type === 'fulltime') {
          fulltimeIds.push(id);
        } else if (type === 'parttime') {
          parttimeIds.push(id);
        }
      });

      Swal.fire({
        title: 'Confirm Deletion',
        text: `Are you sure you want to delete ${checkedBoxes.length} selected employee(s)? This action cannot be undone.`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc3545',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Yes, delete them!',
        cancelButtonText: 'Cancel'
      }).then((result) => {
        if (result.isConfirmed) {
          deleteEmployees(staffIds, utilityIds, fulltimeIds, parttimeIds);
        }
      });
    });

    // Delete employees function
    async function deleteEmployees(staffIds, utilityIds, fulltimeIds, parttimeIds) {
      try {
        const promises = [];
        
        // Delete staff employees
        if (staffIds.length > 0) {
          promises.push(
            fetch('{{ route("master.list.delete") }}', {
              method: 'POST',
              headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken
              },
              body: JSON.stringify({
                ids: staffIds,
                type: 'staff'
              })
            })
          );
        }
        
        // Delete utility employees
        if (utilityIds.length > 0) {
          promises.push(
            fetch('{{ route("master.list.delete") }}', {
              method: 'POST',
              headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken
              },
              body: JSON.stringify({
                ids: utilityIds,
                type: 'utility'
              })
            })
          );
        }
        
        // Delete fulltime employees
        if (fulltimeIds.length > 0) {
          promises.push(
            fetch('{{ route("master.list.delete") }}', {
              method: 'POST',
              headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken
              },
              body: JSON.stringify({
                ids: fulltimeIds,
                type: 'fulltime'
              })
            })
          );
        }
        
        // Delete parttime employees
        if (parttimeIds.length > 0) {
          promises.push(
            fetch('{{ route("master.list.delete") }}', {
              method: 'POST',
              headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken
              },
              body: JSON.stringify({
                ids: parttimeIds,
                type: 'parttime'
              })
            })
          );
        }

        const responses = await Promise.all(promises);
        let totalDeleted = 0;
        
        for (const response of responses) {
          const data = await response.json();
          if (data.success) {
            totalDeleted += data.deleted_count;
          }
        }

        Swal.fire({
          icon: 'success',
          title: 'Deleted Successfully',
          text: `Successfully deleted ${totalDeleted} employee(s).`,
          confirmButtonColor: '#3498db'
        }).then(() => {
          window.location.reload();
        });

      } catch (error) {
        console.error('Error deleting employees:', error);
        Swal.fire({
          icon: 'error',
          title: 'Error',
          text: 'An error occurred while deleting employees. Please try again.',
          confirmButtonColor: '#3498db'
        });
      }
    }

    // Print selected employees
    printSelectedBtn.addEventListener('click', function() {
      const checkedBoxes = document.querySelectorAll('.employee-checkbox:checked');
      
      if (checkedBoxes.length === 0) {
        Swal.fire({
          icon: 'warning',
          title: 'No Selection',
          text: 'Please select employees to print.',
          confirmButtonColor: '#3498db'
        });
        return;
      }

      // Group by type
      const staffIds = [];
      const utilityIds = [];
      const fulltimeIds = [];
      const parttimeIds = [];
      
      checkedBoxes.forEach(checkbox => {
        const type = checkbox.getAttribute('data-type');
        const id = parseInt(checkbox.value);
        
        if (type === 'staff') {
          staffIds.push(id);
        } else if (type === 'utility') {
          utilityIds.push(id);
        } else if (type === 'fulltime') {
          fulltimeIds.push(id);
        } else if (type === 'parttime') {
          parttimeIds.push(id);
        }
      });

      printEmployees(staffIds, utilityIds, fulltimeIds, parttimeIds);
    });

    // Print employees function
    async function printEmployees(staffIds, utilityIds, fulltimeIds, parttimeIds) {
      try {
        const promises = [];
        
        // Get staff employees data
        if (staffIds.length > 0) {
          promises.push(
            fetch('{{ route("master.list.print") }}', {
              method: 'POST',
              headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken
              },
              body: JSON.stringify({
                ids: staffIds,
                type: 'staff'
              })
            }).then(response => response.json())
          );
        }
        
        // Get utility employees data
        if (utilityIds.length > 0) {
          promises.push(
            fetch('{{ route("master.list.print") }}', {
              method: 'POST',
              headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken
              },
              body: JSON.stringify({
                ids: utilityIds,
                type: 'utility'
              })
            }).then(response => response.json())
          );
        }
        
        // Get fulltime employees data
        if (fulltimeIds.length > 0) {
          promises.push(
            fetch('{{ route("master.list.print") }}', {
              method: 'POST',
              headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken
              },
              body: JSON.stringify({
                ids: fulltimeIds,
                type: 'fulltime'
              })
            }).then(response => response.json())
          );
        }
        
        // Get parttime employees data
        if (parttimeIds.length > 0) {
          promises.push(
            fetch('{{ route("master.list.print") }}', {
              method: 'POST',
              headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken
              },
              body: JSON.stringify({
                ids: parttimeIds,
                type: 'parttime'
              })
            }).then(response => response.json())
          );
        }

        const responses = await Promise.all(promises);
        let allEmployees = [];
        
        responses.forEach(data => {
          if (data.success) {
            allEmployees = allEmployees.concat(data.employees);
          }
        });

        // Create print window
        createPrintWindow(allEmployees);

      } catch (error) {
        console.error('Error getting print data:', error);
        Swal.fire({
          icon: 'error',
          title: 'Error',
          text: 'An error occurred while preparing print data. Please try again.',
          confirmButtonColor: '#3498db'
        });
      }
    }

    // Create print window
    function createPrintWindow(employees) {
      const printWindow = window.open('', '_blank');
      const printContent = `
        <!DOCTYPE html>
        <html>
        <head>
          <title>Master List - Selected Employees</title>
          <style>
            body { font-family: Arial, sans-serif; margin: 20px; }
            h1 { text-align: center; color: #3498db; margin-bottom: 30px; }
            table { width: 100%; border-collapse: collapse; margin-top: 20px; }
            th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
            th { background-color: #3498db; color: white; font-weight: bold; }
            tr:nth-child(even) { background-color: #f9f9f9; }
            .type-staff { background: #dc3545; color: white; padding: 2px 6px; border-radius: 4px; font-size: 12px; }
            .type-utility { background: #6f42c1; color: white; padding: 2px 6px; border-radius: 4px; font-size: 12px; }
            .type-fulltime { background: #28a745; color: white; padding: 2px 6px; border-radius: 4px; font-size: 12px; }
            .type-parttime { background: #ffc107; color: black; padding: 2px 6px; border-radius: 4px; font-size: 12px; }
            .department-badge { background: #3498db; color: white; padding: 2px 6px; border-radius: 4px; font-size: 11px; }
            .print-date { text-align: right; margin-bottom: 20px; color: #666; }
          </style>
        </head>
        <body>
          <div class="print-date">Printed on: ${new Date().toLocaleDateString()}</div>
          <h1>Master List - Selected Employees</h1>
          <table>
            <thead>
              <tr>
                <th>Employee Name</th>
                <th>Type</th>
                <th>Department</th>
                <th>Designation</th>
              </tr>
            </thead>
            <tbody>
              ${employees.map(emp => `
                <tr>
                  <td>${emp.employee_name}</td>
                  <td><span class="type-${emp.type.toLowerCase().replace(/[^a-z]/g, '')}">${emp.type}</span></td>
                  <td>${emp.department ? `<span class="department-badge">${emp.department}</span>` : '-'}</td>
                  <td>${emp.designation || 'N/A'}</td>
                </tr>
              `).join('')}
            </tbody>
          </table>
        </body>
        </html>
      `;
      
      printWindow.document.write(printContent);
      printWindow.document.close();
      printWindow.focus();
      printWindow.print();
    }

    // Reset filters function
    function resetFilters() {
      window.location.href = '{{ route("master.list") }}';
    }

    // Initialize page
    document.addEventListener('DOMContentLoaded', function() {
      updateActionButtons();
    });
  </script>
</body>
</html>