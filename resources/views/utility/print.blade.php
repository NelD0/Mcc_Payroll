<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Print - Utility Timesheet</title>
  
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

    /* Table styles */
    .table-container {
      width: 100%;
      overflow-x: auto;
    }

    table {
      width: 100%;
      border-collapse: collapse;
      margin: 0;
      font-size: 10px;
    }

    th, td {
      border: 1px solid #000;
      padding: 6px 4px;
      text-align: center;
      vertical-align: middle;
      word-wrap: break-word;
    }

    th {
      background-color: #f0f0f0;
      font-weight: bold;
      font-size: 9px;
      text-transform: uppercase;
    }

    td.left {
      text-align: left;
    }

    td.right {
      text-align: right;
    }

    /* Zebra striping for better readability */
    tbody tr:nth-child(even) {
      background-color: #f9f9f9;
    }

    /* Column widths */
    .col-name {
      width: 15%;
      min-width: 100px;
    }

    .col-designation {
      width: 12%;
      min-width: 80px;
    }

    .col-prov {
      width: 8%;
      min-width: 50px;
    }

    .col-day {
      width: 3%;
      min-width: 25px;
    }

    .col-details {
      width: 15%;
      min-width: 100px;
    }

    .col-hour {
      width: 8%;
      min-width: 60px;
    }

    .col-rate {
      width: 8%;
      min-width: 60px;
    }

    .col-deduction {
      width: 8%;
      min-width: 60px;
    }

    .col-total {
      width: 10%;
      min-width: 80px;
      font-weight: bold;
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

    /* Control buttons (hidden in print) */
    .print-controls {
      position: fixed;
      top: 20px;
      right: 20px;
      z-index: 1000;
      display: flex;
      gap: 10px;
    }

    .print-btn, .back-btn {
      padding: 10px 20px;
      border: none;
      border-radius: 5px;
      cursor: pointer;
      font-weight: bold;
      text-decoration: none;
      display: inline-flex;
      align-items: center;
      gap: 5px;
    }

    .print-btn {
      background-color: #0dcaf0;
      color: white;
    }

    .print-btn:hover {
      background-color: #0aa2c0;
    }

    .back-btn {
      background-color: #6c757d;
      color: white;
    }

    .back-btn:hover {
      background-color: #545b62;
    }

    /* Summary section */
    .summary-section {
      margin-top: 20px;
      padding: 15px;
      border: 2px solid #000;
      background-color: #f8f9fa;
    }

    .summary-title {
      font-weight: bold;
      font-size: 14px;
      margin-bottom: 10px;
      text-align: center;
      text-transform: uppercase;
    }

    .summary-stats {
      display: flex;
      justify-content: space-around;
      flex-wrap: wrap;
      gap: 15px;
    }

    .stat-item {
      text-align: center;
      flex: 1;
      min-width: 120px;
    }

    .stat-label {
      font-size: 10px;
      color: #666;
      margin-bottom: 2px;
    }

    .stat-value {
      font-size: 12px;
      font-weight: bold;
      color: #000;
    }

    /* Responsive adjustments */
    @media screen and (max-width: 768px) {
      body {
        padding: 10px;
      }
      
      table {
        font-size: 8px;
      }
      
      th, td {
        padding: 3px 2px;
      }
      
      .print-controls {
        position: relative;
        top: auto;
        right: auto;
        margin-bottom: 20px;
        justify-content: center;
      }
    }
  </style>
</head>
<body>
  <!-- Print Controls (hidden during print) -->
  <div class="print-controls no-print">
    <button onclick="window.print()" class="print-btn">
      🖨️ Print
    </button>
    <a href="{{ route('utility.index') }}" class="back-btn">
      ← Back to List
    </a>
  </div>

  <!-- Print Header -->
  <div class="print-header">
    <h1>MCC PAYROLL SYSTEM</h1>
    <h2>Utility Timesheet</h2>
    <div class="meta-info">
      <p>Generated on: <span id="print-date"></span></p>
      <p>Total Records: {{ count($timesheets) }}</p>
    </div>
  </div>

  <!-- Main Table -->
  <div class="table-container">
    <table>
      <thead>
        <tr>
          <th rowspan="2" class="col-name">NAMES</th>
          <th rowspan="2" class="col-designation">DESIGNATION</th>
          <th rowspan="2" class="col-prov">Prov. Abr.</th>
          <th colspan="15" class="col-days">Days</th>
          <th rowspan="2" class="col-hour">TOTAL<br>Days</th>
          <th rowspan="2" class="col-rate">Rate per<br>Day</th>
          <th rowspan="2" class="col-deduction">Deduction<br>Previous Cut Off</th>
          <th rowspan="2" class="col-total">TOTAL HONORARIUM</th>
        </tr>
        <tr>
          <th class="col-day">1<br>F</th>
          <th class="col-day">2<br>S</th>
          <th class="col-day">3<br>S</th>
          <th class="col-day">4<br>M</th>
          <th class="col-day">5<br>T</th>
          <th class="col-day">6<br>W</th>
          <th class="col-day">7<br>TH</th>
          <th class="col-day">8<br>F</th>
          <th class="col-day">9<br>S</th>
          <th class="col-day">10<br>S</th>
          <th class="col-day">11<br>M</th>
          <th class="col-day">12<br>T</th>
          <th class="col-day">13<br>W</th>
          <th class="col-day">14<br>TH</th>
          <th class="col-day">15<br>F</th>
        </tr>
      </thead>
      <tbody>
        @php
          $totalDays = 0;
          $totalHonorarium = 0;
        @endphp
        
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
          <td class="right">{{ number_format($timesheet->total_days, 2) }}</td>
          <td class="right">₱{{ number_format($timesheet->rate_per_day, 2) }}</td>
          <td class="right">{{ number_format($timesheet->deduction, 2) }}</td>
          <td class="right">₱{{ number_format($timesheet->total_honorarium, 2) }}</td>
        </tr>
        @php
          $totalDays += $timesheet->total_days;
          $totalHonorarium += $timesheet->total_honorarium;
        @endphp
        @empty
        <tr>
          <td colspan="20" style="text-align: center; padding: 20px; color: #666;">
            No utility timesheet records found.
          </td>
        </tr>
        @endforelse
        
        @if(count($timesheets) > 0)
        <!-- Summary Row -->
        <tr style="background-color: #e9ecef; font-weight: bold;">
          <td colspan="16" class="right" style="font-weight: bold;">TOTALS:</td>
          <td class="right">{{ number_format($totalDays, 2) }}</td>
          <td class="right">-</td>
          <td class="right">-</td>
          <td class="right">₱{{ number_format($totalHonorarium, 2) }}</td>
        </tr>
        @endif
      </tbody>
    </table>
  </div>


  <!-- Print Footer -->
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
    // Set print date when page loads
    document.addEventListener('DOMContentLoaded', function() {
      const printDateElement = document.getElementById('print-date');
      if (printDateElement) {
        const now = new Date();
        const options = { 
          year: 'numeric', 
          month: 'long', 
          day: 'numeric',
          hour: '2-digit',
          minute: '2-digit',
          hour12: true
        };
        printDateElement.textContent = now.toLocaleDateString('en-US', options);
      }
    });

    // Auto-print functionality (optional)
    // Uncomment the line below if you want the page to automatically open print dialog
    // window.addEventListener('load', () => setTimeout(() => window.print(), 500));

    // Print button functionality
    function printPage() {
      window.print();
    }

    // Keyboard shortcut for printing (Ctrl+P)
    document.addEventListener('keydown', function(e) {
      if (e.ctrlKey && e.key === 'p') {
        e.preventDefault();
        window.print();
      }
    });
  </script>
</body>
</html>