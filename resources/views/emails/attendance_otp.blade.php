<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Your Verification Code</title>
    <style>
        /* Basic, email-safe styles */
        body { margin: 0; padding: 24px; background: #f6f9fc; font-family: Arial, sans-serif; color: #1f2937; }
        .card { max-width: 560px; margin: 0 auto; background: #ffffff; border: 1px solid #e5e7eb; border-radius: 12px; padding: 24px; }
        .header { text-align: center; margin-bottom: 16px; }
        .logo { width: 56px; height: 56px; object-fit: contain; display: inline-block; }
        .title { text-align: center; font-size: 22px; margin: 8px 0 4px; color: #1e3a8a; font-weight: bold; }
        .subtitle { text-align: center; font-size: 14px; color: #6b7280; margin: 0 0 20px; }
        .otp { display: block; text-align: center; padding: 14px 16px; font-size: 32px; letter-spacing: 6px; background: #eef2ff; border: 1px dashed #1e40af; border-radius: 10px; color: #1e40af; font-weight: bold; font-family: 'Courier New', Courier, monospace; }
        .note { margin-top: 16px; font-size: 13px; color: #4b5563; }
        .footer { margin-top: 20px; font-size: 12px; color: #9ca3af; text-align: center; }
    </style>
</head>
<body>
    <div class="card">
        <div class="header">
            <img class="logo" src="{{ asset('images/logo.png') }}" alt="MCC Payroll Logo">
        </div>
        <h1 class="title">Your Verification Code</h1>
        <p class="subtitle">Use this code to verify you own this email and reset your Attendance Checker password.</p>
        <div class="otp">{{ $otp }}</div>
        <p class="note">This code will expire in 10 minutes. If you didn’t request a password reset, you can safely ignore this email.</p>
        <p class="footer">MCC Payroll System</p>
    </div>
</body>
</html>