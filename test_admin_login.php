<?php
// Simple test script to verify admin login functionality
require_once 'vendor/autoload.php';

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "Testing Admin Login Setup...\n\n";

// Test 1: Check if admin user exists
echo "1. Checking if admin user exists in database...\n";
try {
    $admin = DB::table('users')->where('email', 'admin@mcc.edu.ph')->first();
    if ($admin) {
        echo "✓ Admin user found: {$admin->name} ({$admin->email})\n";
        echo "✓ Role: {$admin->role}\n";
    } else {
        echo "✗ Admin user not found!\n";
    }
} catch (Exception $e) {
    echo "✗ Database error: " . $e->getMessage() . "\n";
}

// Test 2: Check password verification
echo "\n2. Testing password verification...\n";
if (isset($admin)) {
    $testPassword = 'admin123456';
    if (password_verify($testPassword, $admin->password)) {
        echo "✓ Password verification works correctly\n";
    } else {
        echo "✗ Password verification failed\n";
    }
}

// Test 3: Check if routes are properly defined
echo "\n3. Checking routes...\n";
try {
    $routes = app('router')->getRoutes();
    $adminLoginRoute = false;
    $dashboardRoute = false;
    
    foreach ($routes as $route) {
        if ($route->uri() === 'admin/login' && in_array('POST', $route->methods())) {
            $adminLoginRoute = true;
        }
        if ($route->uri() === 'dashboard' && in_array('GET', $route->methods())) {
            $dashboardRoute = true;
        }
    }
    
    if ($adminLoginRoute) {
        echo "✓ Admin login route (POST /admin/login) exists\n";
    } else {
        echo "✗ Admin login route missing\n";
    }
    
    if ($dashboardRoute) {
        echo "✓ Dashboard route (GET /dashboard) exists\n";
    } else {
        echo "✗ Dashboard route missing\n";
    }
} catch (Exception $e) {
    echo "✗ Route checking error: " . $e->getMessage() . "\n";
}

echo "\n4. Test Summary:\n";
echo "Admin Login Credentials:\n";
echo "Email: admin@mcc.edu.ph\n";
echo "Password: admin123456\n";
echo "Role: admin\n";
echo "\nAttendance Login Credentials:\n";
echo "Email: bsit@mcc.edu.ph (or bsba@mcc.edu.ph, bshm@mcc.edu.ph, education@mcc.edu.ph)\n";
echo "Password: bsit123456 (or respective course passwords)\n";
echo "Role: attendance_checker\n";

echo "\nTest completed!\n";
?>