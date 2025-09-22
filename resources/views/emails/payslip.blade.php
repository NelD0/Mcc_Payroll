<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Payslip</title>
  <style>
    body { font-family: Arial, sans-serif; background:#f7f9fc; color:#333; }
    .card { max-width: 640px; margin: 24px auto; background: #fff; border-radius: 12px; border:1px solid #e9eef5; box-shadow: 0 6px 20px rgba(0,0,0,.06); overflow:hidden; }
    .header { background: linear-gradient(135deg,#3498db,#2980b9); color:#fff; padding: 18px 24px; }
    .content { padding: 24px; }
    .row { display:flex; justify-content: space-between; margin: 10px 0; }
    .label { color:#6b7280; font-size: 13px; }
    .value { font-weight:700; }
    .total { font-size: 20px; color:#0f172a; }
    .footer { padding: 16px 24px; background:#f3f6fb; color:#64748b; font-size: 12px; text-align:center; }
  </style>
</head>
<body>
  <div class="card">
    <div class="header">
      <h2 style="margin:0;">MCC Payroll - Payslip</h2>
      <p style="margin:4px 0 0; opacity:.95;">Confidential</p>
    </div>
    <div class="content">
      <p>Hi <strong>{{ $name }}</strong>,</p>
      <p>Here is your payslip summary{{ $employeeType ? ' for ' . $employeeType : '' }}{{ $period ? ' (Period: ' . $period . ')' : '' }}.</p>

      <div class="row">
        <div class="label">Employee</div>
        <div class="value">{{ $name }}</div>
      </div>
      @if(!empty($employeeType))
      <div class="row">
        <div class="label">Type</div>
        <div class="value">{{ $employeeType }}</div>
      </div>
      @endif
      @if(!empty($period))
      <div class="row">
        <div class="label">Period</div>
        <div class="value">{{ $period }}</div>
      </div>
      @endif
      <hr style="border:0;border-top:1px solid #e5e7eb; margin:16px 0;">
      <div class="row">
        <div class="label">Total Honorarium</div>
        <div class="value total">₱ {{ number_format($totalHonorarium, 2) }}</div>
      </div>

      <p style="margin-top:18px; color:#6b7280; font-size: 13px;">If you have questions about this payslip, please reply to this email.</p>
    </div>
    <div class="footer">
      <div>MCC Payroll System • This is an automated message</div>
    </div>
  </div>
</body>
</html>