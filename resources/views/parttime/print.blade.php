<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Print - Instructor Part-time Timesheet</title>
  
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
      justify-content: space-between;
      flex-wrap: wrap;
      gap: 10px;
    }

    .stat-item {
      text-align: center;
      flex: 1;
      min-width: 150px;
      padding: 15px 10px;
      margin: 0;
    }

    .stat-value {
      font-weight: bold;
      font-size: 14px;
      color: #dc3545;
      margin-bottom: 5px;
    }

    .stat-label {
      font-size: 12px;
      color: #666;
      margin-top: 5px;
    }

    /* Responsive adjustments */
    @media (max-width: 768px) {
      .print-controls {
        position: relative;
        margin-bottom: 20px;
        text-align: center;
      }
      
      table {
        font-size: 8px;
      }
      
      th, td {
        padding: 4px 2px;
        font-size: 8px;
      }
    }
  </style>
</head>
<body>
  <!-- Print Controls (hidden when printing) -->
  <div class="print-controls no-print">
    <button onclick="window.print()" class="btn btn-print">
      🖨️ Print Document
    </button>
    <a href="{{ route('parttime.index') }}" class="btn btn-back">
      ← Back to List
    </a>
  </div>

  <!-- Print Header -->
  <div class="print-header">
    <h1>MCC PAYROLL SYSTEM</h1>
    <h2>Instructor Part-time Timesheet</h2>
    <div class="meta-info">
      <span><strong>Generated:</strong> {{ date('F j, Y \a\t g:i A') }}</span>
      <span><strong>Total Records:</strong> {{ $timesheets->count() }}</span>
      <span><strong>Period:</strong> {{ date('F Y') }}</span>
    </div>
  </div>

  <!-- Main Table -->
  <div class="table-container">
    <table>
      <thead>
        <tr>
          <th class="col-name">EMPLOYEE NAME</th>
          <th class="col-designation">DESIGNATION</th>
          <th class="col-deduction">DEDUCTION<br>PREVIOUS CUT OFF</th>
          <th class="col-total">TOTAL HONORARIUM</th>
        </tr>
      </thead>
      <tbody>
        @forelse($timesheets as $timesheet)
        <tr>
          <td class="left col-name">{{ $timesheet->employee_name }}</td>
          <td class="col-designation">{{ $timesheet->designation }}</td>
          <td class="col-deduction">₱{{ number_format($timesheet->deduction, 2) }}</td>
          <td class="col-total">₱{{ number_format($timesheet->total_honorarium, 2) }}</td>
        </tr>
        @empty
        <tr>
          <td colspan="4" style="text-align: center; padding: 20px; font-style: italic;">
            No timesheet records found.
          </td>
        </tr>
        @endforelse
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
    // Auto-print functionality (optional)
    document.addEventListener('DOMContentLoaded', function() {
      // Uncomment the line below if you want the page to auto-print when loaded
      // window.print();
    });

    // Print button functionality
    function printDocument() {
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