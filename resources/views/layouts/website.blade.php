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
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">

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
    @push('styles')
        <style>
            .serial_no {
                padding: 0% 1%;
                background-color: rgb(248, 224, 224);
                border-radius: 13px !important;
                margin: 0% 1%;
                color: blue;

            }

            .continue {
                background-color: #ff9f00;
                padding: 1% 7%;
                color: white;
                font-weight: 600;
            }
        </style>
    @endpush
</head>

<body class="">

    <div class="page-container" style="display:flex; flex-direction:column;min-height:100vh">
        <x-frontend.header />

        <main style="flex:1;">
            @yield('content')
        </main>

        <x-frontend.footer />
    </div>
    <div class="modal_content">
        <x-frontend.cartmodal />

    </div>
    <div class="wishlist_modal_content">
        <x-frontend.wishlistmodal />
    </div>


    @if (session('success') || session('failure') || session('otpfailure'))
        <script>
            const data = {
                status: "{{ session('success') ? 'success' : (session('failure') ? 'failure' : 'otpfailure') }}",
                message: "{{ session('success') ?? (session('failure') ?? session('otpfailure')) }}"
            };

            Swal.fire({
                icon: data.status === 'success' ? 'success' : 'error',
                title: data.status === 'success' ? 'Success' : 'Error',
                text: data.message,
                timer: 4000,
                showConfirmButton: false
            });
        </script>
    @endif

    <link rel="stylesheet" href="https://cdn.datatables.net/2.3.4/css/dataTables.bootstrap4.min.css">
    <!-- DataTables JS -->
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>

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
        AOS.init();
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
    <script>
        document.addEventListener("click", async function(e) {
            // Check if the clicked element or any of its parents has the class "add_to_cart"
            const addToCartBtn = e.target.closest(".add_to_cart");

            if (addToCartBtn) {

                const id = addToCartBtn.getAttribute("id");

                try {
                    const res = await fetch("{{ route('frontend.cartdata') }}", {
                        method: "POST",
                        headers: {
                            "Content-Type": "application/json",
                            "X-CSRF-TOKEN": csrftoken,
                        },
                        body: JSON.stringify({
                            id: id
                        }),
                    });

                    if (!res.ok) {
                        const errorText = await res.text();
                        console.error("Server Error:", errorText);
                        alert(`Error ${res.status}: ${res.statusText}`);
                        return;
                    }

                    const data = await res.json();
                    console.log(data);
                    if (data.status === "cart_loaded") 
                    {
                        const modalContent = document.querySelector(".modal_content");
                        
                        if (modalContent) 
                        {
                            modalContent.innerHTML = data.html;
                             $('.modal.show').modal('hide');
                            setTimeout(() => { 

                                $('#exampleModal').modal('show');

                            }, 500);


                        }
                    } else if (data.status === "added") {
                        $("#exampleModalwishlist").modal('hide');

                        successalert(data);

                    } else if (data.status === "failure") {
                        failurealert(data);
                    } else {
                        console.warn("Unexpected response:", data);
                        alert("Unexpected response from server.");
                    }

                } catch (error) {
                    console.error("Fetch failed:", error);
                    alert("Network or fetch error: " + error.message);
                }
            }
        });
    </script>

    @stack('scripts')


</body>

</html>
