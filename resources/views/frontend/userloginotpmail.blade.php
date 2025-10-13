<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your OTP Code</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 40px 20px;
            min-height: 100vh;
        }
        .email-container {
            max-width: 600px;
            margin: 0 auto;
            background: #ffffff;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
        }
        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 40px 30px;
            text-align: center;
            color: white;
        }
        .header h1 {
            font-size: 28px;
            margin-bottom: 10px;
            font-weight: 600;
        }
        .header p {
            font-size: 14px;
            opacity: 0.9;
        }
        .content {
            padding: 50px 40px;
            text-align: center;
        }
        .greeting {
            font-size: 24px;
            color: #2d3748;
            margin-bottom: 15px;
            font-weight: 600;
        }
        .email-display {
            background: #f7fafc;
            padding: 15px 25px;
            border-radius: 10px;
            margin: 25px 0;
            border-left: 4px solid #667eea;
        }
        .email-label {
            font-size: 12px;
            color: #718096;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 5px;
        }
        .email-value {
            font-size: 16px;
            color: #2d3748;
            font-weight: 600;
        }
        .otp-label {
            font-size: 18px;
            color: #4a5568;
            margin: 30px 0 20px;
            font-weight: 500;
        }
        .otp-box {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            font-size: 42px;
            font-weight: bold;
            letter-spacing: 12px;
            padding: 25px 40px;
            border-radius: 15px;
            display: inline-block;
            margin: 20px 0;
            box-shadow: 0 10px 30px rgba(102, 126, 234, 0.4);
            font-family: 'Courier New', monospace;
        }
        .message {
            font-size: 15px;
            color: #718096;
            line-height: 1.8;
            margin: 30px 0;
        }
        .warning {
            background: #fff5f5;
            border-left: 4px solid #f56565;
            padding: 20px;
            border-radius: 10px;
            margin: 30px 0;
            text-align: left;
        }
        .warning-title {
            color: #c53030;
            font-weight: 600;
            margin-bottom: 8px;
            display: flex;
            align-items: center;
        }
        .warning-title:before {
            content: "⚠️";
            margin-right: 8px;
            font-size: 18px;
        }
        .warning p {
            color: #742a2a;
            font-size: 14px;
            line-height: 1.6;
        }
        .expiry {
            background: #fef5e7;
            border-left: 4px solid #f39c12;
            padding: 15px 20px;
            border-radius: 10px;
            margin: 25px 0;
            font-size: 14px;
            color: #7d6608;
        }
        .footer {
            background: #f7fafc;
            padding: 30px;
            text-align: center;
            border-top: 1px solid #e2e8f0;
        }
        .footer p {
            color: #718096;
            font-size: 13px;
            line-height: 1.8;
            margin: 5px 0;
        }
        .social-links {
            margin: 20px 0;
        }
        .social-links a {
            display: inline-block;
            width: 35px;
            height: 35px;
            background: #667eea;
            color: white;
            border-radius: 50%;
            line-height: 35px;
            margin: 0 5px;
            text-decoration: none;
            transition: all 0.3s ease;
        }
        .social-links a:hover {
            background: #764ba2;
            transform: translateY(-3px);
        }
        @media only screen and (max-width: 600px) {
            .content {
                padding: 30px 20px;
            }
            .otp-box {
                font-size: 32px;
                letter-spacing: 8px;
                padding: 20px 30px;
            }
            .greeting {
                font-size: 20px;
            }
        }
    </style>
</head>
<body>
    <div class="email-container">
        <!-- Header -->
        <div class="header">
            <h1>🔐 Security Verification</h1>
            <p>Your One-Time Password</p>
        </div>

        <!-- Content -->
        <div class="content">
            <div class="greeting">
                Hello, {{ $user->email }}! 👋
            </div>

            <div class="email-display">
                <div class="email-label">Your Account</div>
                <div class="email-value">{{ $user->email }}</div>
            </div>

            <p class="message">
                We received a request to access your account. Use the OTP code below to complete your verification.
            </p>

            <div class="otp-label">Your OTP is:</div>
            
            <div class="otp-box">
                {{ $user->otp ?? '123456' }}
            </div>

            <div class="expiry">
                ⏰ <strong>Note:</strong> This OTP will expire in 5 minutes.
            </div>

            <p class="message">
                Enter this code in the verification page to proceed. Do not share this code with anyone.
            </p>

            <div class="warning">
                <div class="warning-title">Security Alert</div>
                <p>If you did not request this OTP, please ignore this email or contact our support team immediately. Your account security is important to us.</p>
            </div>
        </div>

        <!-- Footer -->
        <div class="footer">
            <p><strong>{{ config('app.name', 'Your Company') }}</strong></p>
            <p>This is an automated message, please do not reply.</p>
            <p>© {{ date('Y') }} {{ config('app.name') }}. All rights reserved.</p>
            
            <div class="social-links">
                <a href="#">f</a>
                <a href="#">t</a>
                <a href="#">in</a>
            </div>
            
            <p style="margin-top: 20px; font-size: 11px;">
                Need help? Contact us at support@yourcompany.com
            </p>
        </div>
    </div>
</body>
</html>