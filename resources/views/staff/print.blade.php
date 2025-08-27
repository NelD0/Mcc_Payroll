<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Print - Staff Timesheet</title>
  
  <style>
    /* Reset and base styles */
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }

    body {
      font-family: "Arial", "Helvetica", sans-serif;
      font-size: 12px;
      line-height: 1.4;
      color: #000;
      background: #fff;
      margin: 0;
      padding: 20px;
    }

    /* Print-specific styles */
    @media print {
      body {
        margin: 0;
        padding: 15px;
        font-size: 10px;
      }
      
      .no-print {
        display: none !important;
      }
      
      .page-break {
        page-break-before: always;
      }
      
      table {
        page-break-inside: auto;
      }
      
      tr {
        page-break-inside: avoid;
        page-break-after: auto;
      }
      
      thead {
        display: table-header-group;
      }
      
      tfoot {
        display: table-footer-group;
      }
    }

    /* Header styles */
    .print-header {
      text-align: center;
      margin-bottom: 30px;
      border-bottom: 2px solid #000;
      padding-bottom: 15px;
    }

    .print-header h1 {
      font-size: 24px;
      font-weight: bold;
      margin-bottom: 5px;
      text-transform: uppercase;
      letter-spacing: 2px;
    }

    .print-header h2 {
      font-size: 18px;
      font-weight: bold;
      margin-bottom: 10px;
      color: #333;
    }

    .print-header .meta-info {
      font-size: 11px;
      color: #666;
      margin-top: 10px;
    }

    .print-header .meta-info span {
      margin: 0 15px;
    }

    /* Table styles */
    .table-container {
      width: 100%;
      overflow-x: auto;
    }

    table {
      width: 100%;
      border-collapse: collapse;
      margin: 0 auto;
      background: #fff;
    }

    th, td {
      border: 1px solid #000;
      padding: 12px 8px;
      text-align: center;
      vertical-align: middle;
      font-size: 12px;
      word-wrap: break-word;
    }

    th {
      background-color: #f0f0f0;
      font-weight: bold;
      font-size: 11px;
      text-transform: uppercase;
      padding: 15px 8px;
    }

    td.left {
      text-align: left;
      padding-left: 6px;
    }

    /* Specific column widths for better layout */
    .col-name {
      width: 40%;
      min-width: 200px;
    }

    .col-designation {
      width: 30%;
      min-width: 150px;
    }

    .col-deduction {
      width: 15%;
      min-width: 100px;
    }

    .col-total {
      width: 15%;
      min-width: 100px;
      font-weight: bold;
    }

    /* Zebra striping for better readability */
    tbody tr:nth-child(even) {
      background-color: #f9f9f9;
    }

    /* Footer styles */
    .print-footer {
      margin-top: 40px;
      text-align: center;
      border-top: 1px solid #ccc;
      padding-top: 15px;
      font-size: 10px;
      color: #666;
    }

    .print-footer .signatures {
      display: flex;
      justify-content: space-between;
      margin-top: 30px;
      padding: 0 50px;
    }

    .signature-block {
      text-align: center;
      width: 200px;
    }

    .signature-line {
      border-bottom: 1px solid #000;
      margin-bottom: 5px;
      height: 40px;
    }

    /* Control buttons */
    .print-controls {
      position: fixed;
      top: 20px;
      right: 20px;
      z-index: 1000;
      background: #fff;
      padding: 15px;
      border-radius: 8px;
      box-shadow: 0 4px 12px rgba(0,0,0,0.15);
      border: 1px solid #ddd;
    }

    .btn {
      display: inline-block;
      padding: 8px 16px;
      margin: 0 5px;
      border: none;
      border-radius: 5px;
      cursor: pointer;
      font-size: 12px;
      font-weight: 600;
      text-decoration: none;
      text-align: center;
      transition: all 0.4s ease;
      box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }

    .btn-print {
      background: linear-gradient(135deg, #0dcaf0, #0aa2c0);
      color: white;
    }

    .btn-print:hover {
      background: linear-gradient(135deg, #28a745, #20c997);
      transform: translateY(-3px) scale(1.05);
      box-shadow: 0 8px 16px rgba(0,0,0,0.2);
    }

    .btn-back {
      background: linear-gradient(135deg, #6c757d, #5a6268);
      color: white;
    }

    .btn-back:hover {
      background: linear-gradient(135deg, #dc3545, #c82333);
      transform: translateY(-3px) scale(1.05);
      box-shadow: 0 8px 16px rgba(0,0,0,0.2);
    }

    /* Summary section */
    .summary-section {
      margin-top: 30px;
      padding: 15px;
      border: 1px solid #ccc;
      background-color: #f8f9fa;
    }

    .summary-title {
      font-weight: bold;
      font-size: 14px;
      margin-bottom: 10px;
      text-align: center;
    }

    .summary-stats {
      display: flex;
      justify-content: space-around;
      text-align: center;
    }

    .stat-item {
      flex: 1;
    }

    .stat-value {
      font-size: 16px;
      font-weight: bold;
      color: #dc3545;
    }

    .stat-label {
      font-size: 11px;
      color: #666;
      margin-top: 5px;
    }
  </style>
</head>
<body>
  <!-- Print Controls (hidden when printing) -->
  <div class="print-controls no-print">
    <button onclick="window.print()" class="btn btn-print">🖨️ Print</button>
    <a href="{{ route('staff.index') }}" class="btn btn-back">← Back</a>
  </div>

  <!-- Print Header -->
  <div class="print-header">
    <h1>MCC PAYROLL SYSTEM</h1>
    <h2>Staff Timesheet</h2>
    <div class="meta-info">
      <span>Generated on: <strong id="print-date"></strong></span>
      <span>Total Records: <strong>{{ count($timesheets) }}</strong></span>
      <span>Print Time: <strong id="print-time"></strong></span>
    </div>
  </div>

  <!-- Main Table -->
  <div class="table-container">
    <table>
      <thead>
        <tr>
          <th rowspan="2" class="col-name">NAMES</th>
          <th rowspan="2" class="col-designation">DESIGNATION</th>
          <th rowspan="2">Prov. Abr.</th>
          <th colspan="15">Days</th>
          <th rowspan="2">Details for<br>Inclusive Hours of Classes</th>
          <th rowspan="2">TOTAL<br>Hour</th>
          <th rowspan="2">Rate per<br>Hour</th>
          <th rowspan="2" class="col-deduction">Deduction<br>Previous Cut Off</th>
          <th rowspan="2" class="col-total">TOTAL HONORARIUM</th>
        </tr>
        <tr>
          <td>1<br>F</td>
          <td>2<br>S</td>
          <td>3<br>S</td>
          <td>4<br>M</td>
          <td>5<br>T</td>
          <td>6<br>W</td>
          <td>7<br>TH</td>
          <td>8<br>F</td>
          <td>9<br>S</td>
          <td>10<br>S</td>
          <td>11<br>M</td>
          <td>12<br>T</td>
          <td>13<br>W</td>
          <td>14<br>TH</td>
          <td>15<br>F</td>
        </tr>
      </thead>
      <tbody>
        @forelse($timesheets as $timesheet)
        <tr>
          <td class="left">{{ $timesheet->employee_name }}</td>
          <td>{{ $timesheet->designation }}</td>
          <td>{{ $timesheet->prov_abr }}</td>
          <td colspan="15">
            @php
              $days = $timesheet->days ?? [];
              if (is_array($days)) {
                echo implode(', ', $days);
              } else {
                echo $days;
              }
            @endphp
          </td>
          <td>{{ $timesheet->details }}</td>
          <td>{{ $timesheet->total_hour }}</td>
          <td>₱{{ number_format($timesheet->rate_per_hour, 2) }}</td>
          <td>{{ $timesheet->deduction }}</td>
          <td class="col-total">₱{{ number_format($timesheet->total_honorarium, 2) }}</td>
        </tr>
        @empty
        <tr>
          <td colspan="22" style="text-align: center; padding: 30px; color: #666; font-style: italic;">
            No staff timesheet records found.
          </td>
        </tr>
        @endforelse
      </tbody>
    </table>
  </div>



  <div class="print-footer">
    
    <div class="signatures">
      <div class="signature-block">
        <div class="signature-line"></div>
        <div><strong>Reviewed By</strong></div>
        <div>WENDELL DENORTE</div>
      </div>
      <div class="signature-block">
        <div class="signature-line"></div>
        <div><strong>Approved By</strong></div>
        <div>DR. FLORIPIS A. MONTECILLO</div>
      </div>
    </div>
    
    <p style="margin-top: 20px;">© {{ date('Y') }} MCC - All Rights Reserved</p>
  </div>

  <script>
    // Set current date and time
    document.addEventListener('DOMContentLoaded', function() {
      const now = new Date();
      const dateOptions = { 
        year: 'numeric', 
        month: 'long', 
        day: 'numeric' 
      };
      const timeOptions = { 
        hour: '2-digit', 
        minute: '2-digit', 
        second: '2-digit' 
      };
      
      document.getElementById('print-date').textContent = now.toLocaleDateString('en-US', dateOptions);
      document.getElementById('print-time').textContent = now.toLocaleTimeString('en-US', timeOptions);
    });

    // Auto-print functionality (optional)
    // Uncomment the line below if you want the page to automatically open print dialog
    // window.addEventListener('load', () => setTimeout(() => window.print(), 1000));
  </script>
</body>
</html>