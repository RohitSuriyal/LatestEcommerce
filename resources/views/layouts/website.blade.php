<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />

    <!-- Styles / Scripts -->


    <link href="{{ asset('vendor/fontawesome-free/css/all.min.css') }}" rel="stylesheet" type="text/css">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link href="{{ asset('css/sb-admin-2.min.css') }}" rel="stylesheet" />
    <link href="https://unpkg.com/filepond@^4/dist/filepond.css" rel="stylesheet" />

    {{-- this is the link href --}}

    <style>
        body {
            background-color: #fff5e6;
        }

        h1 {
            color: black;
        }

        p {
            color: grey;
        }

        h2,
        h3 {
            color: black;
        }

        .form-control:focus {
            box-shadow: none !important;
        }

        td {
            color: black;
            font-weight: 500;
        }

        label {
            color: black;
            font-weight: bold;
        }

        .form_width {
            width: 93% !important;
        }

        h4 {
            font-size: 17px;
            color: black !important;
            font-family: "Roboto", sans-serif;

        }

        h5 {
            font-weight: 500;
            color: black !important;
            font-family: "Roboto", sans-serif;

        }

        h6 {
            font-weight: 500;
            color: black !important;
            font-family: "Roboto", sans-serif;
        }

        h1 {
            font-weight: 100;
            font-family: "Roboto", sans-serif;

        }

        h3 {
            font-weight: 600;
        }

        li {
            color: black;
            font-size: 14px;
            font-weight: 400;
        }

        a {
            color: black !important;
        }

        p {
            color: black;
        }


        @import url('https://fonts.googleapis.com/css2?family=Roboto+Slab:wght@100..900&family=Roboto:ital,wght@0,100..900;1,100..900&display=swap');

        .roboto {
            font-family: "Roboto", sans-serif;
            font-optical-sizing: auto;
            font-weight: 600;
            font-style: normal;
            font-variation-settings:
                "wdth" 100;
        }
    </style>
    @stack('styles')
</head>

<body class="">

    <div class="page-container" style="display:flex; flex-direction:column;min-height:100vh">
        <x-frontend.header />

        <main style="flex:1;">
            @yield('content')
        </main>

        <x-frontend.footer />
    </div>

    <link rel="stylesheet" href="https://cdn.datatables.net/2.3.4/css/dataTables.bootstrap4.min.css">
    <!-- DataTables JS -->

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"
        integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js"
        integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js"
        integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous">
    </script>
    <script>
        window.csrftoken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        function successalert(data) {

            swal.fire({
                toast: true,
                position: "top-end",
                icon: "success",
                title: data.message,
                timer: 3000,
                timerProgressBar: true,
                showConfirmButton: false, // Show a progress bar



            });
        }

        function failurealert(data) {
            swal.fire({
                toast: true,
                position: "top-end",
                icon: "error",
                title: data.message,
                timer: 3000,
                timerProgressBar: true,
                showConfirmButton: false, // Show a progress bar



            });



        }
    </script>
    @stack('scripts')


</body>

</html>
