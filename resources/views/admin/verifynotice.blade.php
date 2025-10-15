<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Verify Your Email</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f4f6f8;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .verification-card {
            max-width: 500px;
            margin: 80px auto;
            padding: 40px 30px;
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.1);
            text-align: center;
        }

        .verification-card h2 {
            color: #4f46e5;
            margin-bottom: 20px;
        }

        .verification-card p {
            color: #555;
            line-height: 1.6;
        }

        .btn-primary {
            background-color: #4f46e5;
            border: none;
            padding: 12px 25px;
            font-weight: bold;
            border-radius: 8px;
        }

        .resend-link {
            display: block;
            margin-top: 20px;
            color: #4f46e5;
            text-decoration: underline;
            cursor: pointer;
        }

        @media (max-width: 576px) {
            .verification-card {
                margin: 40px 15px;
                padding: 30px 20px;
            }
        }

        .expired-screen {
            background-color: #f8f9fa;
            height: 100vh;
            width: 100%;
        }

        .error-icon svg {
            animation: pulse 1.5s infinite;
        }

        @keyframes pulse {
            0% {
                transform: scale(1);
                opacity: 1;
            }

            50% {
                transform: scale(1.1);
                opacity: 0.8;
            }

            100% {
                transform: scale(1);
                opacity: 1;
            }
        }
    </style>
</head>

<body>

    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif


    @php
        $user = session('user');
    @endphp


    @if (isset($user))
        <div class="verification-card">
            <h2>Almost There! ✅</h2>
            <p>
                We have sent a verification link to your entered mail
                Please check your inbox and click the <strong>Verify My Email</strong> button.
            </p>
            <p>
                <strong>Note:</strong> This link will expire in 2 minutes.
            </p>



            <a href="{{ route('admin.resend_welcome_email', $user->id) }}" type="submit"
                class="btn btn-primary mt-3">Resend
                Verification Email</a>


            
        </div>
    @else
        <div class="expired-screen d-flex flex-column justify-content-center align-items-center vh-100">
            <div class="text-center">
                <!-- Red Error Icon -->
                <div class="error-icon mb-3">
                    <svg xmlns="http://www.w3.org/2000/svg" width="90" height="90" fill="none"
                        viewBox="0 0 24 24" stroke="red" stroke-width="2">
                        <circle cx="12" cy="12" r="10" stroke="red" stroke-width="2" fill="none" />
                        <line x1="8" y1="8" x2="16" y2="16" stroke="red"
                            stroke-width="2" />
                        <line x1="16" y1="8" x2="8" y2="16" stroke="red"
                            stroke-width="2" />
                    </svg>
                </div>

                <h2 class="text-danger fw-bold mb-2">Session Expired</h2>
                <p class="text-secondary mb-4">Your session has expired. Please log in again to continue.</p>

                <a  class="btn btn-danger px-4 py-2 rounded-3">
                    Go to Login
                </a>
            </div>
        </div>
    @endif


</body>

</html>
