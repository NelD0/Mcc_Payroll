<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>{{ $selectedDepartment }} Attendance Checker</title>
  <!-- Bootstrap 5 CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- Bootstrap Icons -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
  <!-- SweetAlert2 -->
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

  <style>
    body {
      font-family: "Segoe UI", Arial, sans-serif;
      margin: 0;
      padding: 0;
      @if($selectedDepartment == 'BSIT')
        background: #dc3545; /* Red for BSIT */
      @elseif($selectedDepartment == 'BSBA')
        background: #0d6efd; /* Blue for BSBA */
      @elseif($selectedDepartment == 'BSHM')
        background: #198754; /* Green for BSHM */
      @else
        background: #fd7e14; /* Orange for Education */
      @endif
      min-height: 100vh;
      display: flex;
      align-items: flex-start;
      justify-content: center;
    }

    .main-content {
      margin: 30px auto;
      padding: 20px;
      background: #fff;
      border-radius: 8px;
      box-shadow: 0 2px 10px rgba(0,0,0,0.1);
      width: 95%;
      max-width: 1400px;
    }

    .main-content h2 {
      font-size: 22px;
      margin-bottom: 20px;
      color: #333;
      display: inline-block;
    }

    /* Icon Buttons */
    .icon-btn {
      display: inline-flex;
      align-items: center;
      justify-content: center;
      width: 40px;
      height: 40px;
      border-radius: 50%;
      font-size: 18px;
      color: #fff;
      border: none;
      cursor: pointer;
      margin-right: 8px;
      text-decoration: none;
    }

    .btn-back {
      @if($selectedDepartment == 'BSIT')
        background-color: #dc3545;
      @elseif($selectedDepartment == 'BSBA')
        background-color: #0d6efd;
      @elseif($selectedDepartment == 'BSHM')
        background-color: #198754;
      @else
        background-color: #fd7e14;
      @endif
    }

    .btn-back:hover {
      opacity: 0.8;
    }

    .print-btn {
      background-color: #ffffff;
      @if($selectedDepartment == 'BSIT')
        color: #dc3545 !important;
        border: 2px solid #dc3545;
      @elseif($selectedDepartment == 'BSBA')
        color: #0d6efd !important;
        border: 2px solid #0d6efd;
      @elseif($selectedDepartment == 'BSHM')
        color: #198754 !important;
        border: 2px solid #198754;
      @else
        color: #fd7e14 !important;
        border: 2px solid #fd7e14;
      @endif
    }

    .print-btn:hover {
      @if($selectedDepartment == 'BSIT')
        background-color: #dc3545;
      @elseif($selectedDepartment == 'BSBA')
        background-color: #0d6efd;
      @elseif($selectedDepartment == 'BSHM')
        background-color: #198754;
      @else
        background-color: #fd7e14;
      @endif
      color: #ffffff !important;
    }

    /* Week Navigation */
    .week-nav {
      display: flex;
      align-items: center;
      justify-content: center;
      margin-bottom: 20px;
      gap: 15px;
    }

    .week-nav button {
      background: none;
      border: 1px solid #ddd;
      padding: 8px 12px;
      border-radius: 5px;
      cursor: pointer;
    }

    .week-nav button:hover {
      @if($selectedDepartment == 'BSIT')
        background-color: #dc3545;
      @elseif($selectedDepartment == 'BSBA')
        background-color: #0d6efd;
      @elseif($selectedDepartment == 'BSHM')
        background-color: #198754;
      @else
        background-color: #fd7e14;
      @endif
      color: white;
    }

    /* Table Styling */
    .table-container {
      overflow-x: auto;
    }

    table {
      width: 100%;
      border-collapse: collapse;
      background: white;
    }

    table thead {
      @if($selectedDepartment == 'BSIT')
        background-color: #f8d7da;
      @elseif($selectedDepartment == 'BSBA')
        background-color: #cfe2ff;
      @elseif($selectedDepartment == 'BSHM')
        background-color: #d1e7dd;
      @else
        background-color: #ffe5cc;
      @endif
    }

    th, td {
      padding: 10px 12px;
      text-align: center;
      border: 1px solid #ddd;
      font-size: 13px;
    }

    th {
      font-weight: 600;
      font-size: 14px;
      color: #333;
    }

    td.left {
      text-align: left;
    }

    tr:nth-child(even) td {
      background-color: #f9f9f9;
    }

    tr:hover td {
      @if($selectedDepartment == 'BSIT')
        background-color: #f8d7da;
      @elseif($selectedDepartment == 'BSBA')
        background-color: #cfe2ff;
      @elseif($selectedDepartment == 'BSHM')
        background-color: #d1e7dd;
      @else
        background-color: #ffe5cc;
      @endif
    }

    /* Attendance cells */
    .attendance-cell {
      font-size: 18px;
      padding: 8px !important;
      cursor: pointer;
    }

    .attendance-cell:hover {
      background-color: #f0f0f0 !important;
    }

    .text-success {
      color: #198754 !important;
    }

    .text-danger {
      color: #dc3545 !important;
    }

    /* Day headers */
    th small {
      font-weight: normal;
      color: #666;
      font-size: 10px;
    }

    /* Summary cards */
    .summary-cards {
      display: flex;
      gap: 15px;
      margin-bottom: 20px;
      flex-wrap: wrap;
    }

    .summary-card {
      flex: 1;
      min-width: 200px;
      padding: 15px;
      border-radius: 8px;
      color: white;
      text-align: center;
    }

    .summary-card h4 {
      margin: 0 0 5px 0;
      font-size: 24px;
    }

    .summary-card p {
      margin: 0;
      font-size: 14px;
    }

    .card-total {
      @if($selectedDepartment == 'BSIT')
        background-color: #dc3545;
      @elseif($selectedDepartment == 'BSBA')
        background-color: #0d6efd;
      @elseif($selectedDepartment == 'BSHM')
        background-color: #198754;
      @else
        background-color: #fd7e14;
      @endif
    }

    .card-present {
      background-color: #198754;
    }

    .card-absent {
      background-color: #dc3545;
    }

    .card-partial {
      background-color: #ffc107;
      color: #000;
    }

    /* Print styles */
    @media print {
      body {
        background: white !important;
        color: black !important;
        font-size: 12px;
      }
      
      .main-content {
        margin: 0 !important;
        padding: 20px !important;
        background: white !important;
        box-shadow: none !important;
        border-radius: 0 !important;
        width: 100% !important;
        max-width: none !important;
      }
      
      .icon-btn, .float-end, .week-nav, .summary-cards {
        display: none !important;
      }
      
      .main-content h2 {
        color: black !important;
        text-align: center;
        margin-bottom: 30px;
        font-size: 18px;
        font-weight: bold;
      }
      
      table {
        width: 100% !important;
        border-collapse: collapse !important;
        margin: 0 !important;
      }
      
      table thead {
        background-color: #f0f0f0 !important;
        -webkit-print-color-adjust: exact;
        color-adjust: exact;
      }
      
      th, td {
        border: 1px solid #000 !important;
        padding: 8px 6px !important;
        font-size: 10px !important;
        text-align: center !important;
      }
      
      th {
        font-weight: bold !important;
        background-color: #f0f0f0 !important;
        -webkit-print-color-adjust: exact;
        color-adjust: exact;
      }
      
      td.left {
        text-align: left !important;
      }
      
      tr:nth-child(even) td {
        background-color: #f9f9f9 !important;
        -webkit-print-color-adjust: exact;
        color-adjust: exact;
      }
    }
  </style>
</head>
<body>

  <div class="main-content">
    <!-- Back button -->
    <a href="{{ route('dashboard') }}" class="icon-btn btn-back" title="Back to Dashboard">
      <i class="bi bi-arrow-left"></i>
    </a>

    <h2>{{ $selectedDepartment }} Attendance Checker</h2>

    <!-- Print button -->
    <div class="float-end">
      <button onclick="printAttendance()" class="icon-btn print-btn me-2" title="Print Attendance">
        <i class="bi bi-printer"></i>
      </button>
    </div>

    <!-- Summary Cards -->
    <div class="summary-cards">
      <div class="summary-card card-total">
        <h4 id="total-employees">{{ count($attendanceData) }}</h4>
        <p>Total Employees</p>
      </div>
      <div class="summary-card card-present">
        <h4 id="present-count">0</h4>
        <p>Present Today</p>
      </div>
      <div class="summary-card card-absent">
        <h4 id="absent-count">0</h4>
        <p>Absent Today</p>
      </div>
      <div class="summary-card card-partial">
        <h4 id="partial-count">0</h4>
        <p>Partial Week</p>
      </div>
    </div>

    <!-- Week Navigation -->
    <div class="week-nav">
      <button onclick="previousWeek()">
        <i class="bi bi-chevron-left"></i> Previous Week
      </button>
      <span id="current-week-display">
        Week of {{ $currentWeek['monday']->format('M d') }} - {{ $currentWeek['saturday']->format('M d, Y') }}
      </span>
      <button onclick="nextWeek()">
        Next Week <i class="bi bi-chevron-right"></i>
      </button>
    </div>

    <div class="table-container mt-3">
      <table>
        <thead>
          <tr>
            <th>Employee ID</th>
            <th>Employee Name</th>
            <th>Designation</th>
            <th>Department</th>
            <th>Mon<br><small>{{ $currentWeek['monday']->format('M d') }}</small></th>
            <th>Tue<br><small>{{ $currentWeek['tuesday']->format('M d') }}</small></th>
            <th>Wed<br><small>{{ $currentWeek['wednesday']->format('M d') }}</small></th>
            <th>Thu<br><small>{{ $currentWeek['thursday']->format('M d') }}</small></th>
            <th>Fri<br><small>{{ $currentWeek['friday']->format('M d') }}</small></th>
            <th>Sat<br><small>{{ $currentWeek['saturday']->format('M d') }}</small></th>
            <th>Total Days</th>
            <th>Total Hours</th>
          </tr>
        </thead>
        <tbody>
          @forelse($attendanceData as $record)
          <tr data-employee-id="{{ $record['employee_id'] }}">
            <td>{{ $record['employee_id'] }}</td>
            <td class="left">{{ $record['employee_name'] }}</td>
            <td>{{ $record['designation'] ?? 'N/A' }}</td>
            <td>{{ $record['department'] }}</td>
            <td class="attendance-cell" onclick="toggleAttendance({{ $record['employee_id'] }}, 'monday')">
              @if($record['monday'])
                <i class="bi bi-check-circle-fill text-success" title="{{ $record['monday_hours'] }} hours"></i>
              @else
                <i class="bi bi-x-circle-fill text-danger"></i>
              @endif
            </td>
            <td class="attendance-cell" onclick="toggleAttendance({{ $record['employee_id'] }}, 'tuesday')">
              @if($record['tuesday'])
                <i class="bi bi-check-circle-fill text-success" title="{{ $record['tuesday_hours'] }} hours"></i>
              @else
                <i class="bi bi-x-circle-fill text-danger"></i>
              @endif
            </td>
            <td class="attendance-cell" onclick="toggleAttendance({{ $record['employee_id'] }}, 'wednesday')">
              @if($record['wednesday'])
                <i class="bi bi-check-circle-fill text-success" title="{{ $record['wednesday_hours'] }} hours"></i>
              @else
                <i class="bi bi-x-circle-fill text-danger"></i>
              @endif
            </td>
            <td class="attendance-cell" onclick="toggleAttendance({{ $record['employee_id'] }}, 'thursday')">
              @if($record['thursday'])
                <i class="bi bi-check-circle-fill text-success" title="{{ $record['thursday_hours'] }} hours"></i>
              @else
                <i class="bi bi-x-circle-fill text-danger"></i>
              @endif
            </td>
            <td class="attendance-cell" onclick="toggleAttendance({{ $record['employee_id'] }}, 'friday')">
              @if($record['friday'])
                <i class="bi bi-check-circle-fill text-success" title="{{ $record['friday_hours'] }} hours"></i>
              @else
                <i class="bi bi-x-circle-fill text-danger"></i>
              @endif
            </td>
            <td class="attendance-cell" onclick="toggleAttendance({{ $record['employee_id'] }}, 'saturday')">
              @if($record['saturday'])
                <i class="bi bi-check-circle-fill text-success" title="{{ $record['saturday_hours'] }} hours"></i>
              @else
                <i class="bi bi-x-circle-fill text-danger"></i>
              @endif
            </td>
            <td><strong>{{ $record['total_days'] }}</strong></td>
            <td><strong>{{ number_format($record['total_hours'], 1) }}</strong></td>
          </tr>
          @empty
          <tr>
            <td colspan="12" class="text-center text-muted py-4">
              <i class="bi bi-inbox me-2"></i>No employees found in {{ $selectedDepartment }} department
            </td>
          </tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>

  <!-- Bootstrap 5 JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  
  <script>
    // CSRF token for AJAX requests
    const csrfToken = '{{ csrf_token() }}';
    const selectedDepartment = '{{ $selectedDepartment }}';

    // Calculate and update summary statistics
    function updateSummaryStats() {
      const rows = document.querySelectorAll('tbody tr[data-employee-id]');
      let presentCount = 0;
      let absentCount = 0;
      let partialCount = 0;

      rows.forEach(row => {
        const attendanceCells = row.querySelectorAll('.attendance-cell i.text-success');
        const totalDays = attendanceCells.length;
        
        if (totalDays === 0) {
          absentCount++;
        } else if (totalDays === 6) {
          presentCount++;
        } else {
          partialCount++;
        }
      });

      document.getElementById('present-count').textContent = presentCount;
      document.getElementById('absent-count').textContent = absentCount;
      document.getElementById('partial-count').textContent = partialCount;
    }

    // Toggle attendance for a specific day
    function toggleAttendance(employeeId, day) {
      Swal.fire({
        title: 'Update Attendance',
        html: `
          <div class="mb-3">
            <label class="form-label">Mark as:</label>
            <div class="form-check">
              <input class="form-check-input" type="radio" name="attendance" id="present" value="present">
              <label class="form-check-label" for="present">Present</label>
            </div>
            <div class="form-check">
              <input class="form-check-input" type="radio" name="attendance" id="absent" value="absent">
              <label class="form-check-label" for="absent">Absent</label>
            </div>
          </div>
          <div class="mb-3">
            <label for="hours" class="form-label">Hours worked:</label>
            <input type="number" class="form-control" id="hours" min="0" max="24" step="0.5" value="8">
          </div>
        `,
        showCancelButton: true,
        confirmButtonText: 'Update',
        cancelButtonText: 'Cancel',
        preConfirm: () => {
          const attendance = document.querySelector('input[name="attendance"]:checked');
          const hours = document.getElementById('hours').value;
          
          if (!attendance) {
            Swal.showValidationMessage('Please select attendance status');
            return false;
          }
          
          return {
            status: attendance.value,
            hours: parseFloat(hours) || 0
          };
        }
      }).then((result) => {
        if (result.isConfirmed) {
          updateAttendanceRecord(employeeId, day, result.value.status === 'present', result.value.hours);
        }
      });
    }

    // Update attendance record via AJAX
    function updateAttendanceRecord(employeeId, day, isPresent, hours) {
      const data = {
        employee_id: employeeId,
        department: selectedDepartment,
        [day]: isPresent,
        [day + '_hours']: isPresent ? hours : 0,
        _token: csrfToken
      };

      fetch('{{ route("attendance.update") }}', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': csrfToken
        },
        body: JSON.stringify(data)
      })
      .then(response => response.json())
      .then(data => {
        if (data.success) {
          // Update the UI
          const row = document.querySelector(`tr[data-employee-id="${employeeId}"]`);
          const cell = row.querySelector(`.attendance-cell:nth-child(${getDayColumnIndex(day)})`);
          const icon = cell.querySelector('i');
          
          if (isPresent) {
            icon.className = 'bi bi-check-circle-fill text-success';
            icon.title = `${hours} hours`;
          } else {
            icon.className = 'bi bi-x-circle-fill text-danger';
            icon.title = '';
          }
          
          updateSummaryStats();
          
          Swal.fire({
            icon: 'success',
            title: 'Updated!',
            text: 'Attendance has been updated successfully.',
            timer: 1500,
            showConfirmButton: false
          });
        } else {
          throw new Error(data.message || 'Update failed');
        }
      })
      .catch(error => {
        Swal.fire({
          icon: 'error',
          title: 'Error!',
          text: 'Failed to update attendance: ' + error.message
        });
      });
    }

    // Get column index for day
    function getDayColumnIndex(day) {
      const dayMap = {
        'monday': 5,
        'tuesday': 6,
        'wednesday': 7,
        'thursday': 8,
        'friday': 9,
        'saturday': 10
      };
      return dayMap[day];
    }

    // Print attendance
    function printAttendance() {
      Swal.fire({
        title: 'Print Attendance',
        text: 'This will open the print dialog for the attendance sheet.',
        icon: 'info',
        showCancelButton: true,
        confirmButtonColor: '#{{ $selectedDepartment == "BSIT" ? "dc3545" : ($selectedDepartment == "BSBA" ? "0d6efd" : ($selectedDepartment == "BSHM" ? "198754" : "fd7e14")) }}',
        cancelButtonColor: '#6c757d',
        confirmButtonText: '<i class="bi bi-printer"></i> Print',
        cancelButtonText: 'Cancel'
      }).then((result) => {
        if (result.isConfirmed) {
          window.print();
        }
      });
    }

    // Week navigation functions
    function previousWeek() {
      // Implementation for previous week navigation
      Swal.fire({
        icon: 'info',
        title: 'Coming Soon',
        text: 'Week navigation will be implemented in the next update.'
      });
    }

    function nextWeek() {
      // Implementation for next week navigation
      Swal.fire({
        icon: 'info',
        title: 'Coming Soon',
        text: 'Week navigation will be implemented in the next update.'
      });
    }

    // Initialize summary stats on page load
    document.addEventListener('DOMContentLoaded', function() {
      updateSummaryStats();
    });

    // Show success message if exists
    @if(session('success'))
      Swal.fire({
        icon: 'success',
        title: 'Welcome!',
        text: '{{ session('success') }}',
        timer: 3000,
        showConfirmButton: false
      });
    @endif
  </script>

</body>
</html>