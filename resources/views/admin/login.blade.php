<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta http-equiv="X-UA-Compatible" content="ie=edge" />
    <title>Login Form</title>

    <style>
        /* Reset default spacing */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body,
        html {
            height: 100%;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, 'Open Sans', 'Helvetica Neue', sans-serif;
        }

        /* ---------- Main background ---------- */
        .main_div {
            position: relative;
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
            overflow: hidden;
        }

        .main_div::before {
            content: "";
            position: absolute;
            inset: 0;
            /* top:0 left:0 right:0 bottom:0    */
            background-image: url('/images/background.png');
            background-size: cover;
            background-position: center;
            filter: blur(8px);
            transform: scale(1.1);
            /* Prevents edge blur cutoff */
            z-index: 0;
        }

        /* ---------- Form styling ---------- */
        .form {

            z-index: 1;
            display: flex;
            flex-direction: column;
            gap: 10px;
            background: rgba(255, 255, 255, 0.8);
            backdrop-filter: blur(6px);
            padding: 30px;
            width: 100%;
            max-width: 450px;
            border-radius: 20px;
            box-shadow: 0 4px 25px rgba(0, 0, 0, 0.2);
        }

        ::placeholder {
            font-family: inherit;
        }

        .form button {
            align-self: flex-end;
        }

        .flex-column>label {
            color: #151717;
            font-weight: 600;
        }

        .inputForm {
            border: 1.5px solid #ecedec;
            border-radius: 10px;
            height: 50px;
            display: flex;
            align-items: center;
            padding-left: 10px;
            transition: 0.2s ease-in-out;
            background: white;
        }

        .input {
            margin-left: 10px;
            border: none;
            width: 85%;
            height: 100%;
            border-radius: 10px;
            font-size: 15px;
        }

        .input:focus {
            outline: none;
        }

        .inputForm:focus-within {
            border: 1.5px solid #2d79f3;
        }

        .flex-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 10px;
        }

        .flex-row>div>label {
            font-size: 14px;
            color: black;
            font-weight: 400;
        }

        .span {
            font-size: 14px;
            color: #2d79f3;
            font-weight: 500;
            cursor: pointer;
        }

        .button-submit {
            margin: 20px 0 10px 0;
            background-color: #151717;
            border: none;
            color: white;
            font-size: 15px;
            font-weight: 500;
            border-radius: 10px;
            height: 50px;
            width: 100%;
            cursor: pointer;
            transition: 0.3s ease;
        }

        .button-submit:hover {
            background-color: #252727;
        }

        .p {
            text-align: center;
            color: black;
            font-size: 14px;
            margin: 5px 0;
        }

        .btn {
            margin-top: 10px;
            width: 100%;
            height: 50px;
            border-radius: 10px;
            display: flex;
            justify-content: center;
            align-items: center;
            font-weight: 500;
            gap: 10px;
            border: 1px solid #ededef;
            background-color: white;
            cursor: pointer;
            transition: 0.2s ease-in-out;
        }

        .btn:hover {
            border: 1px solid #2d79f3;
        }

        /* Responsive */
        @media (max-width: 500px) {
            .form {
                padding: 20px;
            }
        }

        .text-danger {
            color: red;
        }
    </style>
</head>

<body>

    <div class="main_div">
        <form class="form" method="post" action="{{route('admin.login')}}">
            @csrf
            <div class="flex-column">
                <label>Email</label>
            </div>
            <div class="inputForm">
                <svg height="20" width="20" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 32 32">
                    <path
                        d="m30.853 13.87a15 15 0 0 0 -29.729 4.082 15.1 15.1 0 0 0 12.876 12.918 15.6 15.6 0 0 0 2.016.13 14.85 14.85 0 0 0 7.715-2.145 1 1 0 1 0 -1.031-1.711 13.007 13.007 0 1 1 5.458-6.529 2.149 2.149 0 0 1 -4.158-.759v-10.856a1 1 0 0 0 -2 0v1.726a8 8 0 1 0 .2 10.325 4.135 4.135 0 0 0 7.83.274 15.2 15.2 0 0 0 .823-7.455zm-14.853 8.13a6 6 0 1 1 6-6 6.006 6.006 0 0 1 -6 6z">
                    </path>
                </svg>
                <input type="text" name="email" class="input" placeholder="Enter your Email" />

            </div>
            @error("email")
                <span class="text-danger">{{$message}}</span>
            @enderror

            <div class="flex-column">
                <label>Password</label>
            </div>

            <div class="inputForm">
                <svg height="20" width="20" xmlns="http://www.w3.org/2000/svg" viewBox="-64 0 512 512">
                    <path
                        d="m336 512h-288c-26.453125 0-48-21.523438-48-48v-224c0-26.476562 21.546875-48 48-48h288c26.453125 0 48 21.523438 48 48v224c0 26.476562-21.546875 48-48 48zm-288-288c-8.8125 0-16 7.167969-16 16v224c0 8.832031 7.1875 16 16 16h288c8.8125 0 16-7.167969 16-16v-224c0-8.832031-7.1875-16-16-16zm0 0">
                    </path>
                    <path
                        d="m304 224c-8.832031 0-16-7.167969-16-16v-80c0-52.929688-43.070312-96-96-96s-96 43.070312-96 96v80c0 8.832031-7.167969 16-16 16s-16-7.167969-16-16v-80c0-70.59375 57.40625-128 128-128s128 57.40625 128 128v80c0 8.832031-7.167969 16-16 16zm0 0">
                    </path>
                </svg>
                <input type="password" name="password" class="input" placeholder="Enter your Password" />

            </div>
            @error('password')
                <span class="text-danger"> {{$message}}</span>
            @enderror



            <button class="button-submit">Sign In</button>
            <a href="{{route('admin.signup')}}">
                <p class="p">Don't  have an account? <span class="span">Sign Up</span></p>
            </a>
            <p class="p">Or With</p>

            <div class="flex-row">
                <button class="btn google">
                    <svg width="20" height="20" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
                        <path fill="#FBBB00"
                            d="M113.47,309.408L95.648,375.94l-65.139,1.378C11.042,341.211,0,299.9,0,256c0-42.451,10.324-82.483,28.624-117.732h.014l57.992,10.632 25.404,57.644c-5.317,15.501-8.215,32.141-8.215,49.456C103.821,274.792,107.225,292.797,113.47,309.408z" />
                    </svg>
                    Google
                </button>

                <button class="btn apple">
                    <svg height="20" width="20" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 22.773 22.773">
                        <path
                            d="M15.769,0c0.053,0,0.106,0,0.162,0c0.13,1.606-0.483,2.806-1.228,3.675c-0.731,0.863-1.732,1.7-3.351,1.573 c-0.108-1.583,0.506-2.694,1.25-3.561C13.292,0.879,14.557,0.16,15.769,0z" />
                        <path
                            d="M20.67,16.716c0,0.016,0,0.03,0,0.045c-0.455,1.378-1.104,2.559-1.896,3.655c-0.723,0.995-1.609,2.334-3.191,2.334 c-1.367,0-2.275-0.879-3.676-0.903c-1.482-0.024-2.297,0.735-3.652,0.926c-0.155,0-0.31,0-0.462,0 c-0.995-0.144-1.798-0.932-2.383-1.642c-1.725-2.098-3.058-4.808-3.306-8.276c0-0.34,0-0.679,0-1.019 c0.105-2.482,1.311-4.5,2.914-5.478c0.846-0.52,2.009-0.963,3.304-0.765c0.555,0.086,1.122,0.276,1.619,0.464 c0.471,0.181,1.06,0.502,1.618,0.485c0.378-0.011,0.754-0.208,1.135-0.347c1.116-0.403,2.21-0.865,3.652-0.648 c1.733,0.262,2.963,1.032,3.723,2.22c-1.466,0.933-2.625,2.339-2.427,4.74C17.818,14.688,19.086,15.964,20.67,16.716z" />
                    </svg>
                    Apple
                </button>
            </div>
        </form>
    </div>

</body>

</html>