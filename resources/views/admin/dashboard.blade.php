<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Dashboard</title>

  <!-- Bootstrap 5 -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>



  <style>
    :root{
      --brand:#3498db;          
      --brand-600:#2980b9;      
      --muted:#f5f6f8;          
      --card:#ffffff;           
      --text:#111111;           
    }
    .night-mode {
      --brand:#222831;
      --brand-600:#393e46;
      --muted:#18191a;
      --card:#c5c8ce;
      --text:#ffffff;           
    }
    body{ background:var(--muted); color:var(--text); transition:background .3s, color .3s; }
    .app{ min-height:100vh; }
    .sidebar{
      background: linear-gradient(180deg, var(--brand), var(--brand-600));
      color:#fff; width:260px; position:sticky; top:0; height:100vh; padding:1.25rem 1rem;
      box-shadow: 0 10px 25px rgba(52,152,219,.25);
    }
    .sidebar .nav-link{
      color:#e3f2fd; border-radius:.75rem; padding:.6rem .8rem; font-weight:500;
    }
    .sidebar .nav-link:hover, .sidebar .nav-link.active{
      background:#fff; color:var(--brand-600);
    }
    .sidebar .section-title{ font-size:.8rem; text-transform:uppercase; opacity:.85; margin:.9rem .5rem .3rem; }
    .content{ flex:1; }
    .topbar{ background:#fff; border-bottom:1px solid #f0f0f0; padding:.75rem 1rem; position:sticky; top:0; z-index:1020; }
    .logout-icon{ font-size:1.4rem; color:var(--brand); }
    .logout-icon:hover{ color:#85c1e9; }
    .card-soft{ background:var(--card); border:1px solid #f0f0f0; border-radius:1rem; box-shadow:0 8px 20px rgba(0,0,0,.03); }
    .stat-card .icon-wrap{ width:46px; height:46px; border-radius:999px; display:grid; place-items:center; background:rgba(52,152,219,.1); }
    .stat-value{ font-weight:800; font-size:1.35rem; color:var(--brand-600); }
    .stat-card{ transition: transform 0.2s ease, box-shadow 0.2s ease; }
    .stat-card:hover{ transform: translateY(-2px); box-shadow: 0 12px 30px rgba(0,0,0,.08); }
    .stat-card .icon-wrap i{ font-size: 1.2rem; }
    .stat-card .stat-value{ transition: all 0.3s ease; }
    .stat-card:hover .stat-value{ transform: scale(1.05); }
    .badge-soft{ background:rgba(52,152,219,.1); color:var(--brand-600); border-radius:999px; }
    thead th{ background:var(--brand); color:#fff; }
    @media (max-width: 992px){
      .sidebar{ position:fixed; transform:translateX(-100%); transition:.25s; z-index:1030; }
      .sidebar.show{ transform:none; }
      .content{ margin-left:0!important; }
    }

    .sidebar-btn {
      background-color: #3498db; color: white; text-align: left; margin-bottom: 5px; border: none; width: 100%; padding: 8px 12px; border-radius: 5px; transition: 0.3s;
    }
    .sidebar-btn:hover, .sidebar-btn:focus {
      background-color: white; color: #3498db; border: 1px solid #3498db;
    }

    .dropdown-menu {
  border-radius: 8px;
  padding: 0;
  overflow: hidden;
}
.dropdown-menu .dropdown-item {
  padding: 10px 15px;
  transition: 0.3s;
}
.dropdown-menu .dropdown-item:hover {
  background-color: #3498db;
  color: #fff;
}

    /* Custom SweetAlert2 styling for login success */
    .swal-login-popup {
      border-radius: 20px !important;
      border: 3px solid #3498db !important;
      box-shadow: 0 20px 60px rgba(52, 152, 219, 0.3) !important;
      backdrop-filter: blur(10px) !important;
    }
    
    .swal-login-title {
      color: #3498db !important;
      font-weight: 800 !important;
      font-size: 2rem !important;
      text-shadow: 0 2px 4px rgba(52, 152, 219, 0.2) !important;
    }
    
    .swal-login-content {
      color: #2c3e50 !important;
      font-size: 1.1rem !important;
      font-weight: 500 !important;
    }
    
    .swal-login-button {
      background: linear-gradient(135deg, #3498db, #2980b9) !important;
      border: none !important;
      border-radius: 12px !important;
      font-weight: 700 !important;
      text-transform: uppercase !important;
      letter-spacing: 1px !important;
      padding: 15px 35px !important;
      font-size: 1rem !important;
      box-shadow: 0 8px 25px rgba(52, 152, 219, 0.4) !important;
      transition: all 0.3s ease !important;
    }
    
    .swal-login-button:hover {
      background: linear-gradient(135deg, #2980b9, #1f618d) !important;
      transform: translateY(-3px) !important;
      box-shadow: 0 12px 35px rgba(52, 152, 219, 0.5) !important;
    }

    /* Bounce animation for login success */
    @keyframes bounceIn {
      0% {
        opacity: 0;
        transform: scale(0.3) translateY(-50px);
      }
      50% {
        opacity: 1;
        transform: scale(1.05) translateY(0);
      }
      70% {
        transform: scale(0.95);
      }
      100% {
        opacity: 1;
        transform: scale(1);
      }
    }

    /* Success icon pulse animation */
    .swal2-success-circular-line-right,
    .swal2-success-circular-line-left {
      background-color: #3498db !important;
    }
    
    .swal2-success-fix {
      background-color: #3498db !important;
    }
    
    .swal2-success-ring {
      border: 4px solid rgba(52, 152, 219, 0.3) !important;
    }

    /* User welcome styling */
    .user-welcome {
      background: linear-gradient(135deg, rgba(52, 152, 219, 0.1), rgba(52, 152, 219, 0.05));
      border-radius: 8px;
      padding: 8px 16px;
      border-left: 4px solid var(--brand);
    }
    
    .user-name {
      color: var(--brand) !important;
      font-weight: 700 !important;
      text-shadow: 0 1px 2px rgba(52, 152, 219, 0.1);
    }

    /* Search dropdown styling */
    .search-container {
      position: relative;
    }
    
    .search-dropdown {
      position: absolute;
      top: 100%;
      left: 0;
      right: 0;
      background: white;
      border: 1px solid #e0e0e0;
      border-radius: 8px;
      box-shadow: 0 8px 25px rgba(0,0,0,0.15);
      z-index: 1050;
      max-height: 300px;
      overflow-y: auto;
      display: none;
    }
    
    .search-dropdown.show {
      display: block;
    }
    
    .search-item {
      padding: 12px 16px;
      border-bottom: 1px solid #f0f0f0;
      cursor: pointer;
      transition: background-color 0.2s;
    }
    
    .search-item:last-child {
      border-bottom: none;
    }
    
    .search-item:hover {
      background-color: #f8f9fa;
    }
    
    .search-item-name {
      font-weight: 600;
      color: var(--brand);
      margin-bottom: 2px;
    }
    
    .search-item-details {
      font-size: 0.85rem;
      color: #6c757d;
    }
    
    .search-no-results {
      padding: 16px;
      text-align: center;
      color: #6c757d;
      font-style: italic;
    }
    
    .search-loading {
      padding: 16px;
      text-align: center;
      color: var(--brand);
    }
    
    .search-header {
      background: #f8f9fa;
      font-weight: 600;
      font-size: 0.8rem;
      text-transform: uppercase;
      letter-spacing: 0.5px;
    }
    
    .search-item .d-flex {
      transition: all 0.2s ease;
    }
    
    .search-item:hover .d-flex {
      padding-left: 8px;
    }
    
    .search-item .bi-arrow-right {
      opacity: 0;
      transition: opacity 0.2s ease;
    }
    
    .search-item:hover .bi-arrow-right {
      opacity: 1;
    }
    
    /* Night mode search dropdown */
    .night-mode .search-dropdown {
      background: var(--card);
      border-color: #444;
    }
    
    .night-mode .search-header {
      background: rgba(255,255,255,0.1);
      color: #bbb;
    }
    
    .night-mode .search-item:hover {
      background-color: rgba(255,255,255,0.1);
    }
    
    .night-mode .search-item-name {
      color: var(--brand);
    }
    
    .night-mode .search-item-details {
      color: #aaa;
    }

    /* Footer styling */
    .footer {
      background: linear-gradient(135deg, var(--brand), var(--brand-600));
      color: #fff;
      padding: 2rem 0 1rem;
      margin-top: 3rem;
      border-top: 1px solid rgba(220, 53, 69, 0.2);
    }
    
    .footer-content {
      max-width: 1200px;
      margin: 0 auto;
      padding: 0 1rem;
    }
    
    .footer-section h6 {
      color: #fff;
      font-weight: 700;
      margin-bottom: 1rem;
      font-size: 1.1rem;
    }
    
    .footer-section p, .footer-section li {
      color: #e3f2fd;
      font-size: 0.9rem;
      line-height: 1.6;
    }
    
    .footer-section ul {
      list-style: none;
      padding: 0;
    }
    
    .footer-section ul li {
      margin-bottom: 0.5rem;
      padding-left: 1rem;
      position: relative;
    }
    
    .footer-section ul li:before {
      content: "▸";
      position: absolute;
      left: 0;
      color: #fff;
    }
    
    .footer-bottom {
      border-top: 1px solid rgba(255, 255, 255, 0.2);
      margin-top: 1.5rem;
      padding-top: 1rem;
      text-align: center;
    }
    
    .footer-bottom p {
      margin: 0;
      color: #e3f2fd;
      font-size: 0.85rem;
    }
    
    .footer-logo {
      font-size: 1.5rem;
      font-weight: 800;
      color: #fff;
      text-shadow: 0 2px 4px rgba(0,0,0,0.3);
    }
    
    /* Night mode footer */
    .night-mode .footer {
      background: linear-gradient(135deg, var(--brand), var(--brand-600));
    }
    
    .night-mode .footer-section h6 {
      color: #fff;
    }
    
    .night-mode .footer-section p, 
    .night-mode .footer-section li {
      color: #e0e0e0;
    }
    
    .night-mode .footer-bottom p {
      color: #e0e0e0;
    }

    /* Simple Two-Theme System - No additional styles needed */

    /* Theme transition animation */
    body {
      transition: background-color 0.5s ease, color 0.5s ease;
    }
    
    .sidebar, .topbar, .card-soft {
      transition: all 0.5s ease;
    }

    /* Theme button styling */
    .theme-btn {
      position: relative;
      overflow: hidden;
      border-radius: 8px !important;
      transition: all 0.3s ease;
      border: 2px solid transparent !important;
      background: rgba(255, 255, 255, 0.9) !important;
    }
    
    .theme-btn:hover {
      transform: scale(1.05);
      box-shadow: 0 4px 15px rgba(0,0,0,0.2);
      background: rgba(255, 255, 255, 1) !important;
    }
    
    .theme-btn i {
      transition: all 0.3s ease;
    }
    
    /* Selected theme styling */
    .theme-btn.selected {
      border-color: currentColor !important;
      background: currentColor !important;
      color: white !important;
      box-shadow: 0 0 20px currentColor, 0 4px 15px rgba(0,0,0,0.3);
      animation: selected-pulse 3s infinite;
    }
    
    .theme-btn.selected i {
      color: white !important;
      animation: selected-icon-bounce 2s ease-in-out infinite;
    }
    
    .theme-btn.selected span {
      color: white !important;
      font-weight: bold;
    }
    
    @keyframes selected-pulse {
      0%, 100% { 
        box-shadow: 0 0 20px currentColor, 0 4px 15px rgba(0,0,0,0.3);
        transform: scale(1);
      }
      50% { 
        box-shadow: 0 0 30px currentColor, 0 0 40px currentColor, 0 4px 20px rgba(0,0,0,0.4);
        transform: scale(1.02);
      }
    }
    
    @keyframes selected-icon-bounce {
      0%, 100% { transform: translateY(0px); }
      50% { transform: translateY(-2px); }
    }
    
    /* Temporary active state for theme switching */
    .theme-btn.switching {
      animation: theme-switch 0.6s ease-in-out;
    }
    
    @keyframes theme-switch {
      0% { transform: scale(1) rotate(0deg); }
      50% { transform: scale(1.1) rotate(5deg); }
      100% { transform: scale(1) rotate(0deg); }
    }

    /* Simple theme button animation */
    @keyframes default-pulse {
      0%, 100% { transform: scale(1); }
      50% { transform: scale(1.05); }
    }
    
    .theme-btn i {
      animation: default-pulse 1.5s ease-in-out;
    }

    /* Theme transition effects */
    .theme-transition {
      animation: theme-change 0.8s ease-in-out;
    }
    
    @keyframes theme-change {
      0% { opacity: 1; transform: scale(1); }
      50% { opacity: 0.7; transform: scale(0.95); }
      100% { opacity: 1; transform: scale(1); }
    }

    /* Particles removed for simplicity */

    /* Simple notification styling */
  </style>
</head>
<body>

  
  <div class="app d-flex">
    <!-- SIDEBAR -->
    <aside class="sidebar" id="sidebar">
      <a class="nav-link active" href="#"><i class="bi bi-speedometer2 me-2"></i>Dashboard</a>
      <div class="section-title">Menu</div>
      <nav class="nav flex-column">
        <!-- Employee Dropdown -->
<div class="dropdown">

  <button class="sidebar-btn dropdown-toggle w-100 text-start" type="button" data-bs-toggle="dropdown" aria-expanded="false">
    <i class="bi bi-archive me-2"></i>Employee
  </button>
  <ul class="dropdown-menu w-100">
  <li><a class="dropdown-item" href="{{ route('fulltime.index') }}"><i class="bi bi-person-workspace me-2"></i>Instructor - Full Time</a></li>
  <li><a class="dropdown-item" href="{{ route('parttime.index') }}"><i class="bi bi-person-workspace me-2"></i>Instructor - Part Time</a></li>
    <li><a class="dropdown-item" href="{{ route('utility.index') }}"><i class="bi bi-tools me-2"></i>Utility</a></li>
    <li><a class="dropdown-item" href="{{ route('staff.index') }}"><i class="bi bi-people-fill me-2"></i>Staff</a></li>
  </ul>
</div>

        <!-- Instructor Rate Dropdown -->
<div class="dropdown">
  <button class="sidebar-btn dropdown-toggle w-100 text-start" type="button" data-bs-toggle="dropdown" aria-expanded="false">
    <i class="bi bi-currency-dollar me-2"></i>Instructor Rate
  </button>
  <ul class="dropdown-menu w-100">
    <li><a class="dropdown-item" href="#" onclick="showInstructorRate('130-150')"><i class="bi bi-cash me-2"></i>₱130 - ₱150</a></li>
    <li><a class="dropdown-item" href="#" onclick="showInstructorRate('170-210')"><i class="bi bi-cash me-2"></i>₱170 - ₱210</a></li>
    <li><a class="dropdown-item" href="#" onclick="showInstructorRate('220')"><i class="bi bi-cash me-2"></i>₱220</a></li>
    <li><a class="dropdown-item" href="#" onclick="showInstructorRate('250')"><i class="bi bi-cash me-2"></i>₱250</a></li>
  </ul>
</div>

        <!-- Department Dropdown -->
<div class="dropdown">
  <button class="sidebar-btn dropdown-toggle w-100 text-start" type="button" data-bs-toggle="dropdown" aria-expanded="false">
    <i class="bi bi-building me-2"></i>Department
  </button>
  <ul class="dropdown-menu w-100">
    <li><a class="dropdown-item" href="{{ route('departments.index') }}"><i class="bi bi-gear me-2"></i>Manage Departments</a></li>
    <li><hr class="dropdown-divider"></li>
    <li><a class="dropdown-item" href="{{ route('bsit.index') }}"><i class="bi bi-laptop me-2"></i>BSIT</a></li>
    <li><a class="dropdown-item" href="{{ route('bsba.index') }}"><i class="bi bi-briefcase me-2"></i>BSBA</a></li>
    <li><a class="dropdown-item" href="{{ route('bshm.index') }}"><i class="bi bi-cup-hot me-2"></i>BSHM</a></li>
    <li><a class="dropdown-item" href="{{ route('education.index') }}"><i class="bi bi-book me-2"></i>EDUCATION</a></li>
  </ul>
</div>



        <button class="sidebar-btn"><i class="bi bi-clipboard-data me-2"></i>History Records</button>
        <a href="{{ route('master.list') }}" class="sidebar-btn text-decoration-none"><i class="bi bi-list-ul me-2"></i>Master List</a>
        <a href="{{ route('salary.adjustment') }}" class="sidebar-btn text-decoration-none"><i class="bi bi-calculator me-2"></i>Salary Adjustment/Differential</a>
      </nav>
    </aside>

    <!-- MAIN CONTENT -->
    <div class="content w-100">
      <!-- TOPBAR -->

      <div class="topbar d-flex align-items-center justify-content-between">
        <!-- Welcome message -->
        <div class="d-flex align-items-center">
          <div class="user-welcome">
            <h6 class="mb-0 text-muted">Welcome back, <span class="user-name">{{ session('user_name') }}</span></h6>
          </div>
        </div>
        
        <!-- Search + Night mode + Logout aligned right -->
        <div class="d-none d-md-flex align-items-center gap-2 ms-auto">
          <div class="search-container" style="width:260px;">
            <div class="input-group">
              <span class="input-group-text bg-white"><i class="bi bi-search"></i></span>
              <input type="search" id="searchInput" class="form-control" placeholder="Search employees…" autocomplete="off">
            </div>
            <div id="searchDropdown" class="search-dropdown"></div>
          </div>
          <button class="btn btn-light theme-btn" id="toggleTheme" title="Switch Theme">
            <i class="bi bi-sun" id="themeIcon"></i>
            <span class="d-none d-lg-inline ms-1" id="themeText">Default</span>
          </button>
          <a href="{{ url('/logout') }}" id="logoutBtn" class="logout-icon ms-2" title="Log out">
            <i class="bi bi-box-arrow-right"></i>
          </a>
        </div>
        <!-- Mobile menu button -->
        <div class="d-flex d-md-none align-items-center gap-2 ms-auto">
          <button class="btn btn-outline-primary" id="mobileMenuBtn" aria-label="Open menu">
            <i class="bi bi-list" style="font-size:1.5rem;"></i>
          </button>
        </div>

      </div>

      <!-- PAGE BODY -->
      <div class="container-fluid py-4">
        <!-- Employee Statistics Cards -->
        <div class="row g-3 mb-4">
          <!-- Total Employees Card -->
          <div class="col-lg-2 col-md-4 col-sm-6">
            <div class="card-soft p-3 stat-card">
              <div class="d-flex align-items-center">
                <div class="icon-wrap me-3">
                  <i class="bi bi-people-fill text-primary"></i>
                </div>
                <div>
                  <div class="stat-value">{{ $totalEmployees ?? 0 }}</div>
                  <small class="text-muted">Total Employees</small>
                </div>
              </div>
            </div>
          </div>

          <!-- Full-time Instructors Card -->
          <div class="col-lg-2 col-md-4 col-sm-6">
            <div class="card-soft p-3 stat-card">
              <div class="d-flex align-items-center">
                <div class="icon-wrap me-3" style="background:rgba(40, 167, 69, 0.1);">
                  <i class="bi bi-person-badge-fill" style="color: #28a745;"></i>
                </div>
                <div>
                  <div class="stat-value" style="color: #28a745;">{{ $totalFulltimeInstructors ?? 0 }}</div>
                  <small class="text-muted">Full-time</small>
                </div>
              </div>
            </div>
          </div>

          <!-- Part-time Instructors Card -->
          <div class="col-lg-2 col-md-4 col-sm-6">
            <div class="card-soft p-3 stat-card">
              <div class="d-flex align-items-center">
                <div class="icon-wrap me-3" style="background:rgba(255, 193, 7, 0.1);">
                  <i class="bi bi-person-check-fill" style="color: #0741ff;"></i>
                </div>
                <div>
                  <div class="stat-value" style="color: #0734ff;">{{ $totalParttimeInstructors ?? 0 }}</div>
                  <small class="text-muted">Part-time</small>
                </div>
              </div>
            </div>
          </div>

          <!-- Staff Card -->
          <div class="col-lg-2 col-md-4 col-sm-6">
            <div class="card-soft p-3 stat-card">
              <div class="d-flex align-items-center">
                <div class="icon-wrap me-3" style="background:rgba(220, 53, 69, 0.1);">
                  <i class="bi bi-person-workspace" style="color: #dc3545;"></i>
                </div>
                <div>
                  <div class="stat-value" style="color: #dc3545;">{{ $totalStaff ?? 0 }}</div>
                  <small class="text-muted">Staff</small>
                </div>
              </div>
            </div>
          </div>

          <!-- Utility Workers Card -->
          <div class="col-lg-2 col-md-4 col-sm-6">
            <div class="card-soft p-3 stat-card">
              <div class="d-flex align-items-center">
                <div class="icon-wrap me-3" style="background:rgba(111, 66, 193, 0.1);">
                  <i class="bi bi-tools" style="color: #6f42c1;"></i>
                </div>
                <div>
                  <div class="stat-value" style="color: #6f42c1;">{{ $totalUtility ?? 0 }}</div>
                  <small class="text-muted">Utility</small>
                </div>
              </div>
            </div>
          </div>

          <!-- Action Buttons -->
          <div class="col-lg-2 col-md-4 col-sm-6">
            <div class="card-soft p-3 stat-card">
              <div class="d-flex flex-column align-items-center justify-content-center h-100 gap-1">
                <button class="btn btn-outline-primary btn-sm w-100" onclick="refreshStats()" title="Refresh Statistics">
                  <i class="bi bi-arrow-clockwise me-1"></i>
                  <span class="d-none d-lg-inline">Refresh</span>
                </button>
                <button class="btn btn-outline-info btn-sm w-100" onclick="showDetailedStats()" title="View Detailed Statistics">
                  <i class="bi bi-info-circle me-1"></i>
                  <span class="d-none d-lg-inline">Details</span>
                </button>
              </div>
            </div>
          </div>
        </div>

        <div class="row g-3">
          <div class="col-12">
             <div class="card-soft p-3">
              {{-- Attendance Users Status (red = online, green = offline) --}}
              @php
                  // If controller doesn’t pass $attendanceUsers, fallback to role-based fetch
                  $attendanceUsers = $attendanceUsers
                      ?? \App\Models\User::query()->where('role', 'attendance_checker')->get();
              @endphp

              <h5 class="mb-3">Attendance Checkers</h5>

              @if($attendanceUsers->isEmpty())
                <div class="text-muted">No attendance users found.</div>
              @else
                <ul class="list-group list-group-flush">
                  @foreach ($attendanceUsers as $user)
                    @php
                      $isOnline = cache()->has('user-is-online-'.$user->id);
                    @endphp

                    <li class="list-group-item d-flex align-items-center justify-content-between">
                      <div class="me-3">
                        <span class="me-2 d-inline-block rounded-circle"
                              style="width:10px;height:10px;background: {{ $isOnline ? '#dc3545' : '#28a745' }};"></span>
                        <span class="fw-semibold">{{ $user->name }}</span>
                        <span class="text-muted small ms-2">({{ $user->course ?? '—' }})</span>
                        @if($isOnline)
                          @php $info = cache()->get('user-online-info-'.$user->id, []); @endphp
                          <div class="small text-muted mt-1">
                            <i class="bi bi-pc-display"></i>
                            {{ $info['device'] ?? 'Unknown device' }}
                            <span class="mx-1">•</span>
                            <i class="bi bi-wifi"></i>
                            {{ $info['ip'] ?? 'Unknown IP' }}
                          </div>
                        @endif
                      </div>
                      <span class="badge {{ $isOnline ? 'bg-danger' : 'bg-success' }}">
                        {{ $isOnline ? 'Online' : 'Offline' }}
                      </span>
                    </li>
                  @endforeach
                </ul>
              @endif
            </div>
          </div>
        </div>
      </div>


    </div>
  </div>
  <!-- Scripts -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script>


    // Check for error messages
    @if(session('error'))
      Swal.fire({
        icon: 'error',
        title: 'Oops!',
        text: '{{ session('error') }}',
        confirmButtonText: 'OK',
        confirmButtonColor: '#dc3545',
        customClass: {
          popup: 'swal-login-popup',
          title: 'swal-login-title',
          content: 'swal-login-content',
          confirmButton: 'swal-login-button'
        }
      });
    @endif
       
    // Simple Two-Theme System
    const themes = [
      { 
        name: ' ', 
        class: '', 
        icon: 'bi-sun', 
        color: '#dc3545',
        description: 'Default light theme'
      },
      { 
        name: ' ', 
        class: 'night-mode', 
        icon: 'bi-moon-fill', 
        color: '#222831',
        description: 'Dark theme for better night viewing'
      }
    ];

    let currentThemeIndex = 0;

    // Load saved theme
    const savedTheme = localStorage.getItem('currentTheme');
    if (savedTheme) {
      currentThemeIndex = parseInt(savedTheme);
      applyTheme(currentThemeIndex);
    }

    function applyTheme(index, showNotification = false) {
      const theme = themes[index];
      const body = document.body;
      
      // Add transition effect
      body.classList.add('theme-transition');
      setTimeout(() => body.classList.remove('theme-transition'), 800);
      
      // Remove all theme classes
      themes.forEach(t => {
        if (t.class) body.classList.remove(t.class);
      });
      
      // Apply new theme class
      if (theme.class) {
        body.classList.add(theme.class);
      }
      
      // Update desktop button
      const themeIcon = document.getElementById('themeIcon');
      const themeText = document.getElementById('themeText');
      const themeBtn = document.getElementById('toggleTheme');
      
      if (themeIcon) {
        themeIcon.className = `bi ${theme.icon}`;
      }
      if (themeText) {
        themeText.textContent = theme.name;
      }
      if (themeBtn) {
        // Set the theme color
        themeBtn.style.setProperty('color', theme.color, 'important');
        themeBtn.title = `Selected: ${theme.name} - ${theme.description}. Click to switch to next theme.`;
        
        // Add selected state
        themeBtn.classList.remove('switching');
        themeBtn.classList.add('selected');
        
        // Add switching animation when changing themes
        if (showNotification) {
          themeBtn.classList.add('switching');
          setTimeout(() => {
            themeBtn.classList.remove('switching');
          }, 600);
        }
      }
      
      // Update mobile button
      const themeIconMobile = document.getElementById('themeIconMobile');
      const themeTextMobile = document.getElementById('themeTextMobile');
      const themeBtnMobile = document.getElementById('toggleThemeMobile');
      
      if (themeIconMobile) {
        themeIconMobile.className = `bi ${theme.icon}`;
      }
      if (themeTextMobile) {
        themeTextMobile.textContent = `${theme.name} Theme`;
      }
      if (themeBtnMobile) {
        // Set the theme color
        themeBtnMobile.style.setProperty('color', theme.color, 'important');
        
        // Add selected state
        themeBtnMobile.classList.remove('switching');
        themeBtnMobile.classList.add('selected');
        
        // Add switching animation when changing themes
        if (showNotification) {
          themeBtnMobile.classList.add('switching');
          setTimeout(() => {
            themeBtnMobile.classList.remove('switching');
          }, 600);
        }
      }
      
      // Save theme preference
      localStorage.setItem('currentTheme', index.toString());
      
      // Show simple theme change notification
      if (showNotification) {
        const themeEmojis = {
          ' ': '☀️',
          ' ': '🌙'
        };
        
        Swal.fire({
          toast: true,
          position: 'top-end',
          icon: 'success',
          title: `${themeEmojis[theme.name]} ${theme.name} Selected!`,
          text: theme.description,
          showConfirmButton: false,
          timer: 2000,
          timerProgressBar: true,
          background: theme.color,
          color: '#fff',
          iconColor: '#fff'
        });
      }
    }

    // Particle effects removed for simplicity

    function nextTheme() {
      currentThemeIndex = (currentThemeIndex + 1) % themes.length;
      applyTheme(currentThemeIndex, true); // Show notification when manually switching
    }

    // Desktop theme toggle
    const themeBtn = document.getElementById('toggleTheme');
    if (themeBtn) {
      themeBtn.addEventListener('click', nextTheme);
    }

    // Mobile theme toggle
    const themeBtnMobile = document.getElementById('toggleThemeMobile');
    if (themeBtnMobile) {
      themeBtnMobile.addEventListener('click', nextTheme);
    }

    // Initialize theme display
    applyTheme(currentThemeIndex);

    // logout
    const logoutBtn = document.getElementById('logoutBtn');
  if(logoutBtn){
    logoutBtn.addEventListener('click', function(e){
      e.preventDefault(); // prevent instant redirect
      Swal.fire({
        title: 'Are you sure?',
        text: "You will be logged out of your session.",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc3545',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Yes, logout'
      }).then((result) => {
        if (result.isConfirmed) {
          window.location.href = "{{ url('/logout') }}";
        }
      });
    });
  }



// Search functionality
let searchTimeout;
const searchInputs = ['searchInput', 'searchInputMobile'];
const searchDropdowns = ['searchDropdown', 'searchDropdownMobile'];

searchInputs.forEach((inputId, index) => {
  const searchInput = document.getElementById(inputId);
  const searchDropdown = document.getElementById(searchDropdowns[index]);
  
  if (searchInput && searchDropdown) {
    searchInput.addEventListener('input', function() {
      const query = this.value.trim();
      
      // Clear previous timeout
      clearTimeout(searchTimeout);
      
      if (query.length < 2) {
        searchDropdown.classList.remove('show');
        return;
      }
      
      // Show loading
      searchDropdown.innerHTML = '<div class="search-loading"><i class="bi bi-search"></i> Searching...</div>';
      searchDropdown.classList.add('show');
      
      // Debounce search
      searchTimeout = setTimeout(() => {
        fetch(`{{ route('search') }}?query=${encodeURIComponent(query)}`)
          .then(response => response.json())
          .then(data => {
            displaySearchResults(data, searchDropdown);
          })
          .catch(error => {
            console.error('Search error:', error);
            searchDropdown.innerHTML = '<div class="search-no-results">Search error occurred</div>';
          });
      }, 300);
    });
    
    // Hide dropdown when clicking outside
    document.addEventListener('click', function(e) {
      if (!searchInput.contains(e.target) && !searchDropdown.contains(e.target)) {
        searchDropdown.classList.remove('show');
      }
    });
    
    // Hide dropdown on escape key
    searchInput.addEventListener('keydown', function(e) {
      if (e.key === 'Escape') {
        searchDropdown.classList.remove('show');
        this.blur();
      }
    });
  }
});

function displaySearchResults(results, dropdown) {
  if (results.length === 0) {
    dropdown.innerHTML = '<div class="search-no-results">No employees found</div>';
    return;
  }
  
  let html = '';
  results.forEach(result => {
    html += `
      <div class="search-item" onclick="window.location.href='${result.route}'">
        <div class="search-item-name">${result.name}</div>
        <div class="search-item-details">${result.type}${result.designation ? ' • ' + result.designation : ''}</div>
      </div>
    `;
  });
  
  dropdown.innerHTML = html;
}

// Auto-update employee statistics with unique person counts
function updateEmployeeStats() {
  fetch('/api/employee-stats')
    .then(response => response.json())
    .then(data => {
      // Update all stat values with unique person counts
      const statElements = document.querySelectorAll('.stat-card .stat-value');
      
      // Update each statistic card
      if (statElements[0]) statElements[0].textContent = data.totalEmployees || 0;
      if (statElements[2]) statElements[2].textContent = data.totalFulltimeInstructors || 0;
      if (statElements[3]) statElements[3].textContent = data.totalParttimeInstructors || 0;
      if (statElements[4]) statElements[4].textContent = data.totalStaffTimesheets || 0;
      if (statElements[5]) statElements[5].textContent = data.totalStaff || 0;
      if (statElements[6]) statElements[6].textContent = data.totalUtility || 0;
      
      console.log('Employee statistics updated (unique person counts):', data);
    })
    .catch(error => {
      console.error('Error updating employee statistics:', error);
    });
}

// Initialize auto-update functionality
document.addEventListener('DOMContentLoaded', function() {
  // Update immediately on page load
  updateEmployeeStats();
  
  // Auto-update every 30 seconds
  setInterval(updateEmployeeStats, 30000);
});

// Update when page becomes visible again
document.addEventListener('visibilitychange', function() {
  if (!document.hidden) {
    updateEmployeeStats();
  }
});

// Manual refresh function for the refresh button
function refreshStats() {
  // Show loading state on refresh button
  const refreshBtn = document.querySelector('button[onclick="refreshStats()"]');
  const originalContent = refreshBtn.innerHTML;
  
  refreshBtn.innerHTML = '<i class="bi bi-arrow-clockwise me-1 spin"></i><span class="d-none d-lg-inline">Loading...</span>';
  refreshBtn.disabled = true;
  
  // Add spin animation to the icon
  const style = document.createElement('style');
  style.textContent = `
    .spin {
      animation: spin 1s linear infinite;
    }
    @keyframes spin {
      from { transform: rotate(0deg); }
      to { transform: rotate(360deg); }
    }
  `;
  document.head.appendChild(style);
  
  // Fetch updated statistics
  fetch('/api/employee-stats')
    .then(response => response.json())
    .then(data => {
      // Update statistics display
      const statValues = document.querySelectorAll('.stat-value');
      if (statValues[0]) statValues[0].textContent = data.totalEmployees || 0;
      if (statValues[1]) statValues[1].textContent = data.totalFulltimeInstructors || 0;
      if (statValues[2]) statValues[2].textContent = data.totalParttimeInstructors || 0;
      if (statValues[3]) statValues[3].textContent = data.totalStaff || 0;
      if (statValues[4]) statValues[4].textContent = data.totalUtility || 0;
      
      // Show success feedback
      Swal.fire({
        toast: true,
        position: 'top-end',
        icon: 'success',
        title: 'Statistics Updated!',
        showConfirmButton: false,
        timer: 2000,
        timerProgressBar: true
      });
      
      console.log('Statistics refreshed:', data);
    })
    .catch(error => {
      console.error('Error refreshing statistics:', error);
      
      // Show error feedback
      Swal.fire({
        toast: true,
        position: 'top-end',
        icon: 'error',
        title: 'Failed to refresh statistics',
        showConfirmButton: false,
        timer: 3000
      });
    })
    .finally(() => {
      // Restore button state
      setTimeout(() => {
        refreshBtn.innerHTML = originalContent;
        refreshBtn.disabled = false;
        document.head.removeChild(style);
      }, 1000);
    });
}

// Show detailed employee statistics
function showDetailedStats() {
  // Show loading
  Swal.fire({
    title: 'Loading Statistics...',
    html: 'Fetching detailed employee data...',
    allowOutsideClick: false,
    didOpen: () => {
      Swal.showLoading();
    }
  });
  
  // Fetch detailed statistics
  fetch('/api/employee-stats/detailed')
    .then(response => response.json())
    .then(data => {
      if (data.error) {
        throw new Error(data.message || 'Unknown error');
      }
      
      // Build detailed HTML
      let html = `
        <div class="text-start">
          <h6 class="text-primary mb-3"><i class="bi bi-graph-up me-2"></i>Employee Statistics Summary</h6>
          <div class="row g-2 mb-3">
            <div class="col-6"><strong>Total Employees:</strong> ${data.summary.totalEmployees}</div>
            <div class="col-6"><strong>Full-time:</strong> ${data.summary.totalFulltimeInstructors}</div>
            <div class="col-6"><strong>Part-time:</strong> ${data.summary.totalParttimeInstructors}</div>
            <div class="col-6"><strong>Staff:</strong> ${data.summary.totalStaff}</div>
            <div class="col-6"><strong>Utility:</strong> ${data.summary.totalUtility}</div>
          </div>
          
          <h6 class="text-success mb-2"><i class="bi bi-people me-2"></i>Employee Breakdown</h6>
      `;
      
      // Add employee lists
      const categories = [
        { key: 'fulltime_names', label: 'Full-time Instructors', icon: 'bi-person-badge-fill', color: 'success' },
        { key: 'parttime_names', label: 'Part-time Instructors', icon: 'bi-person-check-fill', color: 'warning' },
        { key: 'staff_names', label: 'Staff Members', icon: 'bi-person-workspace', color: 'danger' },
        { key: 'utility_names', label: 'Utility Workers', icon: 'bi-tools', color: 'secondary' }
      ];
      
      categories.forEach(category => {
        const employees = data.breakdown[category.key] || [];
        html += `
          <div class="mb-2">
            <small class="text-${category.color} fw-bold">
              <i class="bi ${category.icon} me-1"></i>${category.label} (${employees.length})
            </small>
            <div class="ms-3 small text-muted">
              ${employees.length > 0 ? employees.join(', ') : 'No employees found'}
            </div>
          </div>
        `;
      });
      
      html += `
          <hr class="my-3">
          <h6 class="text-info mb-2"><i class="bi bi-database me-2"></i>Database Status</h6>
          <div class="small">
            <div>✅ Full-time Table: ${data.table_info.fulltime_table_exists ? 'Available' : 'Missing'}</div>
            <div>✅ Part-time Table: ${data.table_info.parttime_table_exists ? 'Available' : 'Missing'}</div>
            <div>✅ Staff Table: ${data.table_info.staff_table_exists ? 'Available' : 'Missing'}</div>
            <div>✅ Utility Table: ${data.table_info.utility_table_exists ? 'Available' : 'Missing'}</div>
          </div>
        </div>
      `;
      
      // Show detailed statistics
      Swal.fire({
        title: 'Employee Statistics Details',
        html: html,
        width: '600px',
        confirmButtonText: 'Close',
        confirmButtonColor: '#3498db',
        customClass: {
          popup: 'text-start'
        }
      });
      
    })
    .catch(error => {
      console.error('Error fetching detailed statistics:', error);
      
      Swal.fire({
        icon: 'error',
        title: 'Error',
        text: 'Failed to load detailed statistics: ' + error.message,
        confirmButtonColor: '#dc3545'
      });
    });
}

// Function to handle instructor rate selection
function showInstructorRate(rate) {
  // Show loading message
  Swal.fire({
    title: 'Loading...',
    html: 'Fetching instructors with rate: ₱' + rate,
    allowOutsideClick: false,
    didOpen: () => {
      Swal.showLoading();
    }
  });
  
  // Fetch data from the server
  fetch(`/api/instructors-by-rate?rate_range=${rate}`)
    .then(response => response.json())
    .then(data => {
      if (data.error) {
        throw new Error(data.message || data.error);
      }
      
      let title = `Instructors with Rate: ₱${rate}`;
      let content = `
        <div class="text-start">
          <h6 class="text-primary mb-3">
            <i class="bi bi-currency-dollar me-2"></i>Rate: ₱${rate}
            <span class="badge bg-primary ms-2">${data.count} instructor${data.count !== 1 ? 's' : ''}</span>
          </h6>
      `;
      
      if (data.instructors && data.instructors.length > 0) {
        content += `
          <div class="table-responsive">
            <table class="table table-sm table-hover">
              <thead class="table-primary">
                <tr>
                  <th><i class="bi bi-person me-1"></i>Name</th>
                  <th><i class="bi bi-briefcase me-1"></i>Designation</th>
                  <th><i class="bi bi-cash me-1"></i>Rate</th>
                  <th><i class="bi bi-tag me-1"></i>Type</th>
                </tr>
              </thead>
              <tbody>
        `;
        
        data.instructors.forEach(instructor => {
          const typeColor = instructor.type === 'Full-time Instructor' ? 'success' : 'info';
          content += `
            <tr>
              <td><strong>${instructor.name}</strong></td>
              <td>${instructor.designation || 'N/A'}</td>
              <td><span class="badge bg-warning">₱${instructor.rate}</span></td>
              <td><span class="badge bg-${typeColor}">${instructor.type}</span></td>
            </tr>
          `;
        });
        
        content += `
              </tbody>
            </table>
          </div>
        `;
      } else {
        content += `
          <div class="alert alert-info text-center">
            <i class="bi bi-info-circle me-2"></i>
            No instructors found with rate ₱${rate}
          </div>
        `;
      }
      
      content += `</div>`;
      
      // Show the results with print button
      Swal.fire({
        title: title,
        html: content,
        width: '700px',
        showCancelButton: true,
        confirmButtonText: '<i class="bi bi-printer me-2"></i>Print',
        cancelButtonText: 'Close',
        confirmButtonColor: '#28a745',
        cancelButtonColor: '#6c757d',
        customClass: {
          popup: 'text-start'
        }
      }).then((result) => {
        if (result.isConfirmed) {
          printInstructorRateData(rate, data);
        }
      });
    })
    .catch(error => {
      console.error('Error fetching instructor rate data:', error);
      
      Swal.fire({
        icon: 'error',
        title: 'Error',
        text: 'Failed to load instructor rate data: ' + error.message,
        confirmButtonColor: '#dc3545'
      });
    });
}

// Function to print instructor rate data
function printInstructorRateData(rate, data) {
  // Create print window
  const printWindow = window.open('', '_blank', 'width=800,height=600');
  
  // Generate print content
  let printContent = `
    <!DOCTYPE html>
    <html>
    <head>
      <title>Instructor Rate Report - ₱${rate}</title>
      <style>
        body {
          font-family: Arial, sans-serif;
          margin: 20px;
          color: #333;
        }
        .header {
          text-align: center;
          margin-bottom: 30px;
          border-bottom: 2px solid #3498db;
          padding-bottom: 20px;
        }
        .header h1 {
          color: #3498db;
          margin: 0;
          font-size: 24px;
        }
        .header h2 {
          color: #666;
          margin: 5px 0;
          font-size: 18px;
        }
        .info-section {
          margin-bottom: 20px;
          background: #f8f9fa;
          padding: 15px;
          border-radius: 5px;
        }
        .info-row {
          display: flex;
          justify-content: space-between;
          margin-bottom: 5px;
        }
        .info-row:last-child {
          margin-bottom: 0;
        }
        table {
          width: 100%;
          border-collapse: collapse;
          margin-top: 20px;
        }
        th, td {
          border: 1px solid #ddd;
          padding: 12px;
          text-align: left;
        }
        th {
          background-color: #3498db;
          color: white;
          font-weight: bold;
        }
        tr:nth-child(even) {
          background-color: #f9f9f9;
        }
        tr:hover {
          background-color: #f5f5f5;
        }
        .rate-badge {
          background: #ffc107;
          color: #000;
          padding: 4px 8px;
          border-radius: 4px;
          font-weight: bold;
        }
        .type-fulltime {
          background: #28a745;
          color: white;
          padding: 4px 8px;
          border-radius: 4px;
          font-size: 12px;
        }
        .type-parttime {
          background: #17a2b8;
          color: white;
          padding: 4px 8px;
          border-radius: 4px;
          font-size: 12px;
        }
        .no-data {
          text-align: center;
          padding: 40px;
          color: #666;
          font-style: italic;
        }
        .footer {
          margin-top: 30px;
          text-align: center;
          font-size: 12px;
          color: #666;
          border-top: 1px solid #ddd;
          padding-top: 15px;
        }
        @media print {
          body { margin: 0; }
          .header { page-break-after: avoid; }
          table { page-break-inside: avoid; }
        }
      </style>
    </head>
    <body>
      <div class="header">
        <h1>MCC Payroll System</h1>
        <h2>Instructor Rate Report</h2>
      </div>
      
      <div class="info-section">
        <div class="info-row">
          <strong>Rate Range:</strong>
          <span>₱${rate}</span>
        </div>
        <div class="info-row">
          <strong>Total Instructors:</strong>
          <span>${data.count} instructor${data.count !== 1 ? 's' : ''}</span>
        </div>
        <div class="info-row">
          <strong>Generated On:</strong>
          <span>${new Date().toLocaleString()}</span>
        </div>
      </div>
  `;
  
  if (data.instructors && data.instructors.length > 0) {
    printContent += `
      <table>
        <thead>
          <tr>
            <th style="width: 5%;">#</th>
            <th style="width: 35%;">Instructor Name</th>
            <th style="width: 30%;">Designation</th>
            <th style="width: 15%;">Rate/Hour</th>
            <th style="width: 15%;">Type</th>
          </tr>
        </thead>
        <tbody>
    `;
    
    data.instructors.forEach((instructor, index) => {
      const typeClass = instructor.type === 'Full-time Instructor' ? 'type-fulltime' : 'type-parttime';
      printContent += `
        <tr>
          <td>${index + 1}</td>
          <td><strong>${instructor.name}</strong></td>
          <td>${instructor.designation || 'N/A'}</td>
          <td><span class="rate-badge">₱${instructor.rate}</span></td>
          <td><span class="${typeClass}">${instructor.type}</span></td>
        </tr>
      `;
    });
    
    printContent += `
        </tbody>
      </table>
    `;
  } else {
    printContent += `
      <div class="no-data">
        <h3>No instructors found with rate ₱${rate}</h3>
        <p>There are currently no instructors in the system with this rate range.</p>
      </div>
    `;
  }
  
  printContent += `
      <div class="footer">
        <p>This report was generated from the MCC Payroll System on ${new Date().toLocaleDateString()}</p>
        <p>Madridejos Community College - Payroll Management System</p>
      </div>
    </body>
    </html>
  `;
  
  // Write content to print window
  printWindow.document.write(printContent);
  printWindow.document.close();
  
  // Wait for content to load then print
  printWindow.onload = function() {
    printWindow.focus();
    printWindow.print();
    
    // Close print window after printing (optional)
    setTimeout(() => {
      printWindow.close();
    }, 1000);
  };
}
  // Mobile sidebar toggle
  document.addEventListener('DOMContentLoaded', function () {
    const btn = document.getElementById('mobileMenuBtn');
    const sidebar = document.getElementById('sidebar');
    if (btn && sidebar) {
      btn.addEventListener('click', function () {
        sidebar.classList.toggle('show');
      });
    }
  });
  </script>
</body>
</html>
