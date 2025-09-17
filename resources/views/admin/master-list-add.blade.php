<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Add Employee - Master List</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
  <style>
    /* Light blue + white theme */
    body{
      font-family:"Segoe UI",Arial,sans-serif;
      background: linear-gradient(135deg, #e9f4ff 0%, #ffffff 65%);
      background-attachment: fixed;
    }
    .main{
      max-width:980px;
      margin:30px auto;
      background:#fff;
      border-radius:12px;
      padding:28px;
      box-shadow:0 8px 24px rgba(45, 104, 163, .12);
      border:1px solid rgba(45, 104, 163, .12);
    }
    .title{display:flex;align-items:center;gap:10px;margin-bottom:20px}
    .badge-soft{background:rgba(45, 123, 211, .10);color:#2d7bd3;border-radius:999px;font-weight:600}
    .days-selector{background:#f7fbff;border-radius:8px;padding:12px;border:1px solid rgba(45, 123, 211, .15)}
    .total-box{border:1px solid #2d7bd3;border-radius:8px;padding:12px}
    .total-box strong{color:#2d7bd3 !important}

    /* Buttons in blue tone */
    .btn-danger{
      background-color:#3498db;
      border-color:#3498db;
    }
    .btn-danger:hover{background-color:#2d7bd3;border-color:#2d7bd3}
  </style>
</head>
<body>
  <div class="container">
    <div class="d-flex justify-content-between align-items-center mt-4 mb-3">
      <h5 class="mb-0 text-muted"><i class="bi bi-list-ul me-2"></i>Master List • Add Employee</h5>
      <div class="d-flex gap-2">
        <a href="{{ route('master.list') }}" class="btn btn-outline-secondary btn-sm"><i class="bi bi-arrow-left me-1"></i>Back</a>
      </div>
    </div>

    <div class="main">
      <div class="title">
        <h4 class="mb-0"><i class="bi bi-person-plus me-2 text-danger"></i>Add Employee</h4>
        <span class="badge badge-soft px-2 py-1">Master List</span>
      </div>

      <form method="POST" action="{{ route('master.list.add.store') }}">
        @csrf

        <div class="row g-3">
          <div class="col-md-6">
            <label class="form-label">Employee Name</label>
            <input type="text" name="employee_name" class="form-control" value="{{ old('employee_name') }}" required>
          </div>
          <div class="col-md-6">
            <label class="form-label">Email</label>
            <input type="email" name="email" class="form-control" value="{{ old('email') }}" placeholder="name@gmail.com">
          </div>
          <div class="col-md-6">
            <label class="form-label">Employee Type</label>
            <select name="employee_type" class="form-select" required>
              <option value="" disabled {{ old('employee_type')? '' : 'selected' }}>Select Type</option>
              <option value="fulltime" {{ old('employee_type')=='fulltime'?'selected':'' }}>Fulltime</option>
              <option value="parttime" {{ old('employee_type')=='parttime'?'selected':'' }}>Parttime</option>
              <option value="staff" {{ old('employee_type')=='staff'?'selected':'' }}>Staff</option>
              <option value="utility" {{ old('employee_type')=='utility'?'selected':'' }}>Utility</option>
            </select>
          </div>

          <div class="col-md-4">
            <label class="form-label">Designation</label>
            <select name="designation" class="form-select" required>
              <option value="" disabled selected>Select Designation</option>
              <option value="instructor" {{ old('designation')=='instructor'?'selected':'' }}>Instructor</option>
              <option value="utility" {{ old('designation')=='utility'?'selected':'' }}>Utility</option>
              <option value="staff" {{ old('designation')=='staff'?'selected':'' }}>Staff</option>
            </select>
          </div>
          <div class="col-md-4">
            <label class="form-label">Province Abbreviation</label>
            <input type="text" name="prov_abr" class="form-control" value="{{ old('prov_abr') }}">
          </div>
          <div class="col-md-4">
            <label class="form-label">Department</label>
            <select name="department" class="form-select" required>
              <option value="" disabled selected>Select Department</option>
              <option value="BSIT" {{ old('department')=='BSIT'?'selected':'' }}>BSIT</option>
              <option value="BSBA" {{ old('department')=='BSBA'?'selected':'' }}>BSBA</option>
              <option value="BSHM" {{ old('department')=='BSHM'?'selected':'' }}>BSHM</option>
              <option value="BSED" {{ old('department')=='BSED'?'selected':'' }}>BSED</option>
              <option value="BEED" {{ old('department')=='BEED'?'selected':'' }}>BEED</option>
              @isset($departments)
                @foreach($departments as $department)
                  <option value="{{ $department->code }}" {{ old('department')==$department->code?'selected':'' }}>{{ $department->name }}</option>
                @endforeach
              @endisset
            </select>
          </div>
        </div>

        <div class="mt-3">
          <label class="form-label">Working Days (Hours per Day)</label>
          <div class="days-selector">
            <div class="row">
              @php($days=['Monday','Tuesday','Wednesday','Thursday','Friday','Saturday'])
              @foreach($days as $index=>$day)
                @php($i=$index+1)
                <div class="col-md-4 col-sm-6 col-12 mb-3">
                  <label class="form-label" for="day{{ $i }}">{{ $day }} Hours</label>
                  <input type="number" id="day{{ $i }}" name="days[{{ $i }}]" class="form-control" min="0" max="24" step="0.25" value="{{ old('days.'.$i) }}" placeholder="0">
                </div>
              @endforeach
            </div>
          </div>
          <small class="text-muted">Enter hours for Monday–Saturday. Leave blank for 0.</small>
        </div>

        <div class="mt-3">
          <label class="form-label">Details for Inclusive Hours of Classes</label>
          <textarea name="details" rows="3" class="form-control">{{ old('details') }}</textarea>
        </div>

        <div class="row mt-3 g-3">
          <div class="col-md-4">
            <label class="form-label">Total Hours</label>
            <input type="number" step="0.01" name="total_hour" id="total_hour" class="form-control" value="{{ old('total_hour') }}">
          </div>
          <div class="col-md-4">
            <label class="form-label">Rate per Hour</label>
            <input type="number" step="0.01" name="rate_per_hour" id="rate_per_hour" class="form-control" value="{{ old('rate_per_hour') }}">
          </div>
          <div class="col-md-4">
            <label class="form-label">Deduction Previous Cut Off</label>
            <input type="number" step="0.01" name="deduction" id="deduction" class="form-control" value="{{ old('deduction',0) }}">
          </div>
        </div>

        <div class="total-box mt-3 d-flex justify-content-between align-items-center">
          <span class="text-muted">Calculated Total Honorarium</span>
          <strong id="calculated-total" class="text-danger">₱0.00</strong>
        </div>

        <div class="d-flex justify-content-end gap-2 mt-4">
          <a href="{{ route('master.list') }}" class="btn btn-outline-secondary"><i class="bi bi-x-lg me-1"></i>Cancel</a>
          <button type="submit" class="btn btn-danger"><i class="bi bi-check-lg me-1"></i>Add Employee</button>
        </div>
      </form>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    const toMoney = (n) => new Intl.NumberFormat('en-PH',{style:'currency',currency:'PHP'}).format(Number(n||0));
    function recalc(){
      const t=parseFloat(document.getElementById('total_hour').value)||0;
      const r=parseFloat(document.getElementById('rate_per_hour').value)||0;
      const d=parseFloat(document.getElementById('deduction').value)||0;
      const a=Math.max(0,(t*r)-d);
      document.getElementById('calculated-total').textContent=toMoney(a);
    }
    ['total_hour','rate_per_hour','deduction'].forEach(id=>{const el=document.getElementById(id); if(el) el.addEventListener('input',recalc);});
    recalc();
  </script>
</body>
</html>