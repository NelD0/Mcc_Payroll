<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MCC Payroll System</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            height: 100vh;
            margin: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            /* MCC building background image */
            background: linear-gradient(rgba(0, 0, 0, 0.4), rgba(0, 0, 0, 0.4)), url('{{ asset('images/mcc.jpg') }}');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            background-attachment: fixed;
        }

        .container {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            padding: 40px;
            border-radius: 15px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.3);
            text-align: center;
            max-width: 400px;
            width: 100%;
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .logo {
            width: 80px;
            height: 80px;
            margin: 0 auto 20px auto;
            display: block;
            border-radius: 50%;
            border: 3px solid rgba(255, 255, 255, 0.8);
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
            object-fit: cover;
        }

        h1 {
            color: #333;
            margin-bottom: 10px;
            font-size: 1.8rem;
        }

        .subtitle {
            color: #666;
            margin-bottom: 30px;
            font-size: 1rem;
        }

        .role-buttons {
            display: flex;
            flex-direction: column;
            gap: 15px;
            margin-bottom: 20px;
        }

        .role-btn {
            display: block;
            padding: 15px 20px;
            border: none;
            border-radius: 8px;
            text-decoration: none;
            font-size: 1.1rem;
            font-weight: bold;
            transition: all 0.3s ease;
            cursor: pointer;
        }

        .admin-btn {
            background: #3498db;
            color: white;
        }

        .admin-btn:hover {
            background: #2980b9;
            transform: translateY(-2px);
        }

        .attendance-btn {
            background: rgba(255, 255, 255, 0.8);
            color: #3498db;
            border: 2px solid #3498db;
        }

        .attendance-btn:hover {
            background: #3498db;
            color: white;
            transform: translateY(-2px);
        }

        .footer-text {
            color: #666;
            font-size: 0.9rem;
        }

        .footer-text a {
            color: #3498db;
            text-decoration: none;
        }

        .footer-text a:hover {
            text-decoration: underline;
        }

        @media (max-width: 480px) {
            .container {
                margin: 20px;
                padding: 30px 20px;
            }
            
            h1 {
                font-size: 1.5rem;
            }
            
            .role-btn {
                padding: 12px 15px;
                font-size: 1rem;
            }
        }
    </style>
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
    <div class="container">
        <img src="{{ asset('images/logo.png') }}" alt="MCC Logo" class="logo">
        <h1>MCC Payroll System</h1>
        <p class="subtitle">Select your role to login</p>
        
        <div class="role-buttons">
            <a href="{{ url('/admin/login') }}" class="role-btn admin-btn">
                Administrator Login
            </a>
            <a href="{{ url('/attendance/attendlog') }}" class="role-btn attendance-btn">
                Attendance Checker Login
            </a>
        </div>
        
        <p class="footer-text">
            Need access? <a href="{{ url('/register') }}">Create New Account</a> | Contact Administrator
        </p>
    </div>

    <script>
        // Show error messages
        @if(session('error'))
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: '{{ session('error') }}',
                confirmButtonColor: '#3498db'
            });
        @endif

        // Show success messages
        @if(session('success'))
            Swal.fire({
                icon: 'success',
                title: 'Success',
                text: '{{ session('success') }}',
                timer: 2000,
                showConfirmButton: false
            });
        @endif
    </script>
</body>
</html>