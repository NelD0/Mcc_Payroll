<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - MCC Payroll System</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            min-height: 100vh;
            margin: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            background: radial-gradient(circle at top left, white 30%, transparent 30%) top left/50% 100% no-repeat,
                        radial-gradient(circle at bottom right, #3498db 30%, transparent 30%) bottom right/50% 100% no-repeat,
                        linear-gradient(135deg, #3498db, #5dade2, white);
            padding: 20px 0;
        }

        .register-container {
            background: rgba(255, 255, 255, 0.493);
            backdrop-filter: blur(5px);
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.2);
            width: 100%;
            max-width: 400px;
            box-sizing: border-box;
        }

        h2 {
            text-align: center;
            margin-bottom: 20px;
            color: #333;
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

        .input-group input,
        .input-group select {
            width: 100%;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 14px;
            box-sizing: border-box;
        }

        .input-group input:focus,
        .input-group select:focus {
            border-color: #ff0000;
            outline: none;
            box-shadow: 0 0 5px rgba(255, 0, 0, 0.5);
        }

        .role-badge {
            background: #ff0000;
            color: white;
            padding: 5px 15px;
            border-radius: 20px;
            font-size: 12px;
            text-align: center;
            margin-bottom: 20px;
            font-weight: bold;
        }

        .course-group {
            display: none;
            margin-top: 10px;
        }

        .course-group.show {
            display: block;
        }

        .error-message {
            color: #ff0000;
            font-size: 12px;
            margin-top: 5px;
        }

        .password-requirements {
            margin-top: 8px;
            padding: 10px;
            background: rgba(255, 255, 255, 0.8);
            border-radius: 5px;
            border: 1px solid #ddd;
        }

        .password-requirements small {
            font-weight: bold;
            color: #333;
        }

        .password-requirements ul {
            margin: 5px 0 0 0;
            padding-left: 15px;
            list-style: none;
        }

        .password-requirements li {
            font-size: 11px;
            margin: 2px 0;
            color: #ff0000;
        }

        .password-requirements li.valid {
            color: #28a745;
        }

        .password-strength {
            margin-top: 5px;
            height: 5px;
            background: #ddd;
            border-radius: 3px;
            overflow: hidden;
        }

        .password-strength-bar {
            height: 100%;
            width: 0%;
            transition: all 0.3s ease;
            border-radius: 3px;
        }

        .strength-weak { background: #ff0000; }
        .strength-fair { background: #ffa500; }
        .strength-good { background: #ffff00; }
        .strength-strong { background: #28a745; }

        @media (max-width: 480px) {
            .register-container {
                margin: 10px;
                padding: 20px;
            }
            
            h2 {
                font-size: 1.5rem;
            }
            
            .input-group input,
            .input-group select {
                padding: 10px;
                font-size: 14px;
            }
        }

        button {
            width: 100%;
            padding: 10px;
            background: #ff0000;
            border: none;
            border-radius: 5px;
            color: white;
            font-size: 16px;
            cursor: pointer;
        }

        button:hover {
            background: #cc0000;
        }

        .register-link {
            text-align: center;
            margin-top: 10px;
            font-size: 14px;
        }

        .register-link a {
            color: #ff0000;
            text-decoration: none;
        }

        .register-link a:hover {
            text-decoration: underline;
        }
    </style>
    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
    <div class="register-container">
        <div class="role-badge">
            <i class="fas fa-user-plus"></i> USER REGISTRATION
        </div>
        <h2>Create New Account</h2>
        <form method="POST" action="<?php echo e(url('/register')); ?>">
            <?php echo csrf_field(); ?>
            <div class="input-group">
                <label for="name"><i class="fas fa-user"></i> Full Name</label>
                <input type="text" id="name" name="name" value="<?php echo e(old('name')); ?>" placeholder="Enter your full name" required>
                <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <div class="error-message"><?php echo e($message); ?></div> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>
            
            <div class="input-group">
                <label for="email"><i class="fas fa-envelope"></i> Email Address</label>
                <input type="email" id="email" name="email" value="<?php echo e(old('email')); ?>" placeholder="Enter your email address" required>
                <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <div class="error-message"><?php echo e($message); ?></div> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>
            
            <div class="input-group">
                <label for="role"><i class="fas fa-user-tag"></i> User Role</label>
                <select id="role" name="role" required onchange="toggleCourseField()">
                    <option value="">Select your role</option>
                    <option value="admin" <?php echo e(old('role') == 'admin' ? 'selected' : ''); ?>>Administrator</option>
                    <option value="attendance_checker" <?php echo e(old('role') == 'attendance_checker' ? 'selected' : ''); ?>>Attendance Checker</option>
                </select>
                <?php $__errorArgs = ['role'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <div class="error-message"><?php echo e($message); ?></div> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>
            
            <div class="input-group course-group" id="courseGroup">
                <label for="course"><i class="fas fa-graduation-cap"></i> Department/Course</label>
                <select id="course" name="course">
                    <option value="">Select department</option>
                    <option value="bsit" <?php echo e(old('course') == 'bsit' ? 'selected' : ''); ?>>BSIT (Information Technology)</option>
                    <option value="bsba" <?php echo e(old('course') == 'bsba' ? 'selected' : ''); ?>>BSBA (Business Administration)</option>
                    <option value="bshm" <?php echo e(old('course') == 'bshm' ? 'selected' : ''); ?>>BSHM (Hospitality Management)</option>
                    <option value="bsed" <?php echo e(old('course') == 'bsed' ? 'selected' : ''); ?>>BSED (Secondary Education)</option>
                    <option value="beed" <?php echo e(old('course') == 'beed' ? 'selected' : ''); ?>>BEED (Elementary Education)</option>
                </select>
                <?php $__errorArgs = ['course'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <div class="error-message"><?php echo e($message); ?></div> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>
            
            <div class="input-group">
                <label for="password"><i class="fas fa-lock"></i> Password</label>
                <input type="password" id="password" name="password" placeholder="Min. 12 chars, uppercase, lowercase, number, special char" required>
                <div class="password-requirements">
                    <small>Password must contain:</small>
                    <ul>
                        <li id="length">✗ At least 12 characters</li>
                        <li id="uppercase">✗ One uppercase letter</li>
                        <li id="lowercase">✗ One lowercase letter</li>
                        <li id="number">✗ One number</li>
                        <li id="special">✗ One special character (@$!%*?&)</li>
                    </ul>
                    <div class="password-strength">
                        <div class="password-strength-bar" id="strengthBar"></div>
                    </div>
                    <small id="strengthText">Password strength: Weak</small>
                </div>
                <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <div class="error-message"><?php echo e($message); ?></div> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>
            
            <div class="input-group">
                <label for="password_confirmation"><i class="fas fa-lock"></i> Confirm Password</label>
                <input type="password" id="password_confirmation" name="password_confirmation" placeholder="Confirm your password" required>
            </div>
            
            <button type="submit">
                <i class="fas fa-user-plus"></i> Create Account
            </button>
            
            <p class="register-link">
                Already have an account? <a href="<?php echo e(url('/')); ?>">
                    <i class="fas fa-sign-in-alt"></i> Back to Login
                </a>
            </p>
        </form>
    </div>

    <script>
        function toggleCourseField() {
            const roleSelect = document.getElementById('role');
            const courseGroup = document.getElementById('courseGroup');
            const courseSelect = document.getElementById('course');
            
            if (roleSelect.value === 'attendance_checker') {
                courseGroup.classList.add('show');
                courseSelect.required = true;
            } else {
                courseGroup.classList.remove('show');
                courseSelect.required = false;
                courseSelect.value = '';
            }
        }

        // Show course field if attendance_checker was previously selected (for validation errors)
        document.addEventListener('DOMContentLoaded', function() {
            toggleCourseField();
            setupPasswordValidation();
        });

        function setupPasswordValidation() {
            const passwordInput = document.getElementById('password');
            const lengthCheck = document.getElementById('length');
            const uppercaseCheck = document.getElementById('uppercase');
            const lowercaseCheck = document.getElementById('lowercase');
            const numberCheck = document.getElementById('number');
            const specialCheck = document.getElementById('special');
            const strengthBar = document.getElementById('strengthBar');
            const strengthText = document.getElementById('strengthText');

            passwordInput.addEventListener('input', function() {
                const password = this.value;
                let score = 0;

                // Check length
                if (password.length >= 12) {
                    lengthCheck.textContent = '✓ At least 12 characters';
                    lengthCheck.classList.add('valid');
                    score++;
                } else {
                    lengthCheck.textContent = '✗ At least 12 characters';
                    lengthCheck.classList.remove('valid');
                }

                // Check uppercase
                if (/[A-Z]/.test(password)) {
                    uppercaseCheck.textContent = '✓ One uppercase letter';
                    uppercaseCheck.classList.add('valid');
                    score++;
                } else {
                    uppercaseCheck.textContent = '✗ One uppercase letter';
                    uppercaseCheck.classList.remove('valid');
                }

                // Check lowercase
                if (/[a-z]/.test(password)) {
                    lowercaseCheck.textContent = '✓ One lowercase letter';
                    lowercaseCheck.classList.add('valid');
                    score++;
                } else {
                    lowercaseCheck.textContent = '✗ One lowercase letter';
                    lowercaseCheck.classList.remove('valid');
                }

                // Check number
                if (/\d/.test(password)) {
                    numberCheck.textContent = '✓ One number';
                    numberCheck.classList.add('valid');
                    score++;
                } else {
                    numberCheck.textContent = '✗ One number';
                    numberCheck.classList.remove('valid');
                }

                // Check special character
                if (/[@$!%*?&]/.test(password)) {
                    specialCheck.textContent = '✓ One special character (@$!%*?&)';
                    specialCheck.classList.add('valid');
                    score++;
                } else {
                    specialCheck.textContent = '✗ One special character (@$!%*?&)';
                    specialCheck.classList.remove('valid');
                }

                // Update strength bar
                const percentage = (score / 5) * 100;
                strengthBar.style.width = percentage + '%';

                // Update strength text and color
                if (score === 0) {
                    strengthBar.className = 'password-strength-bar strength-weak';
                    strengthText.textContent = 'Password strength: Very Weak';
                } else if (score <= 2) {
                    strengthBar.className = 'password-strength-bar strength-weak';
                    strengthText.textContent = 'Password strength: Weak';
                } else if (score <= 3) {
                    strengthBar.className = 'password-strength-bar strength-fair';
                    strengthText.textContent = 'Password strength: Fair';
                } else if (score <= 4) {
                    strengthBar.className = 'password-strength-bar strength-good';
                    strengthText.textContent = 'Password strength: Good';
                } else {
                    strengthBar.className = 'password-strength-bar strength-strong';
                    strengthText.textContent = 'Password strength: Strong';
                }
            });
        }

        // Show error messages
        <?php if(session('error')): ?>
            Swal.fire({
                icon: 'error',
                title: 'Registration Failed',
                text: '<?php echo e(session('error')); ?>',
                confirmButtonColor: '#ff0000'
            });
        <?php endif; ?>

        // Show success messages
        <?php if(session('success')): ?>
            Swal.fire({
                icon: 'success',
                title: 'Registration Successful!',
                text: '<?php echo e(session('success')); ?>',
                confirmButtonColor: '#ff0000',
                timer: 3000,
                showConfirmButton: true
            });
        <?php endif; ?>
    </script>
</body>
</html>
<?php /**PATH C:\xampp\htdocs\Mcc_Payroll\resources\views/auth/register.blade.php ENDPATH**/ ?>