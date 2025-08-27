<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Add Utility Timesheet</title>
  <!-- Bootstrap 5 CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- Bootstrap Icons -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
  
  <style>
    body {
      font-family: "Segoe UI", Arial, sans-serif;
      margin: 0;
      padding: 0;
      background: #dc3545; /* Red background */
      min-height: 100vh;
      display: flex;
      align-items: flex-start;
      justify-content: center;
    }

    .main-content {
      margin: 30px auto;
      padding: 30px;
      background: #fff; /* White content box */
      border-radius: 8px;
      box-shadow: 0 2px 10px rgba(0,0,0,0.1);
      width: 95%;
      max-width: 600px;
    }

    .main-content h2 {
      color: #dc3545;
      margin-bottom: 25px;
      text-align: center;
      font-weight: 600;
    }

    .form-label {
      color: #333;
      font-weight: 500;
      margin-bottom: 5px;
    }

    .form-control {
      border: 2px solid #e9ecef;
      border-radius: 6px;
      padding: 10px 12px;
      margin-bottom: 15px;
    }

    .form-control:focus {
      border-color: #dc3545;
      box-shadow: 0 0 0 0.2rem rgba(220, 53, 69, 0.25);
    }

    .btn-primary {
      background-color: #dc3545;
      border-color: #dc3545;
      padding: 10px 25px;
      font-weight: 500;
    }

    .btn-primary:hover {
      background-color: #a71d2a;
      border-color: #a71d2a;
    }

    .btn-secondary {
      background-color: #6c757d;
      border-color: #6c757d;
      padding: 10px 25px;
      font-weight: 500;
    }

    .btn-secondary:hover {
      background-color: #545b62;
      border-color: #545b62;
    }

    .button-group {
      display: flex;
      gap: 10px;
      justify-content: center;
      margin-top: 25px;
    }
  </style>
</head>
<body>
  <div class="main-content">
    <h2><i class="bi bi-plus-circle me-2"></i>Add Utility Timesheet</h2>
    
    <form action="{{ route('utility.store') }}" method="POST">
      @csrf
      
      <div class="mb-3">
        <label for="employee_name" class="form-label">Employee Name</label>
        <input type="text" class="form-control" id="employee_name" name="employee_name" required>
      </div>

      <div class="mb-3">
        <label for="designation" class="form-label">Designation</label>
        <select class="form-control" id="designation" name="designation" required>
          <option value="">Select Designation</option>
          <option value="instructor">Instructor</option>
          <option value="utility">Utility</option>
          <option value="staff">Staff</option>
        </select>
      </div>

      <div class="mb-3">
        <label for="prov_abr" class="form-label">Province Abbreviation</label>
        <input type="text" class="form-control" id="prov_abr" name="prov_abr">
      </div>

      <div class="mb-3">
        <label for="days" class="form-label">Working Days</label>
        <div class="days-selector mb-2">
          <div class="row">
            @for($i = 1; $i <= 15; $i++)
              <div class="col-md-3 col-sm-4 col-6 mb-2">
                <div class="form-check">
                  <input class="form-check-input day-checkbox" type="checkbox" value="{{ $i }}" id="day{{ $i }}">
                  <label class="form-check-label" for="day{{ $i }}">
                    Day {{ $i }}
                  </label>
                </div>
              </div>
            @endfor
          </div>
        </div>
        <input type="hidden" class="form-control" id="days" name="days" value="">
        <small class="form-text text-muted">Select the working days for this timesheet</small>
      </div>



      <div class="row">
        <div class="col-md-6">
          <div class="mb-3">
            <label for="total_days" class="form-label">Total Days</label>
            <input type="number" step="0.01" class="form-control" id="total_days" name="total_days" value="0" min="0">
          </div>
        </div>
        <div class="col-md-6">
          <div class="mb-3">
            <label for="rate_per_day" class="form-label">Rate per Day</label>
            <input type="number" step="0.01" class="form-control" id="rate_per_day" name="rate_per_day" value="1200.00">
          </div>
        </div>
      </div>

      <div class="mb-3">
        <label for="deduction" class="form-label">Deduction Previous Cut Off</label>
        <input type="number" step="0.01" class="form-control" id="deduction" name="deduction" value="0">
      </div>

      <div class="button-group">
        <button type="submit" class="btn btn-primary">
          <i class="bi bi-check-lg me-1"></i>Add Timesheet
        </button>
        <a href="{{ route('utility.index') }}" class="btn btn-secondary">
          <i class="bi bi-x-lg me-1"></i>Cancel
        </a>
      </div>
    </form>
  </div>

  <!-- Bootstrap 5 JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  
  <!-- SweetAlert2 -->
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  
  <script>
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

    // Form submission with loading state
    document.querySelector('form').addEventListener('submit', function(e) {
      const submitBtn = document.querySelector('button[type="submit"]');
      const originalText = submitBtn.innerHTML;
      
      // Show loading state
      submitBtn.innerHTML = '<i class="bi bi-hourglass-split me-1"></i>Creating...';
      submitBtn.disabled = true;
      
      // Show loading alert
      Swal.fire({
        title: 'Creating Utility Timesheet...',
        text: 'Please wait while we add the new timesheet.',
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
      
      // Reset button after a delay (in case of validation errors)
      setTimeout(() => {
        submitBtn.innerHTML = originalText;
        submitBtn.disabled = false;
      }, 5000);
    });

    // Auto-calculate total honorarium
    function calculateTotal() {
      const totalDays = parseFloat(document.getElementById('total_days').value) || 0;
      const ratePerDay = parseFloat(document.getElementById('rate_per_day').value) || 0;
      const deduction = parseFloat(document.getElementById('deduction').value) || 0;
      
      const totalHonorarium = (totalDays * ratePerDay) - deduction;
      
      // Show calculated value in a tooltip or alert
      if (totalDays > 0 && ratePerDay > 0) {
        const calculatedValue = totalHonorarium < 0 ? 0 : totalHonorarium;
        console.log('Calculated Total Honorarium:', calculatedValue.toFixed(2));
      }
    }

    // Handle days selection
    function updateDaysField() {
      const checkboxes = document.querySelectorAll('.day-checkbox:checked');
      const selectedDays = Array.from(checkboxes).map(cb => cb.value);
      document.getElementById('days').value = selectedDays.join(',');
    }

    // Add event listeners to day checkboxes
    document.querySelectorAll('.day-checkbox').forEach(checkbox => {
      checkbox.addEventListener('change', updateDaysField);
    });

    // Add event listeners for auto-calculation
    document.getElementById('total_days').addEventListener('input', calculateTotal);
    document.getElementById('rate_per_day').addEventListener('input', calculateTotal);
    document.getElementById('deduction').addEventListener('input', calculateTotal);
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
  </style>
</body>
</html>