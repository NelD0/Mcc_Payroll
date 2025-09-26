<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Staff Timesheet</title>
  <!-- Bootstrap 5 CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- Bootstrap Icons -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

  <style>
    body {
      font-family: "Segoe UI", Arial, sans-serif;
      margin: 0;
      padding: 0;
      background: linear-gradient(135deg, #e6f2ff 0%, #cde9ff 100%);
      min-height: 100vh;
      display: flex;
      align-items: flex-start;
      justify-content: center;
      position: relative;
    }

    body::before {
      content: '';
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grain" width="100" height="100" patternUnits="userSpaceOnUse"><circle cx="25" cy="25" r="1" fill="rgba(255,255,255,0.1)"/><circle cx="75" cy="75" r="1" fill="rgba(255,255,255,0.1)"/><circle cx="50" cy="10" r="0.5" fill="rgba(255,255,255,0.05)"/><circle cx="20" cy="80" r="0.5" fill="rgba(255,255,255,0.05)"/></pattern></defs><rect width="100" height="100" fill="url(%23grain)"/></svg>');
      pointer-events: none;
      z-index: 1;
    }

    .main-content {
      margin: 30px auto;
      padding: 30px;
      background: rgba(255, 255, 255, 0.95);
      backdrop-filter: blur(10px);
      border-radius: 20px;
      box-shadow: 0 20px 40px rgba(0,0,0,0.1), 0 0 0 1px rgba(255,255,255,0.2);
      width: 95%;
      max-width: 1400px;
      position: relative;
      z-index: 2;
      border: 1px solid rgba(255,255,255,0.3);
    }

    .main-content::before {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      right: 0;
      height: 4px;
      background: linear-gradient(90deg, #667eea, #764ba2);
      border-radius: 20px 20px 0 0;
    }

    .main-content h2 {
      font-size: 28px;
      color: #2d3748;
      font-weight: 700;
      margin-bottom: 25px;
      text-shadow: 0 2px 4px rgba(0,0,0,0.1);
      background: linear-gradient(135deg, #667eea, #764ba2);
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
      background-clip: text;
    }

    /* Icon Buttons */
    .icon-btn {
      display: inline-flex;
      align-items: center;
      justify-content: center;
      width: 45px;
      height: 45px;
      border-radius: 15px;
      font-size: 18px;
      color: #fff;
      border: none;
      cursor: pointer;
      margin-right: 10px;
      text-decoration: none;
      transition: all 0.3s ease;
      box-shadow: 0 4px 15px rgba(0,0,0,0.2);
      position: relative;
      overflow: hidden;
    }

    .icon-btn::before {
      content: '';
      position: absolute;
      top: 0;
      left: -100%;
      width: 100%;
      height: 100%;
      background: linear-gradient(90deg, transparent, rgba(255,255,255,0.3), transparent);
      transition: left 0.5s;
    }

    .icon-btn:hover::before {
      left: 100%;
    }

    /* Position top action buttons (Print/Add) at top-right */
    .top-actions {
      position: absolute;
      top: 20px;
      right: 20px;
      z-index: 3;
    }

    .btn-back {
      background: linear-gradient(135deg, #667eea, #764ba2);
    }
    .btn-back:hover {
      transform: translateY(-2px);
      box-shadow: 0 6px 20px rgba(102, 126, 234, 0.4);
    }

    .add-btn {
      background: linear-gradient(135deg, #667eea, #764ba2);
    }
    .add-btn:hover {
      transform: translateY(-2px);
      box-shadow: 0 6px 20px rgba(102, 126, 234, 0.4);
    }

    .print-btn {
      background: linear-gradient(135deg, #667eea, #764ba2);
      border: 2px solid rgba(255,255,255,0.3);
    }
    .print-btn:hover {
      transform: translateY(-2px);
      box-shadow: 0 6px 20px rgba(102, 126, 234, 0.4);
    }

    /* Action Buttons (Edit/Delete) */
    .action-btn {
      display: inline-flex;
      align-items: center;
      justify-content: center;
      width: 36px;
      height: 36px;
      border-radius: 6px;
      font-size: 16px;
      margin: 2px;
      color: #fff;
      border: none;
      text-decoration: none;
    }

    .btn-edit {
      background-color: #0d6efd;
    }
    .btn-edit:hover {
      background-color: #084298;
    }

    .btn-delete {
      background-color: #dc3545;
    }
    .btn-delete:hover {
      background-color: #a71d2a;
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
      background: linear-gradient(135deg, #667eea, #764ba2);
      color: white;
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
      color: white;
      text-shadow: 0 1px 2px rgba(0,0,0,0.1);
    }

    td.left {
      text-align: left;
    }

    tr:nth-child(even) td {
      background-color: #f9f9f9;
    }

    tr:hover td {
      background-color: rgba(102, 126, 234, 0.1) !important;
      transition: background-color 0.3s ease;
    }

    /* Larger, clearer day inputs */
    .day-column {
      width: 80px;
      min-width: 80px;
      max-width: 80px;
      padding: 4px 6px;
    }
    .day-input {
      height: 42px;
      font-size: 16px;
      padding: 6px 8px;
      text-align: center;
    }

    /* Print-specific styles */
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
      
      .icon-btn, .float-end, .action-btn {
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
      
      .print-header {
        text-align: center;
        margin-bottom: 20px;
      }
      
      .print-footer {
        margin-top: 30px;
        text-align: center;
        font-size: 10px;
        color: #666;
      }
      
      /* Hide actions column in print */
      .actions-column {
        display: none !important;
      }
    }
  </style>
</head>
<body>

  <div class="main-content">
    <!-- Print Header (hidden on screen, visible on print) -->
    <div class="print-header" style="display: none;">
      <h1>MCC PAYROLL SYSTEM</h1>
      <h2>Staff Timesheet</h2>
      <p>Generated on: <span id="print-date"></span></p>
      <hr>
    </div>

    <!-- Back button with icon -->
    <a href="{{ route('dashboard') }}" class="icon-btn btn-back" title="Back to Dashboard">
      <i class="bi bi-arrow-left"></i>
    </a>

    <h2>Staff Timesheet</h2>

    <!-- Add and Print buttons with icons (Top Right) -->
    <div class="top-actions">
      <button onclick="openPrintPage()" class="icon-btn print-btn me-2" title="Open Print Page">
        <i class="bi bi-printer"></i>
      </button>
      <a href="{{ route('staff.create') }}" class="icon-btn add-btn" title="Add Timesheet Entry">
        <i class="bi bi-plus-lg"></i>
      </a>
    </div>

    <div class="table-container mt-3">
      <table>
        <thead>
          <tr>
            <th rowspan="2">NAMES</th>
            <th rowspan="2">DESIGNATION</th>
            <th rowspan="2">Prov. Abr.</th>
            <th colspan="15">Days (16–30)</th>
            <th rowspan="2">TOTAL<br>Days</th>
            <th rowspan="2">Rate per<br>Day</th>
            <th rowspan="2">Deduction<br>Previous Cut Off</th>
            <th rowspan="2">TOTAL HONORARIUM</th>
            <th rowspan="2" class="actions-column">Actions</th>
          </tr>
          <tr>
            @for($d = 16; $d <= 30; $d++)
              <th class="day-header"><span class="day-number">{{ $d }}</span><br><small class="weekday" data-day="{{ $d }}">—</small></th>
            @endfor
          </tr>
        </thead>
        <tbody>
          @foreach($timesheets as $timesheet)
          <tr>
            <td class="left">{{ $timesheet->employee_name }}</td>
            <td>{{ $timesheet->designation }}</td>
            <td>{{ $timesheet->prov_abr }}</td>
            @php
              $days = $timesheet->days ?? [];
              // Normalize different formats to associative array: day => value
              if (is_string($days)) {
                $decoded = json_decode($days, true);
                if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                  $days = $decoded;
                } else {
                  $days = array_map('trim', explode(',', $days));
                  $days = array_filter($days);
                  $days = array_map('intval', $days);
                  $newDays = [];
                  foreach($days as $day) {
                    $newDays[$day] = 1; // default present = 1 day
                  }
                  $days = $newDays;
                }
              } elseif (!is_array($days)) {
                $days = [];
              }
            @endphp
            @for($i = 16; $i <= 30; $i++)
              <td class="day-column">
                <input type="number"
                       class="form-control day-input"
                       value="{{ isset($days[$i]) ? $days[$i] : '' }}"
                       min="0"
                       max="1"
                       step="1"
                       data-timesheet-id="{{ $timesheet->id }}"
                       data-day="{{ $i }}"
                       placeholder="0">
              </td>
            @endfor
            <td>{{ $timesheet->total_days }}</td>
            <td>₱{{ number_format($timesheet->rate_per_day, 2) }}</td>
            <td>{{ $timesheet->deduction }}</td>
            <td>{{ $timesheet->total_honorarium }}</td>
            <td class="actions-column">
              <a href="{{ route('staff.edit', $timesheet->id) }}" 
                 class="action-btn btn-edit" title="Edit">
                <i class="bi bi-pencil"></i>
              </a>
              <form action="{{ route('staff.destroy', $timesheet->id) }}" method="POST" style="display:inline;">
                @csrf
                @method('DELETE')
                <button type="submit" class="action-btn btn-delete" onclick="return confirm('Are you sure?')" title="Delete">
                  <i class="bi bi-trash"></i>
                </button>
              </form>
            </td>
          </tr>
          @endforeach
        </tbody>
      </table>
    </div>
  <!-- Bootstrap 5 JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  
  <!-- SweetAlert2 -->
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  
  <script>
    // Weekday helper
    function getWeekdayLabel(day) {
      const today = new Date();
      const d = new Date(today.getFullYear(), today.getMonth(), day);
      return ['S','M','T','W','T','F','S'][d.getDay()];
    }

    document.addEventListener('DOMContentLoaded', () => {
      document.querySelectorAll('.weekday').forEach(el => {
        const day = parseInt(el.getAttribute('data-day'), 10);
        if (!isNaN(day)) el.textContent = getWeekdayLabel(day);
      });
    });

    // Open dedicated print page
    function openPrintPage() {
      Swal.fire({
        title: 'Open Print Page',
        text: 'This will open a dedicated print-optimized page in a new tab.',
        icon: 'info',
        showCancelButton: true,
        confirmButtonColor: '#0dcaf0',
        cancelButtonColor: '#6c757d',
        confirmButtonText: '<i class="bi bi-box-arrow-up-right"></i> Open Print Page',
        cancelButtonText: 'Cancel',
        customClass: {
          popup: 'swal-custom-popup',
          title: 'swal-custom-title',
          content: 'swal-custom-content',
          confirmButton: 'swal-print-button',
          cancelButton: 'swal-custom-cancel-button'
        }
      }).then((result) => {
        if (result.isConfirmed) {
          // Open print page in new tab
          window.open('{{ route('staff.print') }}', '_blank');
          
          // Show success message
          Swal.fire({
            title: 'Print Page Opened!',
            text: 'The print-optimized page has been opened in a new tab.',
            icon: 'success',
            timer: 2000,
            showConfirmButton: false,
            customClass: {
              popup: 'swal-custom-popup',
              title: 'swal-custom-title',
              content: 'swal-custom-content'
            }
          });
        }
      });
    }

    

    // Check for success message from Laravel session
    @if(session('success'))
      Swal.fire({
        icon: 'success',
        title: 'Success!',
        text: '{{ session('success') }}',
        confirmButtonText: 'OK',
        confirmButtonColor: '#dc3545',
        timer: 3000,
        timerProgressBar: true,
        showConfirmButton: true,
        allowOutsideClick: false,
        customClass: {
          popup: 'swal-custom-popup',
          title: 'swal-custom-title',
          content: 'swal-custom-content',
          confirmButton: 'swal-custom-button'
        }
      });
    @endif

    // Check for error messages
    @if(session('error'))
      Swal.fire({
        icon: 'error',
        title: 'Error!',
        text: '{{ session('error') }}',
        confirmButtonText: 'OK',
        confirmButtonColor: '#dc3545',
        customClass: {
          popup: 'swal-custom-popup',
          title: 'swal-custom-title',
          content: 'swal-custom-content',
          confirmButton: 'swal-custom-button'
        }
      });
    @endif

    // Set print date
    document.getElementById('print-date').textContent = new Date().toLocaleDateString();
  </script>

  <style>
    /* Custom SweetAlert2 styling */
    .swal-custom-popup {
      border-radius: 15px !important;
      border: 2px solid #dc3545 !important;
      box-shadow: 0 15px 50px rgba(220, 53, 69, 0.3) !important;
    }
    
    .swal-custom-title {
      color: #dc3545 !important;
      font-weight: 700 !important;
      font-size: 1.5rem !important;
    }
    
    .swal-custom-content {
      color: #2c3e50 !important;
      font-size: 1rem !important;
      font-weight: 500 !important;
    }
    
    .swal-custom-button {
      background: linear-gradient(135deg, #dc3545, #c82333) !important;
      border: none !important;
      border-radius: 8px !important;
      font-weight: 600 !important;
      padding: 12px 30px !important;
      font-size: 0.95rem !important;
      box-shadow: 0 6px 20px rgba(220, 53, 69, 0.3) !important;
      transition: all 0.3s ease !important;
    }
    
    .swal-custom-button:hover {
      background: linear-gradient(135deg, #a71d2a, #b21e2f) !important;
      transform: translateY(-2px) !important;
      box-shadow: 0 8px 25px rgba(220, 53, 69, 0.4) !important;
    }

    .swal-print-button {
      background: linear-gradient(135deg, #0dcaf0, #0aa2c0) !important;
      border: none !important;
      border-radius: 8px !important;
      font-weight: 600 !important;
      padding: 12px 30px !important;
      font-size: 0.95rem !important;
      box-shadow: 0 6px 20px rgba(13, 202, 240, 0.3) !important;
      transition: all 0.3s ease !important;
    }
    
    .swal-print-button:hover {
      background: linear-gradient(135deg, #0aa2c0, #088395) !important;
      transform: translateY(-2px) !important;
      box-shadow: 0 8px 25px rgba(13, 202, 240, 0.4) !important;
    }

    .swal-custom-cancel-button {
      background: #6c757d !important;
      border: none !important;
      border-radius: 8px !important;
      font-weight: 600 !important;
      padding: 12px 30px !important;
      font-size: 0.95rem !important;
      transition: all 0.3s ease !important;
    }
    
    .swal-custom-cancel-button:hover {
      background: #5a6268 !important;
      transform: translateY(-2px) !important;
    }
  </style>

  </div>
</body>
</html>