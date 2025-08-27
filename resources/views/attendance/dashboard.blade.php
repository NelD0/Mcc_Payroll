<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Attendance Checker Dashboard</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            background: linear-gradient(135deg, #007bff, #4da6ff, #e3f2fd);
            min-height: 100vh;
        }

        .header {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            padding: 1rem 2rem;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .header h1 {
            color: #007bff;
            font-size: 1.5rem;
        }

        .user-info {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .user-badge {
            background: #007bff;
            color: white;
            padding: 0.5rem 1rem;
            border-radius: 20px;
            font-size: 0.9rem;
            font-weight: bold;
        }

        .logout-btn {
            background: #dc3545;
            color: white;
            padding: 0.5rem 1rem;
            border: none;
            border-radius: 5px;
            text-decoration: none;
            font-size: 0.9rem;
            transition: background 0.3s;
        }

        .logout-btn:hover {
            background: #c82333;
        }

        .container {
            max-width: 1200px;
            margin: 2rem auto;
            padding: 0 1rem;
        }

        .dashboard-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 2rem;
            margin-bottom: 2rem;
        }

        .card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 10px;
            padding: 2rem;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease;
        }

        .card:hover {
            transform: translateY(-5px);
        }

        .card h3 {
            color: #007bff;
            margin-bottom: 1rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .card p {
            color: #666;
            margin-bottom: 1rem;
        }

        .btn {
            display: inline-block;
            background: #007bff;
            color: white;
            padding: 0.75rem 1.5rem;
            border: none;
            border-radius: 5px;
            text-decoration: none;
            font-size: 1rem;
            cursor: pointer;
            transition: background 0.3s;
        }

        .btn:hover {
            background: #0056b3;
        }

        .btn-secondary {
            background: #6c757d;
        }

        .btn-secondary:hover {
            background: #545b62;
        }

        .course-badge {
            background: #28a745;
            color: white;
            padding: 0.25rem 0.75rem;
            border-radius: 15px;
            font-size: 0.8rem;
            font-weight: bold;
            text-transform: uppercase;
        }

        .quick-actions {
            display: flex;
            gap: 1rem;
            flex-wrap: wrap;
            margin-top: 1rem;
        }

        @media (max-width: 768px) {
            .header {
                flex-direction: column;
                gap: 1rem;
                text-align: center;
            }

            .user-info {
                flex-direction: column;
                gap: 0.5rem;
            }

            .dashboard-grid {
                grid-template-columns: 1fr;
            }

            .quick-actions {
                flex-direction: column;
            }
        }
    </style>
    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <div class="header">
        <h1><i class="fas fa-clipboard-check"></i> Attendance Checker Dashboard</h1>
        <div class="user-info">
            <div class="user-badge">
                <i class="fas fa-user"></i> {{ session('user_name') }}
                @if(session('user_course'))
                    <span class="course-badge">{{ strtoupper(session('user_course')) }}</span>
                @endif
            </div>
            <a href="{{ url('/logout') }}" class="logout-btn">
                <i class="fas fa-sign-out-alt"></i> Logout
            </a>
        </div>
    </div>

    <div class="container">
        <!-- Dynamic Department Section -->
        <div class="card">
            @php
                $userCourse = strtoupper(session('user_course', 'BSIT'));
                $courseInfo = [
                    'BSIT' => [
                        'name' => 'BSIT Department',
                        'description' => 'Information Technology Instructors Attendance Management',
                        'icon' => 'fas fa-laptop-code',
                        'color' => 'bsit-color'
                    ],
                    'BSBA' => [
                        'name' => 'BSBA Department', 
                        'description' => 'Business Administration Instructors Attendance Management',
                        'icon' => 'fas fa-briefcase',
                        'color' => 'bsba-color'
                    ],
                    'BSHM' => [
                        'name' => 'BSHM Department',
                        'description' => 'Hospitality Management Instructors Attendance Management', 
                        'icon' => 'fas fa-hotel',
                        'color' => 'bshm-color'
                    ],
                    'EDUCATION' => [
                        'name' => 'Education Department',
                        'description' => 'Education Faculty Attendance Management',
                        'icon' => 'fas fa-graduation-cap',
                        'color' => 'education-color'
                    ]
                ];
                $currentCourse = $courseInfo[$userCourse] ?? $courseInfo['BSIT'];
            @endphp
            
            <h3><i class="{{ $currentCourse['icon'] }}"></i> {{ $currentCourse['name'] }}</h3>
            <p>{{ $currentCourse['description'] }}</p>
            <div class="course-selection">
                <div class="single-course-grid">
                    <div class="course-card" onclick="loadAttendance('{{ strtolower($userCourse) }}')">
                        <div class="course-icon {{ $currentCourse['color'] }}">
                            <i class="{{ $currentCourse['icon'] }}"></i>
                        </div>
                        <h4>{{ $userCourse }} Attendance</h4>
                        <p>{{ $currentCourse['name'] }}</p>
                        <span class="course-count" id="course-count">0 Instructors</span>
                        <div class="start-btn">
                            <i class="fas fa-play"></i> Start Attendance
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Attendance Management Section -->
        <div id="attendance-section" class="card" style="display: none;">
            <h3 id="attendance-title"><i class="fas fa-calendar-check"></i> Attendance Management</h3>
            
            <!-- Date Navigation -->
            <div class="date-controls">
                <button class="btn btn-secondary" onclick="previousWeek()">
                    <i class="fas fa-chevron-left"></i> Previous Week
                </button>
                <span class="current-week" id="current-week"></span>
                <button class="btn btn-secondary" onclick="nextWeek()">
                    Next Week <i class="fas fa-chevron-right"></i>
                </button>
                <button class="btn" onclick="goToCurrentWeek()">
                    <i class="fas fa-calendar-day"></i> Current Week
                </button>
            </div>

            <!-- Attendance Table -->
            <div class="table-container" id="attendance-table-container">
                <div class="loading-spinner" id="loading-spinner">
                    <i class="fas fa-spinner fa-spin"></i> Loading attendance data...
                </div>
                <table id="attendance-table" style="display: none;">
                    <thead>
                        <tr>
                            <th style="width: 40px;">
                                <input type="checkbox" id="select-all-checkbox" onchange="toggleSelectAll()" title="Select All">
                            </th>
                            <th>Employee ID</th>
                            <th>Instructor Name</th>
                            <th>Designation</th>
                            <th>Type</th>
                            <th class="day-header">Mon<br><small id="mon-date"></small></th>
                            <th class="day-header">Tue<br><small id="tue-date"></small></th>
                            <th class="day-header">Wed<br><small id="wed-date"></small></th>
                            <th class="day-header">Thu<br><small id="thu-date"></small></th>
                            <th class="day-header">Fri<br><small id="fri-date"></small></th>
                            <th class="day-header">Sat<br><small id="sat-date"></small></th>
                        </tr>
                    </thead>
                    <tbody id="attendance-tbody">
                        <!-- Dynamic content will be loaded here -->
                    </tbody>
                </table>
            </div>

            <!-- Quick Actions -->
            <div class="quick-actions">
                <button class="btn" onclick="markAllPresent()">
                    <i class="fas fa-check-double"></i> Mark All Present
                </button>
                <button class="btn btn-secondary" onclick="markAllAbsent()">
                    <i class="fas fa-times"></i> Mark All Absent
                </button>
                <button class="btn" onclick="saveAttendance()">
                    <i class="fas fa-save"></i> Save Changes
                </button>
                <button class="btn btn-secondary" onclick="exportAttendance()">
                    <i class="fas fa-file-export"></i> Export Report
                </button>
            </div>

            <!-- Bulk Actions -->
            <div class="bulk-actions" id="bulk-actions" style="display: none;">
                <div class="bulk-actions-info">
                    <span id="selected-count">0</span> employee(s) selected
                </div>
                <div class="bulk-actions-buttons">
                    <button class="btn btn-danger" onclick="bulkDeleteSelected()">
                        <i class="fas fa-trash"></i> Delete Selected
                    </button>
                    <button class="btn btn-secondary" onclick="clearSelection()">
                        <i class="fas fa-times"></i> Clear Selection
                    </button>
                </div>
            </div>
        </div>

        <!-- Statistics Card -->
        <div id="stats-section" class="card" style="display: none;">
            <h3><i class="fas fa-chart-pie"></i> Attendance Statistics</h3>
            <div class="stats-grid">
                <div class="stat-item">
                    <div class="stat-value" id="total-instructors">0</div>
                    <div class="stat-label">Total Instructors</div>
                </div>
                <div class="stat-item">
                    <div class="stat-value text-success" id="present-today">0</div>
                    <div class="stat-label">Present Today</div>
                </div>
                <div class="stat-item">
                    <div class="stat-value text-danger" id="absent-today">0</div>
                    <div class="stat-label">Absent Today</div>
                </div>
                <div class="stat-item">
                    <div class="stat-value" id="attendance-rate">0%</div>
                    <div class="stat-label">Attendance Rate</div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .course-selection {
            margin-top: 1rem;
        }

        .single-course-grid {
            display: flex;
            justify-content: center;
            margin-top: 1rem;
        }

        .course-card {
            background: rgba(255, 255, 255, 0.9);
            border: 2px solid #e3f2fd;
            border-radius: 15px;
            padding: 2rem;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s ease;
            max-width: 350px;
            width: 100%;
        }

        .course-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 12px 35px rgba(0, 123, 255, 0.25);
            border-color: #007bff;
        }
        
        .course-card .start-btn {
            background: linear-gradient(135deg, #dc3545, #c82333);
        }
        
        .course-card .start-btn:hover {
            background: linear-gradient(135deg, #c82333, #a71e2a);
        }

        .start-btn {
            margin-top: 1rem;
            padding: 0.75rem 1.5rem;
            background: linear-gradient(135deg, #dc3545, #c82333);
            color: white;
            border-radius: 25px;
            font-weight: bold;
            font-size: 1rem;
            transition: all 0.3s ease;
        }

        .start-btn:hover {
            background: linear-gradient(135deg, #c82333, #a71e2a);
        }

        .course-icon {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1rem;
            font-size: 1.5rem;
            color: white;
        }

        .bsit-color { background: #dc3545; }
        .bsba-color { background: #28a745; }
        .bshm-color { background: #ffc107; color: #333 !important; }
        .education-color { background: #6f42c1; }

        .course-card h4 {
            margin: 0.5rem 0;
            color: #333;
            font-size: 1.2rem;
        }

        .course-card p {
            margin: 0.5rem 0;
            color: #666;
            font-size: 0.9rem;
        }

        .course-count {
            display: inline-block;
            background: #007bff;
            color: white;
            padding: 0.25rem 0.75rem;
            border-radius: 15px;
            font-size: 0.8rem;
            font-weight: bold;
        }

        .date-controls {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 1rem;
            margin: 1.5rem 0;
            flex-wrap: wrap;
        }

        .current-week {
            font-weight: bold;
            font-size: 1.1rem;
            color: #007bff;
            padding: 0.5rem 1rem;
            background: rgba(0, 123, 255, 0.1);
            border-radius: 5px;
        }

        .table-container {
            overflow-x: auto;
            margin-top: 1rem;
        }

        .loading-spinner {
            text-align: center;
            padding: 2rem;
            color: #007bff;
            font-size: 1.1rem;
        }

        .loading-spinner i {
            font-size: 2rem;
            display: block;
            margin-bottom: 0.5rem;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background: white;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        table thead {
            background: linear-gradient(135deg, #007bff, #4da6ff);
            color: white;
        }

        table th, table td {
            padding: 12px;
            text-align: center;
            border: 1px solid #dee2e6;
        }

        table th {
            font-weight: 600;
            font-size: 0.9rem;
        }

        table tbody tr:nth-child(even) {
            background-color: #f8f9fa;
        }

        table tbody tr:hover {
            background-color: rgba(0, 123, 255, 0.1);
        }

        .attendance-cell {
            cursor: pointer;
            padding: 8px !important;
            font-size: 1.2rem;
        }

        .attendance-cell:hover {
            background-color: rgba(0, 123, 255, 0.2) !important;
        }

        .attendance-present {
            color: #28a745;
        }

        .attendance-absent {
            color: #dc3545;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: 1rem;
            margin-top: 1rem;
        }

        .stat-item {
            text-align: center;
            padding: 1rem;
            background: rgba(255, 255, 255, 0.9);
            border-radius: 8px;
            border: 1px solid #e3f2fd;
        }

        .stat-value {
            font-size: 2rem;
            font-weight: bold;
            margin-bottom: 0.5rem;
        }

        .stat-label {
            color: #666;
            font-size: 0.9rem;
        }

        .quick-actions {
            margin-top: 1.5rem;
            text-align: center;
        }

        .quick-actions .btn {
            margin: 0.25rem;
        }

        /* Bulk Actions Styles */
        .bulk-actions {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: #f8f9fa;
            border: 1px solid #dee2e6;
            border-radius: 8px;
            padding: 1rem;
            margin: 1rem 0;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .bulk-actions-info {
            font-weight: bold;
            color: #495057;
        }

        .bulk-actions-buttons {
            display: flex;
            gap: 0.5rem;
        }

        .btn-danger {
            background: linear-gradient(135deg, #dc3545, #c82333);
            color: white;
            border: none;
        }

        .btn-danger:hover {
            background: linear-gradient(135deg, #c82333, #a71e2a);
            transform: translateY(-2px);
        }

        /* Checkbox styling */
        input[type="checkbox"] {
            width: 18px;
            height: 18px;
            cursor: pointer;
        }

        .employee-checkbox {
            cursor: pointer;
        }

        .selected-row {
            background-color: rgba(0, 123, 255, 0.1) !important;
        }

        /* Notification Styles */
        .notification {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 1000;
            min-width: 300px;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
            animation: slideIn 0.3s ease-out;
        }

        .notification.success {
            background: linear-gradient(135deg, #28a745, #20c997);
            color: white;
        }

        .notification.error {
            background: linear-gradient(135deg, #dc3545, #fd7e14);
            color: white;
        }

        .notification-content {
            padding: 1rem 1.5rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .notification-close {
            background: none;
            border: none;
            color: white;
            cursor: pointer;
            font-size: 1rem;
            opacity: 0.8;
            transition: opacity 0.3s;
        }

        .notification-close:hover {
            opacity: 1;
        }

        @keyframes slideIn {
            from {
                transform: translateX(100%);
                opacity: 0;
            }
            to {
                transform: translateX(0);
                opacity: 1;
            }
        }

        @media (max-width: 768px) {
            .single-course-grid {
                padding: 0 1rem;
            }
            
            .course-card {
                max-width: 100%;
            }
            
            .date-controls {
                flex-direction: column;
            }
            
            .stats-grid {
                grid-template-columns: repeat(2, 1fr);
            }

            .notification {
                left: 20px;
                right: 20px;
                min-width: auto;
            }
        }
    </style>

    <script>
        let currentDate = new Date();
        let selectedCourse = '';
        let attendanceData = [];

        // Initialize
        document.addEventListener('DOMContentLoaded', function() {
            loadCourseCounts();
        });

        function loadCourseCounts() {
            // Load instructor counts for the user's course
            fetch('/api/course-counts')
                .then(response => response.json())
                .then(data => {
                    const count = data.count || 0;
                    document.getElementById('course-count').textContent = `${count} Instructor${count !== 1 ? 's' : ''}`;
                })
                .catch(error => {
                    console.error('Error loading course counts:', error);
                    document.getElementById('course-count').textContent = '0 Instructors';
                });
        }

        function loadAttendance(course) {
            selectedCourse = course.toLowerCase();
            
            // Show sections
            document.getElementById('attendance-section').style.display = 'block';
            document.getElementById('stats-section').style.display = 'block';
            
            // Update title with dynamic course name
            const courseUpper = course.toUpperCase();
            document.getElementById('attendance-title').innerHTML = 
                `<i class="fas fa-calendar-check"></i> ${courseUpper} Attendance Management`;
            
            // Show loading
            document.getElementById('loading-spinner').style.display = 'block';
            document.getElementById('attendance-table').style.display = 'none';
            
            // Update week display
            updateWeekDisplay();
            
            // Fetch attendance data
            fetchAttendanceData();
        }

        function updateWeekDisplay() {
            const startOfWeek = new Date(currentDate);
            startOfWeek.setDate(currentDate.getDate() - currentDate.getDay() + 1);
            
            const endOfWeek = new Date(startOfWeek);
            endOfWeek.setDate(startOfWeek.getDate() + 5);
            
            const weekText = `${startOfWeek.toLocaleDateString()} - ${endOfWeek.toLocaleDateString()}`;
            document.getElementById('current-week').textContent = weekText;
            
            // Update day headers
            const days = ['mon', 'tue', 'wed', 'thu', 'fri', 'sat'];
            for (let i = 0; i < days.length; i++) {
                const dayDate = new Date(startOfWeek);
                dayDate.setDate(startOfWeek.getDate() + i);
                document.getElementById(`${days[i]}-date`).textContent = 
                    dayDate.toLocaleDateString('en-US', { month: 'short', day: 'numeric' });
            }
        }

        function fetchAttendanceData() {
            const startOfWeek = new Date(currentDate);
            startOfWeek.setDate(currentDate.getDate() - currentDate.getDay() + 1);
            
            // Fetch attendance data with existing status
            fetch(`/api/attendance-data/${selectedCourse}?week_start=${startOfWeek.toISOString().split('T')[0]}`)
                .then(response => response.json())
                .then(data => {
                    attendanceData = [];
                    const tbody = document.getElementById('attendance-tbody');
                    tbody.innerHTML = '';
                    
                    data.forEach(employee => {
                        // Parse existing attendance from days data
                        const existingAttendance = employee.days || {};
                        
                        const attendance = {
                            monday: existingAttendance.monday > 0,
                            tuesday: existingAttendance.tuesday > 0,
                            wednesday: existingAttendance.wednesday > 0,
                            thursday: existingAttendance.thursday > 0,
                            friday: existingAttendance.friday > 0,
                            saturday: existingAttendance.saturday > 0
                        };
                        
                        // Create attendance row with existing data
                        const attendanceRow = createAttendanceRow(
                            employee.id, 
                            employee.employee_name, 
                            employee.designation, 
                            employee.employee_type,
                            attendance
                        );
                        tbody.appendChild(attendanceRow);
                        
                        attendanceData.push({
                            id: employee.id,
                            name: employee.employee_name,
                            designation: employee.designation,
                            type: employee.employee_type,
                            attendance: attendance
                        });
                    });
                    
                    // Hide loading and show table
                    document.getElementById('loading-spinner').style.display = 'none';
                    document.getElementById('attendance-table').style.display = 'table';
                    
                    // Update statistics
                    updateStatistics();
                })
                .catch(error => {
                    console.error('Error fetching attendance data:', error);
                    document.getElementById('loading-spinner').innerHTML = 
                        '<i class="fas fa-exclamation-triangle"></i> Error loading attendance data';
                });
        }

        function createAttendanceRow(id, name, designation, type, existingAttendance = null) {
            const row = document.createElement('tr');
            
            // Helper function to get attendance icon and class
            function getAttendanceIcon(isPresent) {
                if (isPresent) {
                    return '<i class="fas fa-check attendance-present"></i>';
                } else {
                    return '<i class="fas fa-times attendance-absent"></i>';
                }
            }
            
            const attendance = existingAttendance || {
                monday: false,
                tuesday: false,
                wednesday: false,
                thursday: false,
                friday: false,
                saturday: false
            };
            
            row.innerHTML = `
                <td>
                    <input type="checkbox" class="employee-checkbox" data-employee-id="${id}" onchange="updateBulkActions()">
                </td>
                <td>${id}</td>
                <td style="text-align: left;">${name}</td>
                <td>${designation}</td>
                <td><span class="badge ${type.includes('Full') ? 'bg-primary' : 'bg-info'}">${type}</span></td>
                <td class="attendance-cell" data-day="monday" onclick="toggleAttendance(this)">
                    ${getAttendanceIcon(attendance.monday)}
                </td>
                <td class="attendance-cell" data-day="tuesday" onclick="toggleAttendance(this)">
                    ${getAttendanceIcon(attendance.tuesday)}
                </td>
                <td class="attendance-cell" data-day="wednesday" onclick="toggleAttendance(this)">
                    ${getAttendanceIcon(attendance.wednesday)}
                </td>
                <td class="attendance-cell" data-day="thursday" onclick="toggleAttendance(this)">
                    ${getAttendanceIcon(attendance.thursday)}
                </td>
                <td class="attendance-cell" data-day="friday" onclick="toggleAttendance(this)">
                    ${getAttendanceIcon(attendance.friday)}
                </td>
                <td class="attendance-cell" data-day="saturday" onclick="toggleAttendance(this)">
                    ${getAttendanceIcon(attendance.saturday)}
                </td>
            `;
            
            return row;
        }

        function toggleAttendance(cell) {
            const icon = cell.querySelector('i');
            const day = cell.dataset.day;
            const row = cell.closest('tr');
            const employeeId = row.cells[0].textContent.trim();
            
            // Find employee in data
            const employee = attendanceData.find(emp => emp.id === employeeId);
            
            if (icon.classList.contains('fa-times')) {
                // Change to present
                icon.classList.remove('fa-times', 'attendance-absent');
                icon.classList.add('fa-check', 'attendance-present');
                if (employee) employee.attendance[day] = true;
            } else {
                // Change to absent
                icon.classList.remove('fa-check', 'attendance-present');
                icon.classList.add('fa-times', 'attendance-absent');
                if (employee) employee.attendance[day] = false;
            }
            
            updateStatistics();
        }

        function markAllPresent() {
            const cells = document.querySelectorAll('.attendance-cell');
            cells.forEach(cell => {
                const icon = cell.querySelector('i');
                const day = cell.dataset.day;
                const row = cell.closest('tr');
                const employeeId = row.cells[0].textContent.trim();
                const employee = attendanceData.find(emp => emp.id === employeeId);
                
                icon.classList.remove('fa-times', 'attendance-absent');
                icon.classList.add('fa-check', 'attendance-present');
                if (employee) employee.attendance[day] = true;
            });
            updateStatistics();
        }

        function markAllAbsent() {
            const cells = document.querySelectorAll('.attendance-cell');
            cells.forEach(cell => {
                const icon = cell.querySelector('i');
                const day = cell.dataset.day;
                const row = cell.closest('tr');
                const employeeId = row.cells[0].textContent.trim();
                const employee = attendanceData.find(emp => emp.id === employeeId);
                
                icon.classList.remove('fa-check', 'attendance-present');
                icon.classList.add('fa-times', 'attendance-absent');
                if (employee) employee.attendance[day] = false;
            });
            updateStatistics();
        }

        function updateStatistics() {
            const totalInstructors = attendanceData.length;
            let presentToday = 0;
            let absentToday = 0;
            
            // Count present/absent for today (or Monday as default)
            const today = new Date().getDay();
            const dayNames = ['sunday', 'monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday'];
            const currentDay = dayNames[today] || 'monday';
            
            attendanceData.forEach(employee => {
                if (employee.attendance[currentDay]) {
                    presentToday++;
                } else {
                    absentToday++;
                }
            });
            
            const attendanceRate = totalInstructors > 0 ? Math.round((presentToday / totalInstructors) * 100) : 0;
            
            document.getElementById('total-instructors').textContent = totalInstructors;
            document.getElementById('present-today').textContent = presentToday;
            document.getElementById('absent-today').textContent = absentToday;
            document.getElementById('attendance-rate').textContent = `${attendanceRate}%`;
        }

        function previousWeek() {
            currentDate.setDate(currentDate.getDate() - 7);
            updateWeekDisplay();
            if (selectedCourse) fetchAttendanceData();
        }

        function nextWeek() {
            currentDate.setDate(currentDate.getDate() + 7);
            updateWeekDisplay();
            if (selectedCourse) fetchAttendanceData();
        }

        function goToCurrentWeek() {
            currentDate = new Date();
            updateWeekDisplay();
            if (selectedCourse) fetchAttendanceData();
        }

        function saveAttendance() {
            if (!selectedCourse || attendanceData.length === 0) {
                alert('Please select a course and load attendance data first.');
                return;
            }

            const startOfWeek = new Date(currentDate);
            startOfWeek.setDate(currentDate.getDate() - currentDate.getDay() + 1);

            const saveData = {
                course: selectedCourse,
                week_start: startOfWeek.toISOString().split('T')[0],
                attendance_data: attendanceData
            };

            // Show loading
            const saveBtn = event.target;
            const originalText = saveBtn.innerHTML;
            saveBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Saving...';
            saveBtn.disabled = true;

            fetch('/api/save-attendance', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
                },
                body: JSON.stringify(saveData)
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Show success message
                    showNotification('✅ ' + data.message, 'success');
                    
                    // Auto-refresh the attendance data to show updated status
                    setTimeout(() => {
                        fetchAttendanceData();
                    }, 500);
                    
                } else {
                    showNotification('❌ Error: ' + data.message, 'error');
                }
            })
            .catch(error => {
                console.error('Error saving attendance:', error);
                showNotification('❌ Error saving attendance. Please try again.', 'error');
            })
            .finally(() => {
                // Restore button
                saveBtn.innerHTML = originalText;
                saveBtn.disabled = false;
            });
        }

        function showNotification(message, type) {
            // Create notification element
            const notification = document.createElement('div');
            notification.className = `notification ${type}`;
            notification.innerHTML = `
                <div class="notification-content">
                    <span>${message}</span>
                    <button class="notification-close" onclick="this.parentElement.parentElement.remove()">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            `;
            
            // Add to page
            document.body.appendChild(notification);
            
            // Auto remove after 4 seconds
            setTimeout(() => {
                if (notification.parentNode) {
                    notification.remove();
                }
            }, 4000);
        }

        function exportAttendance() {
            if (!selectedCourse) {
                alert('Please select a course first.');
                return;
            }
            
            // Open the existing print-optimized page
            window.open(`/${selectedCourse}`, '_blank');
        }

        // Bulk Delete Functions
        function toggleSelectAll() {
            const selectAllCheckbox = document.getElementById('select-all-checkbox');
            const employeeCheckboxes = document.querySelectorAll('.employee-checkbox');
            
            employeeCheckboxes.forEach(checkbox => {
                checkbox.checked = selectAllCheckbox.checked;
                if (selectAllCheckbox.checked) {
                    checkbox.closest('tr').classList.add('selected-row');
                } else {
                    checkbox.closest('tr').classList.remove('selected-row');
                }
            });
            
            updateBulkActions();
        }

        function updateBulkActions() {
            const selectedCheckboxes = document.querySelectorAll('.employee-checkbox:checked');
            const selectedCount = selectedCheckboxes.length;
            const bulkActions = document.getElementById('bulk-actions');
            const selectedCountSpan = document.getElementById('selected-count');
            const selectAllCheckbox = document.getElementById('select-all-checkbox');
            
            // Update selected count
            selectedCountSpan.textContent = selectedCount;
            
            // Show/hide bulk actions
            if (selectedCount > 0) {
                bulkActions.style.display = 'flex';
            } else {
                bulkActions.style.display = 'none';
            }
            
            // Update select all checkbox state
            const allCheckboxes = document.querySelectorAll('.employee-checkbox');
            if (selectedCount === 0) {
                selectAllCheckbox.indeterminate = false;
                selectAllCheckbox.checked = false;
            } else if (selectedCount === allCheckboxes.length) {
                selectAllCheckbox.indeterminate = false;
                selectAllCheckbox.checked = true;
            } else {
                selectAllCheckbox.indeterminate = true;
            }
            
            // Update row highlighting
            selectedCheckboxes.forEach(checkbox => {
                checkbox.closest('tr').classList.add('selected-row');
            });
            
            document.querySelectorAll('.employee-checkbox:not(:checked)').forEach(checkbox => {
                checkbox.closest('tr').classList.remove('selected-row');
            });
        }

        function clearSelection() {
            const employeeCheckboxes = document.querySelectorAll('.employee-checkbox');
            const selectAllCheckbox = document.getElementById('select-all-checkbox');
            
            employeeCheckboxes.forEach(checkbox => {
                checkbox.checked = false;
                checkbox.closest('tr').classList.remove('selected-row');
            });
            
            selectAllCheckbox.checked = false;
            selectAllCheckbox.indeterminate = false;
            
            updateBulkActions();
        }

        function bulkDeleteSelected() {
            const selectedCheckboxes = document.querySelectorAll('.employee-checkbox:checked');
            const selectedIds = Array.from(selectedCheckboxes).map(checkbox => checkbox.dataset.employeeId);
            
            if (selectedIds.length === 0) {
                showNotification('❌ No employees selected for deletion.', 'error');
                return;
            }
            
            // Show confirmation dialog
            const confirmMessage = `Are you sure you want to delete ${selectedIds.length} employee record(s)? This action cannot be undone.`;
            
            if (!confirm(confirmMessage)) {
                return;
            }
            
            // Send bulk delete request
            fetch('/api/bulk-delete-attendance', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
                },
                body: JSON.stringify({
                    course: selectedCourse,
                    employee_ids: selectedIds
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showNotification(`✅ Successfully deleted ${selectedIds.length} employee record(s).`, 'success');
                    
                    // Remove deleted rows from table
                    selectedCheckboxes.forEach(checkbox => {
                        checkbox.closest('tr').remove();
                    });
                    
                    // Update attendance data array
                    attendanceData = attendanceData.filter(employee => !selectedIds.includes(employee.id));
                    
                    // Clear selection and update UI
                    clearSelection();
                    updateStatistics();
                    loadCourseCounts(); // Refresh course counts
                    
                } else {
                    showNotification('❌ Error: ' + (data.message || 'Failed to delete selected records.'), 'error');
                }
            })
            .catch(error => {
                console.error('Error deleting records:', error);
                showNotification('❌ Error deleting records. Please try again.', 'error');
            });
        }
    </script>
</body>
</html>