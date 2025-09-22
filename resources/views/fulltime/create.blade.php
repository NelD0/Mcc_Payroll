<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Add Fulltime Timesheet</title>
  <!-- Bootstrap 5 CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- Bootstrap Icons -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
  
  <style>
    body {
      font-family: "Segoe UI", Arial, sans-serif;
      margin: 0;
      padding: 0;
      background: #add8e6; /* Light blue background */
      min-height: 100vh;
      display: flex;
      align-items: flex-start;
      justify-content: center;
      position: relative;
    }

    /* Add subtle pattern overlay */
    body::before {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      right: 0;
      bottom: 0;
      background-image: radial-gradient(circle at 25% 25%, rgba(255,255,255,0.1) 2px, transparent 2px);
      background-size: 50px 50px;
      pointer-events: none;
    }

    .main-content {
      margin: 30px auto;
      padding: 35px;
      background: #fff;
      border-radius: 15px;
      box-shadow: 0 8px 32px rgba(0,0,0,0.2), 0 2px 8px rgba(220, 53, 69, 0.1);
      width: 95%;
      max-width: 700px;
      position: relative;
      border: 1px solid rgba(220, 53, 69, 0.1);
      animation: slideInUp 0.6s ease-out;
    }

    /* Add subtle red accent border */
    .main-content::before {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      right: 0;
      height: 4px;
      background: linear-gradient(90deg, #dc3545, #ff6b7a, #dc3545);
      border-radius: 15px 15px 0 0;
    }

    @keyframes slideInUp {
      from {
        opacity: 0;
        transform: translateY(30px);
      }
      to {
        opacity: 1;
        transform: translateY(0);
      }
    }

    .main-content h2 {
      color: #dc3545;
      margin-bottom: 30px;
      text-align: center;
      font-weight: 700;
      font-size: 1.8rem;
      text-shadow: 0 1px 2px rgba(220, 53, 69, 0.1);
    }

    .form-label {
      color: #2c3e50;
      font-weight: 600;
      margin-bottom: 6px;
      font-size: 0.9rem;
      display: block;
    }

    .form-control {
      border: 2px solid #e9ecef;
      border-radius: 6px;
      padding: 8px 12px;
      margin-bottom: 15px;
      transition: all 0.3s ease;
      font-size: 0.9rem;
      background-color: #fafafa;
      height: auto;
    }

    .form-control:focus {
      border-color: #dc3545;
      box-shadow: 0 0 0 0.25rem rgba(220, 53, 69, 0.15);
      background-color: #fff;
      transform: translateY(-1px);
    }

    .form-control:hover {
      border-color: #dc3545;
      background-color: #fff;
    }

    .btn-primary {
      background: linear-gradient(135deg, #dc3545, #c82333);
      border: none;
      padding: 10px 25px;
      font-weight: 600;
      border-radius: 6px;
      transition: all 0.3s ease;
      text-transform: uppercase;
      letter-spacing: 0.5px;
      font-size: 0.85rem;
      box-shadow: 0 4px 15px rgba(220, 53, 69, 0.3);
    }

    .btn-primary:hover {
      background: linear-gradient(135deg, #a71d2a, #b21e2f);
      transform: translateY(-2px);
      box-shadow: 0 6px 20px rgba(220, 53, 69, 0.4);
    }

    .btn-primary:active {
      transform: translateY(0);
    }

    .btn-secondary {
      background: linear-gradient(135deg, #6c757d, #5a6268);
      border: none;
      padding: 10px 25px;
      font-weight: 600;
      border-radius: 6px;
      transition: all 0.3s ease;
      text-transform: uppercase;
      letter-spacing: 0.5px;
      font-size: 0.85rem;
      box-shadow: 0 4px 15px rgba(108, 117, 125, 0.3);
    }

    .btn-secondary:hover {
      background: linear-gradient(135deg, #545b62, #4e555b);
      transform: translateY(-2px);
      box-shadow: 0 6px 20px rgba(108, 117, 125, 0.4);
    }

    .btn-secondary:active {
      transform: translateY(0);
    }

    .button-group {
      display: flex;
      gap: 15px;
      justify-content: center;
      margin-top: 35px;
      flex-wrap: wrap;
    }

    /* Enhanced row styling */
    .row .col-md-6 {
      padding-left: 10px;
      padding-right: 10px;
    }

    /* Responsive improvements */
    @media (max-width: 768px) {
      .main-content {
        margin: 15px;
        padding: 25px;
        width: calc(100% - 30px);
      }
      
      .button-group {
        flex-direction: column;
        align-items: center;
      }
      
      .btn-primary, .btn-secondary {
        width: 100%;
        max-width: 200px;
      }
      
      .main-content h2 {
        font-size: 1.5rem;
      }
    }

    /* Add focus indicators for accessibility */
    .form-control:focus,
    .btn:focus {
      outline: 2px solid #dc3545;
      outline-offset: 2px;
    }

    /* Enhanced textarea styling */
    textarea.form-control {
      resize: vertical;
      min-height: 80px;
      padding: 8px 12px;
    }

    /* Input group enhancements */
    .mb-3 {
      position: relative;
      margin-bottom: 1rem;
    }

    /* Add subtle hover effects to form groups */
    .mb-3:hover .form-label {
      color: #dc3545;
      transition: color 0.3s ease;
    }

    /* Days selector styling */
    .days-selector {
      background: #f8f9fa;
      border-radius: 6px;
      padding: 12px;
      border: 2px solid #e9ecef;
      transition: border-color 0.3s ease;
    }

    .days-selector:hover {
      border-color: #dc3545;
    }

    .form-check {
      margin-bottom: 0;
    }

    .form-check-input {
      margin-top: 0.25rem;
    }

    .form-check-label {
      font-size: 0.9rem;
      color: #495057;
      cursor: pointer;
    }

    .form-check-input:checked + .form-check-label {
      color: #dc3545;
      font-weight: 600;
    }

    .form-check-input:checked {
      background-color: #dc3545;
      border-color: #dc3545;
    }

    /* Total honorarium display */
    .total-display {
      background: linear-gradient(135deg, #f8f9fa, #e9ecef);
      border: 2px solid #dc3545;
      border-radius: 8px;
      padding: 15px;
      text-align: center;
      margin-top: 10px;
    }

    .total-display .amount {
      font-size: 1.5rem;
      font-weight: 700;
      color: #dc3545;
      margin: 0;
    }

    .total-display .label {
      font-size: 0.9rem;
      color: #6c757d;
      margin: 0;
    }
  </style>
</head>
<body>
  <div class="main-content">
    <h2><i class="bi bi-plus-circle me-2"></i>Add Fulltime Timesheet</h2>
    
    <form action="{{ route('fulltime.store') }}" method="POST">
      @csrf
      
      <div class="mb-3">
        <label for="employee_name" class="form-label">Employee Name</label>
        <input type="text" class="form-control" id="employee_name" name="employee_name" required>
      </div>

      <div class="mb-3">
        <label for="email" class="form-label">Email</label>
        <input type="email" class="form-control" id="email" name="email" placeholder="name@gmail.com">
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
        <label for="department" class="form-label">Department</label>
        <select class="form-control" id="department" name="department" required>
          <option value="" disabled selected>Select Department</option>
          <!-- Fixed options to match enum -->
          <option value="BSIT">BSIT</option>
          <option value="BSBA">BSBA</option>
          <option value="BSHM">BSHM</option>
          <option value="BSED">BSED</option>
          <option value="BEED">BEED</option>
          
          <!-- Dynamic options, if you still want to show other active departments -->
          @foreach($departments as $department)
            <option value="{{ $department->code }}">{{ $department->name }}</option>
          @endforeach
        </select>
      </div>

      <!-- Days in Fulltime Section -->
      <div class="mb-3">
        <label class="form-label">Days in Fulltime</label>
        <div class="row">
          <div class="col-md-6">
            <div class="form-check">
              <input class="form-check-input" type="radio" name="period" id="period_1_15" value="1-15" checked>
              <label class="form-check-label" for="period_1_15">
                1-15
              </label>
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-check">
              <input class="form-check-input" type="radio" name="period" id="period_16_30" value="16-30">
              <label class="form-check-label" for="period_16_30">
                16-30
              </label>
            </div>
          </div>
        </div>
      </div>

      <!-- Working Days (Mon-Sat) -->
      <div class="mb-3">
        <label class="form-label">Working Days (Mon-Sat)</label>
        <div class="days-selector">
          <div class="row">
            <div class="col-12 mb-2">
              <label for="mon_hours" class="form-label">Monday Hours</label>
              <input type="number" step="0.01" class="form-control day-hours" id="mon_hours" name="mon_hours" min="0">
            </div>
          </div>
          <div class="row">
            <div class="col-md-4 mb-2">
              <label for="tue_hours" class="form-label">Tuesday</label>
              <input type="number" step="0.01" class="form-control day-hours" id="tue_hours" name="tue_hours" min="0" readonly>
            </div>
            <div class="col-md-4 mb-2">
              <label for="wed_hours" class="form-label">Wednesday</label>
              <input type="number" step="0.01" class="form-control day-hours" id="wed_hours" name="wed_hours" min="0" readonly>
            </div>
            <div class="col-md-4 mb-2">
              <label for="thu_hours" class="form-label">Thursday</label>
              <input type="number" step="0.01" class="form-control day-hours" id="thu_hours" name="thu_hours" min="0" readonly>
            </div>
          </div>
          <div class="row">
            <div class="col-md-6 mb-2">
              <label for="fri_hours" class="form-label">Friday</label>
              <input type="number" step="0.01" class="form-control day-hours" id="fri_hours" name="fri_hours" min="0" readonly>
            </div>
            <div class="col-md-6 mb-2">
              <label for="sat_hours" class="form-label">Saturday</label>
              <input type="number" step="0.01" class="form-control day-hours" id="sat_hours" name="sat_hours" min="0" readonly>
            </div>
          </div>
          <div class="row">
            <div class="col-12">
              <label for="sun_hours" class="form-label">Sunday</label>
              <input type="number" step="0.01" class="form-control" id="sun_hours" name="sun_hours" min="0" readonly>
            </div>
          </div>
        </div>
        <small class="form-text text-muted">Enter hours for Monday, and Tue-Sat will be automatically populated with the same value</small>
      </div>

      <!-- Number Input -->
      <div class="mb-3">
        <label for="number_of_days" class="form-label">Number of Days</label>
        <input type="number" class="form-control" id="number_of_days" name="number_of_days" min="1" max="7" value="6">
      </div>

      <div class="mb-3">
        <label for="details" class="form-label">Details for Inclusive Hours of Classes</label>
        <textarea class="form-control" id="details" name="details" rows="3"></textarea>
      </div>

      <div class="row">
        <div class="col-md-6">
          <div class="mb-3">
            <label for="total_hour" class="form-label">Total Hours</label>
            <input type="number" step="0.01" class="form-control" id="total_hour" name="total_hour">
          </div>
        </div>
        <div class="col-md-6">
          <div class="mb-3">
            <label for="rate_per_hour" class="form-label">Rate per Hour</label>
            <input type="number" step="0.01" class="form-control" id="rate_per_hour" name="rate_per_hour">
          </div>
        </div>
      </div>

      <div class="mb-3">
        <label for="deduction" class="form-label">Deduction Previous Cut Off</label>
        <input type="number" step="0.01" class="form-control" id="deduction" name="deduction" value="0">
      </div>

      <!-- Total Honorarium Display -->
      <div class="total-display">
        <p class="label">Calculated Total Honorarium</p>
        <p class="amount" id="calculated-total">₱0.00</p>
      </div>

      <div class="button-group">
        <button type="submit" class="btn btn-primary">
          <i class="bi bi-check-lg me-1"></i>Add Timesheet
        </button>
        <a href="{{ route('fulltime.index') }}" class="btn btn-secondary">
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
        title: 'Creating Timesheet...',
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

    // Handle Monday hours input -> populate all working days (Tue-Sat)
    function populateWorkingDaysFromMonday() {
      const monHours = parseFloat(document.getElementById('mon_hours').value) || 0;
      const workingDays = ['tue_hours', 'wed_hours', 'thu_hours', 'fri_hours', 'sat_hours'];

      workingDays.forEach(day => {
        document.getElementById(day).value = monHours;
      });

      // Calculate total hours (sum of all day hours)
      calculateTotalHours();
      calculateTotal();
    }

    // Calculate total hours from all day inputs
    function calculateTotalHours() {
      const dayInputs = ['mon_hours', 'tue_hours', 'wed_hours', 'thu_hours', 'fri_hours', 'sat_hours', 'sun_hours'];
      let total = 0;

      dayInputs.forEach(day => {
        const value = parseFloat(document.getElementById(day).value) || 0;
        total += value;
      });

      const totalHourEl = document.getElementById('total_hour');
      if (totalHourEl) totalHourEl.value = total.toFixed(2);
    }

    // Event listener for Monday hours input
    document.getElementById('mon_hours').addEventListener('input', populateWorkingDaysFromMonday);

    // Auto-calculate total honorarium (manual hours)
  function calculateTotal() {
    const totalHour = parseFloat(document.getElementById('total_hour').value) || 0;
    const ratePerHour = parseFloat(document.getElementById('rate_per_hour').value) || 0;
    const deduction = parseFloat(document.getElementById('deduction').value) || 0;
    
    const totalHonorarium = (totalHour * ratePerHour) - deduction;
    const calculatedValue = totalHonorarium < 0 ? 0 : totalHonorarium;
    
    document.getElementById('calculated-total').textContent =
      '₱' + calculatedValue.toFixed(2);

    const display = document.querySelector('.total-display');
    if (calculatedValue > 0) {
      display.style.borderColor = '#198754';
      display.querySelector('.amount').style.color = '#198754';
    } else {
      display.style.borderColor = '#dc3545';
      display.querySelector('.amount').style.color = '#dc3545';
    }
  }

  // Event listeners only for honorarium fields
  document.getElementById('total_hour').addEventListener('input', calculateTotal);
  document.getElementById('rate_per_hour').addEventListener('input', calculateTotal);
  document.getElementById('deduction').addEventListener('input', calculateTotal);

  // Initial calculation
  calculateTotal();

  // Auto-select period based on current date and month
  function setPeriodBasedOnDate() {
    const today = new Date();
    const day = today.getDate();
    const year = today.getFullYear();
    const month = today.getMonth();

    // Get the last day of the current month
    const lastDayOfMonth = new Date(year, month + 1, 0).getDate();

    const period1_15 = document.getElementById('period_1_15');
    const period16_30 = document.getElementById('period_16_30');
    const period16_30_label = document.querySelector('label[for="period_16_30"]');

    // Update the second period label and value based on month's last day
    const secondPeriodStart = 16;
    const secondPeriodEnd = lastDayOfMonth;
    const secondPeriodText = `${secondPeriodStart}-${secondPeriodEnd}`;

    period16_30.value = secondPeriodText;
    period16_30_label.textContent = secondPeriodText;

    // Auto-select based on current day
    if (day <= 15) {
      period1_15.checked = true;
    } else {
      period16_30.checked = true;
    }
  }

  // Set period on load
  setPeriodBasedOnDate();

  // Initial populate
  populateWorkingDaysFromMonday();
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
