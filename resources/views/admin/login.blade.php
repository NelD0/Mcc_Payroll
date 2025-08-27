<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            height: 100vh;
            margin: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            /* MCC building background image */
            background: linear-gradient(rgba(52, 152, 219, 0.3), rgba(0, 0, 0, 0.4)), url('{{ asset('images/mcc.jpg') }}');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            background-attachment: fixed;
        }

        .login-container {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            padding: 25px;
            border-radius: 15px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.3);
            width: 100%;
            max-width: 350px;
            min-width: 0;
            box-sizing: border-box;
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .logo {
            width: 60px;
            height: 60px;
            margin: 0 auto 15px auto;
            display: block;
            border-radius: 50%;
            border: 2px solid rgba(52, 152, 219, 0.6);
            box-shadow: 0 3px 12px rgba(0, 0, 0, 0.2);
            object-fit: cover;
        }

        h2 {
            text-align: center;
            margin-bottom: 20px;
            color: #333;
        }

        .admin-badge {
            background: #3498db;
            color: white;
            padding: 5px 15px;
            border-radius: 20px;
            font-size: 12px;
            text-align: center;
            margin-bottom: 20px;
            font-weight: bold;
        }

        .input-group {
            margin-bottom: 15px;
        }

        .input-group label {
            display: block;
            margin-bottom: 5px;
            color: #555;
            font-size: 14px;
        }

        .input-group input {
            width: 100%;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 16px;
            box-sizing: border-box;
        }

        .input-group input:focus {
            border-color: #3498db;
            outline: none;
            box-shadow: 0 0 5px rgba(52, 152, 219, 0.5);
        }

        button {
            width: 100%;
            padding: 14px;
            background: #3498db;
            border: none;
            border-radius: 5px;
            color: white;
            font-size: 18px;
            cursor: pointer;
        }

        button:hover {
            background: #2980b9;
        }

        button:disabled {
            opacity: 0.7;
            cursor: not-allowed;
        }

        .back-link {
            text-align: center;
            margin-top: 15px;
            font-size: 14px;
        }

        .back-link a {
            color: #3498db;
            text-decoration: none;
        }

        .back-link a:hover {
            text-decoration: underline;
        }

        @media (max-width: 600px) {
            body {
                padding: 10px;
                align-items: flex-start;
                min-height: 100vh;
            }
            .login-container {
                padding: 18px 10px;
                max-width: 100%;
                border-radius: 0;
                box-shadow: none;
            }
            h2 {
                font-size: 1.5rem;
            }
            .input-group input {
                font-size: 15px;
                padding: 10px;
            }
            button {
                font-size: 16px;
                padding: 12px;
            }
        }

        /* Custom SweetAlert2 styling for loading */
        .swal-login-loading-popup {
            border-radius: 15px !important;
            border: 2px solid #3498db !important;
            box-shadow: 0 10px 30px rgba(52, 152, 219, 0.2) !important;
        }
        
        .swal-login-loading-title {
            color: #3498db !important;
            font-weight: 700 !important;
            font-size: 1.5rem !important;
        }
        
        .swal-login-loading-content {
            color: #333 !important;
            font-size: 1rem !important;
        }

        /* Spinner animation */
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        
        .fa-spinner {
            animation: spin 1s linear infinite;
        }
    </style>
    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
    <div class="login-container">
        <img src="{{ asset('images/logo.png') }}" alt="MCC Logo" class="logo">
        <div class="admin-badge">
            <i class="fas fa-user-shield"></i> ADMIN LOGIN
        </div>
        <h2>Administrator Access</h2>
        <form id="adminLoginForm" action="{{ route('admin.login') }}" method="POST">
            @csrf
            <input type="hidden" name="user_type" value="admin">
            <div class="input-group">
                <label for="email">Admin Email</label>
                <input type="email" id="email" name="email" placeholder="Enter admin email" required>
            </div>
            <div class="input-group">
                <label for="password">Admin Password</label>
                <input type="password" id="password" name="password" placeholder="Enter admin password" required>
            </div>
            <button type="submit">
                <i class="fas fa-sign-in-alt"></i> Login as Admin
            </button>
            <p class="back-link">
                <a href="{{ url('/') }}">
                    <i class="fas fa-arrow-left"></i> Back to Main Login
                </a>
            </p>
        </form>
    </div>

    <script>
        // Show SweetAlert2 error if session('error') exists
        @if(session('error'))
            Swal.fire({
                icon: 'error',
                title: 'Access Denied!',
                text: '{{ session('error') }}',
                confirmButtonText: 'Try Again',
                confirmButtonColor: '#3498db',
                background: '#fff',
                color: '#333',
                showClass: {
                    popup: 'animate__animated animate__shakeX'
                },
                hideClass: {
                    popup: 'animate__animated animate__fadeOut'
                }
            });
        @endif

        // Show SweetAlert2 loading on login submit
        document.getElementById('adminLoginForm')?.addEventListener('submit', function(e) {
            const submitBtn = document.querySelector('button[type="submit"]');
            const originalText = submitBtn.innerHTML;
            
            // Change button text and disable it
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Authenticating...';
            submitBtn.disabled = true;
            
            Swal.fire({
                title: 'Verifying Admin Access...',
                text: 'Please wait while we verify your administrator credentials.',
                allowOutsideClick: false,
                allowEscapeKey: false,
                showConfirmButton: false,
                didOpen: () => {
                    Swal.showLoading();
                },
                background: '#fff',
                color: '#333',
                customClass: {
                    popup: 'swal-login-loading-popup',
                    title: 'swal-login-loading-title',
                    content: 'swal-login-loading-content'
                }
            });
            
            // Reset button after delay (in case of validation errors)
            setTimeout(() => {
                submitBtn.innerHTML = originalText;
                submitBtn.disabled = false;
            }, 5000);
        });

        // Success alert after login
        @if(session('success'))
            Swal.fire({
                icon: 'success',
                title: 'Welcome Admin!',
                text: '{{ session('success') }}',
                confirmButtonColor: '#3498db',
                timer: 2000,
                showConfirmButton: false
            });
        @endif
    </script>
</body>
</html>