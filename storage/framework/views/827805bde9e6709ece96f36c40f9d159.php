<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>BSIT Attendance Checker</title>
  <!-- Bootstrap 5 CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- Bootstrap Icons -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

  <style>
    body {
      font-family: "Segoe UI", Arial, sans-serif;
      margin: 0;
      padding: 0;
      background: radial-gradient(circle at top left, white 30%, transparent 30%) top left/50% 100% no-repeat,
                  radial-gradient(circle at bottom right, #3498db 30%, transparent 30%) bottom right/50% 100% no-repeat,
                  linear-gradient(135deg, #3498db, #5dade2, white); /* Match Create Account background */
      min-height: 100vh;
      display: flex;
      align-items: flex-start;
      justify-content: center;
    }

    .main-content {
      margin: 30px auto;
      padding: 20px;
      background: #fff; /* White content box */
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
      background-color: #dc3545;
    }
    .btn-back:hover {
      background-color: #a71d2a;
    }



    .print-btn {
      background-color: #ffffff;
      color: #dc3545 !important;
      border: 2px solid #dc3545;
    }
    .print-btn:hover {
      background-color: #dc3545;
      color: #ffffff !important;
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
      background-color: #f8d7da; /* Light red header for BSIT */
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
      background-color: #f8d7da; /* light red hover */
    }



    /* Employee type badges */
    .badge {
      padding: 4px 8px;
      border-radius: 12px;
      font-size: 11px;
      font-weight: 500;
    }

    .bg-primary {
      background-color: #dc3545 !important;
    }

    .bg-info {
      background-color: #ffffff !important;
      color: #dc3545 !important;
      border: 1px solid #dc3545;
    }

    /* Attendance cells */
    .attendance-cell {
      font-size: 18px;
      padding: 8px !important;
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
      
      .icon-btn, .float-end {
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
      

    }
  </style>
</head>
<body>

  <div class="main-content">
    <!-- Print Header (hidden on screen, visible on print) -->
    <div class="print-header" style="display: none;">
      <h1>MCC PAYROLL SYSTEM</h1>
      <h2>BSIT Attendance Checker</h2>
      <p>Generated on: <span id="print-date"></span></p>
      <hr>
    </div>

    <!-- Back button with icon -->
    <a href="<?php echo e(route('dashboard')); ?>" class="icon-btn btn-back" title="Back to Dashboard">
      <i class="bi bi-arrow-left"></i>
    </a>

    <h2>BSIT Attendance Checker</h2>

    <!-- Print button with icon -->
    <div class="float-end">
      <button onclick="openPrintPage()" class="icon-btn print-btn me-2" title="Print Attendance">
        <i class="bi bi-printer"></i>
      </button>
    </div>

    <div class="table-container mt-3">
      <table>
        <thead>
          <tr>
            <th>Employee ID</th>
            <th>Employee Name</th>
            <th>Designation</th>
            <th>Type</th>
            <th>Department</th>
            <th>Mon<br><small id="mon-date"></small></th>
            <th>Tue<br><small id="tue-date"></small></th>
            <th>Wed<br><small id="wed-date"></small></th>
            <th>Thu<br><small id="thu-date"></small></th>
            <th>Fri<br><small id="fri-date"></small></th>
            <th>Sat<br><small id="sat-date"></small></th>
          </tr>
        </thead>
        <tbody>
          <?php $__empty_1 = true; $__currentLoopData = $attendanceRecords; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $record): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
          <tr>
            <td><?php echo e($record['id']); ?></td>
            <td class="left"><?php echo e($record['employee_name']); ?></td>
            <td><?php echo e($record['designation'] ?? 'N/A'); ?></td>
            <td>
              <span class="badge <?php echo e($record['employee_type'] == 'Full-time' ? 'bg-primary' : 'bg-info'); ?>">
                <?php echo e($record['employee_type']); ?>

              </span>
            </td>
            <td><?php echo e($record['department']); ?></td>
            <td class="attendance-cell">
              <?php if(isset($record['attendance']['monday']) && $record['attendance']['monday']): ?>
                <i class="bi bi-check-circle-fill text-success"></i>
              <?php else: ?>
                <i class="bi bi-x-circle-fill text-danger"></i>
              <?php endif; ?>
            </td>
            <td class="attendance-cell">
              <?php if(isset($record['attendance']['tuesday']) && $record['attendance']['tuesday']): ?>
                <i class="bi bi-check-circle-fill text-success"></i>
              <?php else: ?>
                <i class="bi bi-x-circle-fill text-danger"></i>
              <?php endif; ?>
            </td>
            <td class="attendance-cell">
              <?php if(isset($record['attendance']['wednesday']) && $record['attendance']['wednesday']): ?>
                <i class="bi bi-check-circle-fill text-success"></i>
              <?php else: ?>
                <i class="bi bi-x-circle-fill text-danger"></i>
              <?php endif; ?>
            </td>
            <td class="attendance-cell">
              <?php if(isset($record['attendance']['thursday']) && $record['attendance']['thursday']): ?>
                <i class="bi bi-check-circle-fill text-success"></i>
              <?php else: ?>
                <i class="bi bi-x-circle-fill text-danger"></i>
              <?php endif; ?>
            </td>
            <td class="attendance-cell">
              <?php if(isset($record['attendance']['friday']) && $record['attendance']['friday']): ?>
                <i class="bi bi-check-circle-fill text-success"></i>
              <?php else: ?>
                <i class="bi bi-x-circle-fill text-danger"></i>
              <?php endif; ?>
            </td>
            <td class="attendance-cell">
              <?php if(isset($record['attendance']['saturday']) && $record['attendance']['saturday']): ?>
                <i class="bi bi-check-circle-fill text-success"></i>
              <?php else: ?>
                <i class="bi bi-x-circle-fill text-danger"></i>
              <?php endif; ?>
            </td>
          </tr>
          <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
          <tr>
            <td colspan="11" class="text-center text-muted py-4">
              <i class="bi bi-inbox me-2"></i>No attendance records found
            </td>
          </tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>

    <!-- Print Footer (hidden on screen, visible on print) -->
    <div class="print-footer" style="display: none;">
      <p>This is a computer-generated report from MCC Payroll System</p>
      <p>Printed on: <span id="print-date-footer"></span></p>
    </div>
  </div>

  <!-- Bootstrap 5 JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  
  <!-- SweetAlert2 -->
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  
  <script>
    // Open dedicated print page
    function openPrintPage() {
      Swal.fire({
        title: 'Print Attendance',
        text: 'This will open a print-optimized page.',
        icon: 'info',
        showCancelButton: true,
        confirmButtonColor: '#dc3545',
        cancelButtonColor: '#6c757d',
        confirmButtonText: '<i class="bi bi-printer"></i> Print',
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
          // Print current page
          window.print();
          
          // Show success message
          Swal.fire({
            title: 'Print Dialog Opened!',
            text: 'The print dialog has been opened.',
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



    // Set print dates and calendar dates
    document.addEventListener('DOMContentLoaded', function() {
      const now = new Date();
      const dateString = now.toLocaleDateString() + ' ' + now.toLocaleTimeString();
      document.getElementById('print-date').textContent = dateString;
      document.getElementById('print-date-footer').textContent = dateString;

      // Set calendar dates for current week
      setWeekDates();
    });

    function setWeekDates() {
      const today = new Date();
      const currentDay = today.getDay(); // 0 = Sunday, 1 = Monday, etc.
      
      // Calculate Monday of current week
      const monday = new Date(today);
      const daysFromMonday = currentDay === 0 ? 6 : currentDay - 1; // If Sunday, go back 6 days
      monday.setDate(today.getDate() - daysFromMonday);

      // Set dates for each day
      const days = ['mon', 'tue', 'wed', 'thu', 'fri', 'sat'];
      
      for (let i = 0; i < days.length; i++) {
        const date = new Date(monday);
        date.setDate(monday.getDate() + i);
        
        const dayElement = document.getElementById(days[i] + '-date');
        if (dayElement) {
          dayElement.textContent = date.getDate() + '/' + (date.getMonth() + 1);
        }
      }
    }

    // Custom SweetAlert2 styles
    const style = document.createElement('style');
    style.textContent = `
      .swal-custom-popup {
        border-radius: 12px !important;
        font-family: "Segoe UI", Arial, sans-serif !important;
      }
      .swal-custom-title {
        color: #333 !important;
        font-weight: 600 !important;
      }
      .swal-custom-content {
        color: #666 !important;
      }
      .swal-custom-button {
        background-color: #0d6efd !important;
        border-radius: 8px !important;
        font-weight: 500 !important;
        padding: 10px 24px !important;
      }
      .swal-custom-button:hover {
        background-color: #084298 !important;
      }
      .swal-print-button {
        background-color: #0dcaf0 !important;
        border-radius: 8px !important;
        font-weight: 500 !important;
        padding: 10px 24px !important;
      }
      .swal-print-button:hover {
        background-color: #0aa2c0 !important;
      }
      .swal-custom-cancel-button {
        background-color: #6c757d !important;
        border-radius: 8px !important;
        font-weight: 500 !important;
        padding: 10px 24px !important;
      }
      .swal-custom-cancel-button:hover {
        background-color: #565e64 !important;
      }
    `;
    document.head.appendChild(style);
  </script>
</body>
</html><?php /**PATH C:\xampp\htdocs\Mcc_Payroll\resources\views/bsit/index.blade.php ENDPATH**/ ?>