<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Confirm Your Email</title>
    <style>
        body {
            background-color: #f4f6f8;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 600px;
            margin: 50px auto;
            background-color: #ffffff;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
            overflow: hidden;
        }
        .header {
            background-color: #4f46e5;
            color: #fff;
            text-align: center;
            padding: 30px 20px;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
        }
        .content {
            padding: 30px 20px;
            color: #333;
            line-height: 1.6;
        }
        .btn {
            display: inline-block;
            margin: 20px 0;
            padding: 12px 24px;
            background-color: #4f46e5;
            color: #fff !important;
            text-decoration: none;
            border-radius: 8px;
            font-weight: bold;
        }
        .footer {
            text-align: center;
            font-size: 13px;
            color: #888;
            padding: 15px;
            border-top: 1px solid #eee;
        }
        @media only screen and (max-width: 600px) {
            .container {
                margin: 20px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Welcome, {{ $user->email }}!</h1>
        </div>
        <div class="content">
            <p>Thank you for registering as an admin. To activate your account, please verify your email by clicking the button below.</p>
            
            <p style="text-align:center;">
                <a href="{{ $url }}" class="btn">Verify My Email</a>
            </p>

            <p><strong>Note:</strong> This link will expire in 2 minutes. If it expires, you can request a new verification link.</p>

            <p>Thanks,<br>{{ config('app.name') }} Team</p>
        </div>
        <div class="footer">
            &copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.
        </div>
    </div>
</body>
</html>
