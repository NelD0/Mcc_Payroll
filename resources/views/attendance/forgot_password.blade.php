<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password - Attendance Checker</title>
    <style>
        body { font-family: Arial, sans-serif; height: 100vh; margin: 0; display: flex; justify-content: center; align-items: center; background: linear-gradient(rgba(52, 152, 219, 0.3), rgba(0, 0, 0, 0.4)), url('{{ asset('images/mcc.jpg') }}'); background-size: cover; background-position: center; }
        .container { background: rgba(255, 255, 255, 0.95); padding: 24px; border-radius: 12px; width: 100%; max-width: 380px; box-shadow: 0 8px 24px rgba(0,0,0,0.2); }
        h2 { text-align: center; margin: 0 0 16px; }
        .input-group { margin-bottom: 14px; }
        label { display:block; margin-bottom:6px; color:#555; font-size:14px; }
        input { width:100%; padding:12px; border:1px solid #ddd; border-radius:6px; font-size:16px; }
        button { width:100%; padding:12px; background:#3498db; border:none; color:#fff; border-radius:6px; font-size:16px; cursor:pointer; }
        button:hover { background:#2980b9; }
        .link { text-align:center; margin-top:12px; font-size:14px; }
        .link a { color:#3498db; text-decoration:none; }
        .link a:hover { text-decoration:underline; }
    </style>
</head>
<body>
    <div class="container">
        <h2><i class="fas fa-key"></i> Forgot Password</h2>
        @if(session('error'))
            <div style="color:#c0392b; margin-bottom:8px;">{{ session('error') }}</div>
        @endif
        @if(session('success'))
            <div style="color:#27ae60; margin-bottom:8px;">{{ session('success') }}</div>
        @endif
        <form action="{{ route('attendance.forgot.send') }}" method="POST">
            @csrf
            <div class="input-group">
                <label for="email">Attendance Checker Email</label>
                <input type="email" id="email" name="email" placeholder="you@example.com" required>
            </div>
            <button type="submit">Send OTP to Email</button>
        </form>
        <div class="link">
            <a href="{{ route('attendance.attendlog.form') }}">Back to Attendance Login</a>
        </div>
    </div>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</body>
</html>