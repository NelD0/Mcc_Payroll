<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>Instructor Fulltime Timesheet</title>
  <!-- Bootstrap 5 CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- Bootstrap Icons -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

  <style>
    body {
      font-family: "Segoe UI", Arial, sans-serif;
      margin: 0;
      padding: 0;
      background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
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

    .btn-back {
      background: linear-gradient(135deg, #667eea, #764ba2);
    }
    .btn-back:hover {
      transform: translateY(-2px);
      box-shadow: 0 6px 20px rgba(102, 126, 234, 0.4);
    }

    .add-btn {
      background: linear-gradient(135deg, #11998e, #38ef7d);
    }
    .add-btn:hover {
      transform: translateY(-2px);
      box-shadow: 0 6px 20px rgba(17, 153, 142, 0.4);
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

    .btn-save {
      background-color: #198754;
    }
    .btn-save:hover {
      background-color: #146c43;
    }
    .btn-save.saving {
      background-color: #ffc107;
    }
    .btn-save.saved {
      background-color: #0dcaf0;
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
      border-radius: 15px;
      box-shadow: 0 10px 30px rgba(0,0,0,0.1);
      background: white;
      border: 1px solid rgba(102, 126, 234, 0.1);
      margin-top: 20px;
    }
    
    /* Responsive adjustments */
    @media (max-width: 768px) {
      .main-content {
        margin: 15px;
        padding: 15px;
        width: calc(100% - 30px);
      }
      
      .d-flex.justify-content-between {
        flex-direction: column;
        gap: 15px;
      }
      
      .d-flex.align-items-center:first-child {
        justify-content: center;
      }
      
      .d-flex.align-items-center:last-child {
        justify-content: center;
      }
      
      th, td {
        padding: 6px 4px;
        font-size: 11px;
      }
      
      .day-column {
        width: 50px;
        min-width: 50px;
        max-width: 50px;
      }
      
      .day-input {
        height: 28px;
        font-size: 10px;
        padding: 1px 2px;
      }
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
      padding: 8px 6px;
      text-align: center;
      border: 1px solid #ddd;
      font-size: 12px;
      white-space: nowrap;
    }
    
    /* Day columns styling */
    .day-column {
      width: 60px;
      min-width: 60px;
      max-width: 60px;
      padding: 4px 2px;
    }
    
    .day-input {
      width: 100%;
      height: 32px;
      padding: 2px 4px;
      border: 1px solid #ddd;
      border-radius: 4px;
      text-align: center;
      font-size: 11px;
      background: white;
      transition: all 0.2s ease;
    }
    
    .day-input:focus {
      border-color: #667eea;
      box-shadow: 0 0 0 2px rgba(102, 126, 234, 0.2);
      outline: none;
    }
    
    .day-input:hover {
      border-color: #667eea;
    }
    
    .day-input.saving {
      background-color: #fff3cd;
      border-color: #ffc107;
    }
    
    .day-input.saved {
      background-color: #d1edff;
      border-color: #0dcaf0;
    }
    
    .day-input.error {
      background-color: #f8d7da;
      border-color: #dc3545;
    }
    
    /* Field input styling */
    .field-input {
      width: 100%;
      padding: 4px 6px;
      border: 1px solid #ddd;
      border-radius: 4px;
      font-size: 12px;
      background: white;
      transition: all 0.2s ease;
    }
    
    .field-input:focus {
      border-color: #667eea;
      box-shadow: 0 0 0 2px rgba(102, 126, 234, 0.2);
      outline: none;
    }
    
    .field-input:hover {
      border-color: #667eea;
    }
    
    .field-input.saving {
      background-color: #fff3cd;
      border-color: #ffc107;
    }
    
    .field-input.saved {
      background-color: #d1edff;
      border-color: #0dcaf0;
    }
    
    .field-input.error {
      background-color: #f8d7da;
      border-color: #dc3545;
    }
    
    .day-header {
      width: 60px;
      min-width: 60px;
      max-width: 60px;
      font-size: 11px;
      line-height: 1.2;
    }
    
    .day-header small {
      font-size: 9px;
      color: #666;
    }

    /* Day header styles to mimic sample */
    .day-header { position: relative; }
    .day-number {
      display: inline-block;
      color: #000; /* black */
      font-size: 16px; /* bigger number */
      font-weight: 800;
      line-height: 1.1;
    }
    .day-abbr {
      display: inline-block;
      color: #000; /* black */
      font-size: 12px;
      font-weight: 700;
      letter-spacing: 0.5px;
    }
    .current-day { background-color: #f7c79a !important; }
    /* Tighten cell spacing similar to the image */
    .day-header, .day-column { padding: 6px 4px; }

    /* Highlight Monday to Saturday labels */
    .weekday-label {
      font-weight: 700;
      color: #000000; /* darker for contrast */
      background-color: #fff3cd; /* light highlight */
      padding: 2px 4px;
      border-radius: 4px;
      display: inline-block;
    }
    
    /* Empty state styling */
    .empty-state {
      padding: 2rem;
    }
    
    .empty-state i {
      display: block;
      margin: 0 auto 1rem;
    }

    th {
      font-weight: 600;
      font-size: 14px;
      color: white;
      text-shadow: 0 1px 2px rgba(0,0,0,0.1);
    }

    tr:hover td {
      background-color: rgba(102, 126, 234, 0.1) !important;
      transition: background-color 0.3s ease;
    }

    td.left {
      text-align: left;
    }

    tr:nth-child(even) td {
      background-color: #f9f9f9;
    }

    tr:hover td {
      background-color: #ffe6e6; /* light red hover */
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
      
      .icon-btn, .float-end, .action-btn, .btn-save {
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
      
      /* Hide input fields and show values in print */
      .day-input, .field-input {
        border: none !important;
        background: transparent !important;
        text-align: center !important;
        font-weight: bold !important;
        padding: 0 !important;
        -webkit-appearance: none;
        -moz-appearance: none;
        appearance: none;
      }
      
      .day-column {
        width: 35px !important;
        min-width: 35px !important;
        max-width: 35px !important;
      }
    }
  </style>
</head>
<body>

  <div class="main-content">
    <!-- Print Header (hidden on screen, visible on print) -->
    <div class="print-header" style="display: none;">
      <h1>MCC PAYROLL SYSTEM</h1>
      <h2>Instructor Fulltime Timesheet</h2>
      <p>Generated on: <span id="print-date"></span></p>
      <hr>
    </div>

    <!-- Header Section with proper layout -->
    <div class="d-flex justify-content-between align-items-center mb-4">
      <div class="d-flex align-items-center">
        <a href="{{ route('dashboard') }}" class="icon-btn btn-back me-3" title="Back to Dashboard">
          <i class="bi bi-arrow-left"></i>
        </a>
        <h2 class="mb-0">Instructor Fulltime Timesheet</h2>
      </div>
      
      <!-- Action buttons -->
      <div class="d-flex align-items-center">
        <button onclick="openPrintPage()" class="icon-btn print-btn me-2" title="Open Print Page">
          <i class="bi bi-printer"></i>
        </button>
        <a href="{{ route('fulltime.create') }}" class="icon-btn add-btn" title="Add Timesheet Entry">
          <i class="bi bi-plus-lg"></i>
        </a>
      </div>
    </div>

    <div class="table-container mt-3">
      <table>
        <thead>
          <tr>
            <th>NAMES</th>
            <th>DESIGNATION</th>
            <th>Prov. Abr.</th>
            <th>DEPARTMENT</th>
            <th>PERIOD</th>
            <th>Details for<br>Inclusive Hours of Classes</th>
            <th>TOTAL<br>Hour</th>
            <th>Rate per<br>Hour</th>
            <th>Deduction<br>Previous Cut Off</th>
            <th>TOTAL HONORARIUM</th>
            <th class="actions-column">Actions</th>
          </tr>
        </thead>
        <tbody>
          @forelse($timesheets as $timesheet)
          <tr>
            <td class="left">{{ $timesheet->employee_name }}</td>
            <td>{{ ucfirst($timesheet->designation) }}</td>
            <td>
              <input type="text"
                     class="form-control field-input"
                     value="{{ $timesheet->prov_abr }}"
                     data-timesheet-id="{{ $timesheet->id }}"
                     data-field="prov_abr"
                     placeholder="Prov. Abr.">
            </td>
            <td>{{ $timesheet->department }}</td>
            <td>{{ $timesheet->period }}</td>

            <td>
              <input type="text" 
                     class="form-control field-input" 
                     value="{{ $timesheet->details }}" 
                     data-timesheet-id="{{ $timesheet->id }}" 
                     data-field="details"
                     placeholder="Details">
            </td>
            <td id="total-hour-{{ $timesheet->id }}">{{ $timesheet->total_hour }}</td>
            <td>
              <input type="number" 
                     class="form-control field-input" 
                     value="{{ $timesheet->rate_per_hour }}" 
                     data-timesheet-id="{{ $timesheet->id }}" 
                     data-field="rate_per_hour"
                     min="0" 
                     step="0.01"
                     placeholder="0.00">
            </td>
            <td>
              <input type="number" 
                     class="form-control field-input" 
                     value="{{ $timesheet->deduction }}" 
                     data-timesheet-id="{{ $timesheet->id }}" 
                     data-field="deduction"
                     min="0" 
                     step="0.01"
                     placeholder="0.00">
            </td>
            <td id="total-honorarium-{{ $timesheet->id }}">₱{{ number_format($timesheet->total_honorarium, 2) }}</td>
            <td class="actions-column">
              <button type="button" 
                      class="action-btn btn-save me-2" 
                      data-timesheet-id="{{ $timesheet->id }}" 
                      title="Save Changes">
                <i class="bi bi-check-lg"></i>
              </button>
              <form action="{{ route('fulltime.destroy', $timesheet->id) }}" method="POST" style="display:inline;">
                @csrf
                @method('DELETE')
                <button type="submit" class="action-btn btn-delete" onclick="return confirm('Are you sure?')" title="Delete">
                  <i class="bi bi-trash"></i>
                </button>
              </form>
            </td>
          </tr>
          @empty
          <tr>
            <td colspan="10" class="text-center py-5">
              <div class="empty-state">
                <i class="bi bi-inbox" style="font-size: 3rem; color: #6c757d; margin-bottom: 1rem;"></i>
                <h5 class="text-muted">No Timesheet Records Found</h5>
                <p class="text-muted mb-3">There are no fulltime timesheet entries to display.</p>
                <a href="{{ route('fulltime.create') }}" class="btn btn-primary">
                  <i class="bi bi-plus-lg me-2"></i>Add First Timesheet
                </a>
              </div>
            </td>
          </tr>
          @endforelse
        </tbody>
      </table>
    </div>
    
    <!-- Print Footer (hidden on screen, visible on print) -->
    <div class="print-footer" style="display: none;">
      <p>Generated by MCC Payroll System - {{ date('Y-m-d H:i:s') }}</p>
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
          window.open('{{ route('fulltime.print') }}', '_blank');
          
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

    // Check for error message from Laravel session
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

    // Enhanced delete confirmation with SweetAlert
    document.querySelectorAll('.btn-delete').forEach(button => {
      button.addEventListener('click', function(e) {
        e.preventDefault();
        const form = this.closest('form');
        
        Swal.fire({
          title: 'Are you sure?',
          text: "You won't be able to revert this!",
          icon: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#dc3545',
          cancelButtonColor: '#6c757d',
          confirmButtonText: 'Yes, delete it!',
          cancelButtonText: 'Cancel',
          customClass: {
            popup: 'swal-custom-popup',
            title: 'swal-custom-title',
            content: 'swal-custom-content',
            confirmButton: 'swal-custom-button',
            cancelButton: 'swal-custom-cancel-button'
          }
        }).then((result) => {
          if (result.isConfirmed) {
            // Show loading state
            Swal.fire({
              title: 'Deleting...',
              text: 'Please wait while we delete the timesheet.',
              icon: 'info',
              allowOutsideClick: false,
              allowEscapeKey: false,
              showConfirmButton: false,
              didOpen: () => {
                Swal.showLoading();
              },
              customClass: {
                popup: 'swal-custom-popup',
                title: 'swal-custom-title',
                content: 'swal-custom-content'
              }
            });
            form.submit();
          }
        });
      });
    });

    // Manual save functionality only
    document.addEventListener('DOMContentLoaded', function() {

      // Days functionality removed: no automatic mirroring
      // document.querySelectorAll('.day-input').forEach(...);


      // Save button functionality
      const saveButtons = document.querySelectorAll('.btn-save');
      saveButtons.forEach(button => {
        button.addEventListener('click', function() {
          const timesheetId = this.dataset.timesheetId;
          const row = this.closest('tr');
          const fieldInputs = row.querySelectorAll('.field-input');
          const dayInputs = row.querySelectorAll('.day-input');
          
          // Add saving state
          this.classList.add('saving');
          this.innerHTML = '<i class="bi bi-hourglass-split"></i>';
          
          // Collect all field data
          const fieldData = {};
          fieldInputs.forEach(input => {
            const field = input.dataset.field;
            if (field) {
              fieldData[field] = input.value;
            }
          });
          
          // Collect all day data (per day)
          const dayData = {};
          dayInputs.forEach(input => {
            const day = input.dataset.day;
            if (day && input.value) {
              dayData[day] = input.value;
            }
          });
          
          // Save all data
          saveAllData(timesheetId, fieldData, dayData, this);
        });
      });

      function saveAllData(timesheetId, fieldData, dayData, buttonElement) {
        const token = document.querySelector('meta[name="csrf-token"]');
        const csrfToken = token ? token.getAttribute('content') : '';
        
        const promises = [];
        
        // Create promises for each field update
        Object.keys(fieldData).forEach(field => {
          const promise = fetch(`/fulltime/${timesheetId}/update-field`, {
            method: 'POST',
            headers: {
              'Content-Type': 'application/json',
              'X-CSRF-TOKEN': csrfToken,
              'Accept': 'application/json'
            },
            body: JSON.stringify({
              field: field,
              value: fieldData[field]
            })
          });
          promises.push(promise);
        });
        
        // Days functionality removed: skip day updates
        // Object.keys(dayData).forEach(day => { /* no-op */ });
        
        // Wait for all updates to complete
        Promise.all(promises)
          .then(responses => Promise.all(responses.map(r => r.json())))
          .then(results => {
            // Check if all updates were successful
            const allSuccessful = results.every(result => result.success);
            
            if (allSuccessful) {
              // Update totals from results
              results.forEach(result => {
                // Update total hours if it's from a day update
                if (result.total_hour) {
                  const totalHourElement = document.getElementById(`total-hour-${timesheetId}`);
                  if (totalHourElement) {
                    totalHourElement.textContent = result.total_hour;
                  }
                }
                
                // Update total honorarium
                if (result.total_honorarium) {
                  const totalHonorariumElement = document.getElementById(`total-honorarium-${timesheetId}`);
                  if (totalHonorariumElement) {
                    totalHonorariumElement.textContent = `₱${result.total_honorarium}`;
                  }
                }
              });
              
              // Show success state
              buttonElement.classList.remove('saving');
              buttonElement.classList.add('saved');
              buttonElement.innerHTML = '<i class="bi bi-check-lg"></i>';
              
              // Show success message only once
              Swal.fire({
                icon: 'success',
                title: 'Saved!',
                text: 'All changes have been saved successfully.',
                timer: 2000,
                showConfirmButton: false,
                toast: true,
                position: 'top-end'
              });
              
              // Reset button after 3 seconds
              setTimeout(() => {
                buttonElement.classList.remove('saved');
                buttonElement.innerHTML = '<i class="bi bi-check-lg"></i>';
              }, 3000);
            } else {
              throw new Error('Some updates failed');
            }
          })
          .catch(error => {
            console.error('Error saving fields:', error);
            buttonElement.classList.remove('saving', 'saved');
            buttonElement.innerHTML = '<i class="bi bi-check-lg"></i>';
            
            // Show error message
            Swal.fire({
              icon: 'error',
              title: 'Save Failed',
              text: 'Failed to save some changes. Please try again.',
              timer: 3000,
              showConfirmButton: false,
              toast: true,
              position: 'top-end'
            });
          });
      }
    });
  </script>

  <style>
    /* Custom SweetAlert2 styling to match theme */
    .swal-custom-popup {
      border-radius: 15px !important;
      border: 2px solid #dc3545 !important;
    }
    
    .swal-custom-title {
      color: #dc3545 !important;
      font-weight: 700 !important;
    }
    
    .swal-custom-content {
      color: #2c3e50 !important;
    }
    
    .swal-custom-button {
      background: linear-gradient(135deg, #dc3545, #c82333) !important;
      border: none !important;
      border-radius: 8px !important;
      font-weight: 600 !important;
      text-transform: uppercase !important;
      letter-spacing: 0.5px !important;
      padding: 12px 30px !important;
      box-shadow: 0 4px 15px rgba(220, 53, 69, 0.3) !important;
    }
    
    .swal-custom-button:hover {
      background: linear-gradient(135deg, #a71d2a, #b21e2f) !important;
      transform: translateY(-2px) !important;
      box-shadow: 0 6px 20px rgba(220, 53, 69, 0.4) !important;
    }
    
    .swal-custom-cancel-button {
      background: linear-gradient(135deg, #6c757d, #5a6268) !important;
      border: none !important;
      border-radius: 8px !important;
      font-weight: 600 !important;
      text-transform: uppercase !important;
      letter-spacing: 0.5px !important;
      padding: 12px 30px !important;
      box-shadow: 0 4px 15px rgba(108, 117, 125, 0.3) !important;
    }
    
    .swal-custom-cancel-button:hover {
      background: linear-gradient(135deg, #545b62, #4e555b) !important;
      transform: translateY(-2px) !important;
      box-shadow: 0 6px 20px rgba(108, 117, 125, 0.4) !important;
    }

    /* Print button styling */
    .swal-print-button {
      background: linear-gradient(135deg, #0dcaf0, #0aa2c0) !important;
      border: none !important;
      border-radius: 8px !important;
      font-weight: 600 !important;
      text-transform: uppercase !important;
      letter-spacing: 0.5px !important;
      padding: 12px 30px !important;
      box-shadow: 0 4px 15px rgba(13, 202, 240, 0.3) !important;
    }
    
    .swal-print-button:hover {
      background: linear-gradient(135deg, #0aa2c0, #0891a5) !important;
      transform: translateY(-2px) !important;
      box-shadow: 0 6px 20px rgba(13, 202, 240, 0.4) !important;
    }

    /* Print-specific header and footer styling */
    @media print {
      .print-header {
        display: block !important;
        page-break-inside: avoid;
      }
      
      .print-footer {
        display: block !important;
        page-break-inside: avoid;
        position: fixed;
        bottom: 0;
        width: 100%;
      }
      
      .print-header h1 {
        font-size: 20px;
        margin: 0;
        color: black;
      }
      
      .print-header h2 {
        font-size: 16px;
        margin: 5px 0;
        color: black;
      }
      
      .print-header p {
        font-size: 12px;
        margin: 5px 0;
        color: black;
      }
    }
  </style>
</body>
</html>
