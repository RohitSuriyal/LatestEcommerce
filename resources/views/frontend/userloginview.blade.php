<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | Flipkart Style</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <style>
        body {
            margin: 0;
            font-family: 'Roboto', sans-serif;
            background-color: #f1f3f6;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .login-container {
            display: flex;
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
            width: 1000px;
            overflow: hidden;
            min-height: 600px;
        }

        .left-panel {
            background-color: #2874f0;
            color: white;
            padding: 60px 40px;
            width: 40%;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .left-panel h2 {
            font-size: 32px;
            margin-bottom: 20px;
        }

        .left-panel p {
            font-size: 16px;
            line-height: 24px;
        }

        .right-panel {
            width: 60%;
            padding: 60px 50px;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .form-group {
            margin-bottom: 25px;
        }

        .form-group input {
            width: 100%;
            padding: 16px;
            border: 1px solid #ccc;
            border-radius: 6px;
            outline: none;
            font-size: 16px;
            transition: border-color 0.3s ease;
        }

        .form-group input:focus {
            border-color: #2874f0;
            box-shadow: 0 0 5px rgba(40, 116, 240, 0.3);
        }

        .btn {
            background-color: #fb641b;
            color: white;
            border: none;
            padding: 16px;
            font-size: 17px;
            font-weight: 500;
            border-radius: 6px;
            cursor: pointer;
            width: 100%;
            transition: background-color 0.3s ease;
        }

        .btn:hover {
            background-color: #e35a14;
        }

        .signup-link {
            margin-top: 25px;
            text-align: center;
            font-size: 15px;
        }

        .signup-link a {
            color: #2874f0;
            text-decoration: none;
            font-weight: 600;
        }

        .signup-link a:hover {
            text-decoration: underline;
        }

        @media(max-width: 768px) {
            .login-container {
                flex-direction: column;
                width: 90%;
            }

            .left-panel,
            .right-panel {
                width: 100%;
                padding: 40px 30px;
            }

            .left-panel {
                align-items: center;
                text-align: center;
            }
        }
    </style>
</head>

<body>

    <div class="login-container">
        <div class="left-panel">
            <h2>Login</h2>
            <p>Get access to your Orders, Wishlist and Recommendations.</p>
        </div>
        <div class="right-panel">


            <x-frontend.failure />
            <x-frontend.success />
            <form action="{{ route('frontend.userlogin') }}">
                <div class="form-group">
                    <input value="{{ session("email") ?session("email"):'' }}" type="text" name="email" placeholder="Enter Email" required>
                </div>

                @if (session('success'))
                    <div class="form-group">
                        <input type="text" name="otp" placeholder="Enter otp" required>
                    </div>
                @endif

                @if (session('success'))
                    <button type="submit" class="btn">Verify OTP</button>
                @else
                    <button type="submit" class="btn">Request OTP</button>
                @endif


            </form>
            <div class="signup-link">
                New to our site? <a href="{{ route('frontend.signupview') }}">Create an account</a>
            </div>
        </div>
    </div>
</body>



</html>
