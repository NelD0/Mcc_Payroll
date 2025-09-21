<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Attendance Login</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            height: 100vh;
            margin: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            /* Match Admin: gradient over MCC background */
            background: linear-gradient(rgba(52, 152, 219, 0.3), rgba(0, 0, 0, 0.4)), url('<?php echo e(asset('images/mcc.jpg')); ?>');
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

        .role-badge {
            background: #3498db;
            color: white;
            padding: 5px 15px;
            border-radius: 20px;
            font-size: 12px;
            text-align: center;
            margin-bottom: 20px;
            font-weight: bold;
        }

        .input-group { margin-bottom: 15px; }

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

        button:hover { background: #2980b9; }
        button:disabled { opacity: 0.7; cursor: not-allowed; }

        .links { text-align: center; margin-top: 12px; font-size: 14px; }
        .links a { color: #3498db; text-decoration: none; }
        .links a:hover { text-decoration: underline; }
        .back-link { text-align: center; margin-top: 24px; font-size: 14px; }
        .back-link a { color: #3498db; text-decoration: none; }
        .back-link a:hover { text-decoration: underline; }

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
            h2 { font-size: 1.5rem; }
            .input-group input { font-size: 15px; padding: 10px; }
            button { font-size: 16px; padding: 12px; }
        }

        /* SweetAlert2 custom (optional to match admin feel) */
        .swal-login-loading-popup { border-radius: 15px !important; border: 2px solid #3498db !important; box-shadow: 0 10px 30px rgba(52, 152, 219, 0.2) !important; }
        .swal-login-loading-title { color: #3498db !important; font-weight: 700 !important; font-size: 1.5rem !important; }
        .swal-login-loading-content { color: #333 !important; font-size: 1rem !important; }
        @keyframes spin { 0% { transform: rotate(0deg); } 100% { transform: rotate(360deg); } }
        .fa-spinner { animation: spin 1s linear infinite; }
    </style>
    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <div class="login-container">
        <img src="<?php echo e(asset('images/logo.png')); ?>" alt="MCC Logo" class="logo">
        <div class="role-badge">
            <i class="fas fa-clipboard-check"></i> ATTENDANCE LOGIN
        </div>
        <h2>Attendance Access</h2>
        <form id="attendance-login-form" action="<?php echo e(route('attendance.attendlog')); ?>" method="POST">
            <?php echo csrf_field(); ?>
            <input type="hidden" name="user_type" value="attendance">
            <div class="input-group">
                <label for="email">Attendance Email</label>
                <input type="email" id="email" name="email" placeholder="Enter attendance email" required>
            </div>
            <div class="input-group">
                <label for="password">Attendance Password</label>
                <input type="password" id="password" name="password" placeholder="Enter attendance password" required>
            </div>
            <button id="login-btn" type="submit">
                <i class="fas fa-sign-in-alt"></i> Login as Attendance
            </button>
            <p class="links">
                <a href="<?php echo e(route('attendance.forgot.form')); ?>">Forgot password?</a>
            </p>
            <p class="back-link">
                <a href="<?php echo e(url('/')); ?>"><i class="fas fa-arrow-left"></i> Back to Main Login</a>
            </p>
        </form>
    </div>

    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('attendance-login-form');
            const loginBtn = document.getElementById('login-btn');
            const lockoutRemaining = <?php echo json_encode(session('lockout_remaining', 0), 512) ?>;
            const attemptsLeft = <?php echo json_encode(session('attempts_left'), 15, 512) ?>;
            const errorMsg = <?php echo json_encode(session('error'), 15, 512) ?>;

            function startCountdown(seconds) {
                loginBtn.disabled = true;
                let remaining = seconds;
                const originalText = loginBtn.innerHTML;
                const timer = setInterval(() => {
                    remaining--;
                    loginBtn.innerHTML = `<i class="fas fa-hourglass-half"></i> Wait ${remaining}s`;
                    if (remaining <= 0) {
                        clearInterval(timer);
                        loginBtn.disabled = false;
                        loginBtn.innerHTML = originalText;
                        window.location.href = '<?php echo e(route('attendance.attendlog.form')); ?>';
                    }
                }, 1000);
            }

            // Error popup (invalid credentials / attempts left / lockout)
            if (errorMsg) {
                let text = errorMsg;
                if (attemptsLeft !== null && attemptsLeft >= 0) {
                    text += `\nAttempts left: ${attemptsLeft}`;
                }
                if (lockoutRemaining && lockoutRemaining > 0) {
                    text = 'Too many attempts. Please wait before trying again.';
                }

                Swal.fire({
                    icon: 'error',
                    title: 'Login Failed',
                    text: text,
                    confirmButtonText: 'OK',
                    confirmButtonColor: '#3498db',
                    background: '#fff',
                    color: '#333'
                });
            }

            // Lockout popup with countdown
            if (lockoutRemaining && lockoutRemaining > 0) {
                Swal.fire({
                    title: 'Locked Out',
                    html: 'Please wait <b id="countdown"></b> seconds before trying again.',
                    icon: 'warning',
                    allowOutsideClick: false,
                    allowEscapeKey: false,
                    showConfirmButton: false,
                    background: '#fff',
                    color: '#333',
                    didOpen: () => {
                        const countdownEl = Swal.getHtmlContainer().querySelector('#countdown');
                        let remaining = lockoutRemaining;
                        countdownEl.textContent = remaining;
                        const interval = setInterval(() => {
                            remaining--;
                            countdownEl.textContent = remaining;
                            if (remaining <= 0) {
                                clearInterval(interval);
                                Swal.close();
                            }
                        }, 1000);
                    }
                });

                startCountdown(lockoutRemaining);
            }

            // Loading state on submit (match admin behavior)
            form?.addEventListener('submit', function() {
                const originalText = loginBtn.innerHTML;
                loginBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Authenticating...';
                loginBtn.disabled = true;

                Swal.fire({
                    title: 'Verifying Access...',
                    text: 'Please wait while we verify your attendance credentials.',
                    allowOutsideClick: false,
                    allowEscapeKey: false,
                    showConfirmButton: false,
                    didOpen: () => { Swal.showLoading(); },
                    background: '#fff',
                    color: '#333',
                    customClass: {
                        popup: 'swal-login-loading-popup',
                        title: 'swal-login-loading-title',
                        content: 'swal-login-loading-content'
                    }
                });

                // Safety reset in case of validation bounce
                setTimeout(() => {
                    loginBtn.innerHTML = originalText;
                    loginBtn.disabled = false;
                }, 5000);
            });
        });
    </script>
</body>
</html><?php /**PATH C:\xampp\htdocs\Mcc_Payroll\resources\views/attendance/attendlog.blade.php ENDPATH**/ ?>