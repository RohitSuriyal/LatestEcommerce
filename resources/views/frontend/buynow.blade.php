@extends('layouts.website')
@section('content')
    @php

        $authuser = Auth::guard('web')->user();
    @endphp

    @push('styles')
        <style>
            /* Container inline with price */
            .flipkart-quantity {
                display: flex;
                align-items: center;
                border: 1px solid #ddd;
                border-radius: 4px;
                overflow: hidden;
                width: 100px;
                /* compact width */
                background-color: #fff;
                font-family: sans-serif;
            }

            .quantity_btn {
                padding: 1% 3%;
                border-radius: 19px !important;
                color: white;
                background-color: orange;
                outline: none;
                border: none;
            }

            .continue {
                background-color: orange;
                color: white;
            }

            .quantity_btn {
                padding: 5px 16px;
                border-radius: 30px !important;
                color: white;
                background-color: orange;
                outline: none;
                border: none;
            }

            .serial_no {
                padding: 0px 7px;
                background-color: rgb(67, 146, 236);
                border-radius: 13px;
                margin-right: 2px;
            }
        </style>
    @endpush
    <div class="container-fluid">

        <div class="row mx-0 my-3">
            <div class="col-md-7">
                <div id="accordion" class="">
                    <!-- LOGIN -->
                    <div class="card">
                        <div class="card-header" id="headingOne">
                            <h5 class="mb-0">
                                <button class="btn btn-primary w-100 d-flex justify-content-start loginbutton"
                                    data-toggle="collapse" data-target="#collapseOne" aria-expanded="true"
                                    aria-controls="collapseOne">
                                    <span class="serial_no">1</span>
                                    Login
                                </button>
                            </h5>
                        </div>



                        <div id="collapseOne" class="collapse " aria-labelledby="headingOne" data-parent="#accordion">
                            <div class="card-body">
                                <form class="loginuserform">
                                    <input value="{{ $authuser ? $authuser->email : old('email') }}"
                                        class="form-control verificationinput"
                                        placeholder="Enter Mobile No / Or Enter Email" />
                                    <hr>
                                    <input type="text" placeholder="Enter the otp" name="otpinput"
                                        class="form-control otpinput d-none" />

                                    <button class="btn btn-success my-2" type="submit">
                                        @if (Auth::guard('web')->check())
                                            Update
                                        @else
                                            Submit
                                        @endif
                                    </button>
                                </form>

                            </div>
                        </div>
                    </div>

                    <!-- ADDRESS -->
                    <div class="card">
                        <div class="card-header" id="headingTwo">
                            <h5 class="mb-0">
                                <button {{ Auth::guard('web')->check() ? '' : 'disabled' }}
                                    class="btn btn-primary w-100 d-flex justify-content-start address_button"
                                    data-toggle="collapse" data-target="#collapseTwo" aria-expanded="false"
                                    aria-controls="collapseTwo">
                                    <span class="serial_no">2</span> ADDRESS
                                </button>
                            </h5>
                        </div>
                        @if (session('success'))
                            <div class="alert alert-success">
                                {{ session('success') }}
                            </div>
                        @endif
                        @if (session('error'))
                            <div class="alert alert-success">
                                {{ session('error') }}
                            </div>
                        @endif


                        <div id="collapseTwo" class="collapse " aria-labelledby="headingTwo" data-parent="#accordion">
                            <div class="card-body">
                                <form method="post" class="p-4 border rounded shadow-sm bg-white user_detail_form"
                                    action="{{ route('frontend.userdetailsubmit') }}" id="addressForm">

                                    @csrf
                                    <input type="hidden" name="product_id" type="text" value="{{ $product->id }}" />
                                    <h5 class="mb-4 fw-bold">Shipping Address</h5>

                                    <div class="row g-3">
                                        <div class="col-md-6">
                                            <label for="full_name" class="form-label">Full Name</label>
                                            <input value="{{ $authuser ? $authuser->name : old('name') }}" type="text"
                                                class="form-control" id="full_name" name="name" placeholder="John Doe"
                                                required>
                                        </div>

                                        <div class="col-md-6">
                                            <label for="phone" class="form-label">Phone Number</label>
                                            <input value="{{ $authuser ? $authuser->phone : old('phone') }}" type="tel"
                                                class="form-control" id="phone" name="phone" placeholder="9876543210"
                                                required>
                                        </div>

                                        <div class="col-12">
                                            <label for="address_line" class="form-label">Address Line</label>
                                            <textarea class="form-control" id="address_line" name="address" rows="2"
                                                placeholder="House number, Street, Locality" required>{{ old('address', Auth::user()->address ?? '') }}</textarea>

                                        </div>

                                        <div class="col-md-6">
                                            <label for="city" class="form-label">City</label>
                                            <input value="{{ $authuser ? $authuser->city : old('city') }}" type="text"
                                                class="form-control" id="city" name="city" placeholder="New Delhi"
                                                required>
                                        </div>

                                        <div class="col-md-6">
                                            <label for="state" class="form-label">State</label>
                                            <input value="{{ $authuser ? $authuser->state : old('state') }}" type="text"
                                                class="form-control" id="state" name="state" placeholder="Delhi"
                                                required>
                                        </div>

                                        <div class="col-md-6">
                                            <label for="pincode" class="form-label">Pincode</label>
                                            <input value="{{ $authuser ? $authuser->pincode : old('pincode') }}"
                                                type="text" class="form-control" id="pincode" name="pincode"
                                                placeholder="110001" required>
                                        </div>

                                        <div class="col-md-6">
                                            <label for="landmark" class="form-label">Landmark (Optional)</label>
                                            <input type="text"
                                                value="{{ $authuser ? $authuser->landmark : old('landmark') }}"
                                                class="form-control" id="landmark" name="landmark"
                                                placeholder="Near City Mall">
                                        </div>
                                    </div>

                                    <div class="mt-4 d-flex justify-content-between">
                                        <button type="reset" class="btn btn-outline-secondary">Clear</button>
                                        <button type="submit" class="btn btn-primary">
                                            @if (Auth::guard('web')->check())
                                                Update
                                            @else
                                                Submit
                                            @endif
                                        </button>
                                    </div>
                                </form>

                            </div>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-header" id="headingthree">
                            <h5 class="mb-0">
                                <button {{ Auth::guard('web')->check() ? '' : 'disabled' }}
                                    class="btn btn-primary w-100 d-flex justify-content-start address_button"
                                    data-toggle="collapse" data-target="#summary" aria-expanded="false"
                                    aria-controls="summary">
                                    <span class="serial_no">3</span> Summary
                                </button>
                            </h5>

                            <div id="summary" class="collapse {{ Auth()->guard('web')->check() ? 'show' : '' }}"
                                aria-labelledby="headingTwo" data-parent="#accordion">
                                <div class="card-body">
                                    <div class="row mx-0 d-flex gap-3">
                                        <div class="col-md-2">
                                            <img width="100%"
                                                src="{{ asset('storage/' . $product->main_image) }}"></img>
                                        </div>

                                        <div class="col-md-7">
                                            <h6>{{ $product->name }}</h6>
                                            <span class="px-2 my-3 py-1 text-white font-weight-bold"
                                                style="background-color: green; border-radius: 6px; font-size: 0.7rem;">
                                                {{ $product->rating }} ★
                                            </span>
                                            <div class="col-md-6 p-0 my-3">
                                                <h5 class="text-primary font-weight-bold ">
                                                    &#8377;{{ $product->sale_price }}
                                                </h5>
                                                <p class="m-0">
                                                    <s class="text-muted">&#8377;{{ $product->price }}</s>
                                                    <span
                                                        class="text-success font-weight-bold ml-2">{{ $product->discount }}%
                                                        off</span>
                                                </p>
                                            </div>

                                            <div class="quantity d-flex gap-3">
                                                <button class="decreament quantity_btn">
                                                    -
                                                </button>
                                                <input class="w-25 qty_input form-control" value="1"
                                                    type="number" />
                                                <button class="increament quantity_btn">
                                                    +
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @if (Auth::guard('web')->check())
                    <div class=" card flex-row d-flex justify-content-between align-items-center my-3 p-3 ">
                        <div> Order confirmation Details will be send to <strong style="color:black">
                                {{ Auth::guard('web')->user()->email }} </strong>
                        </div>
                        <button class="btn continue">Continue</button>
                    </div>
                @endif
            </div>

            <div class="col-md-4">
                @if (Auth::guard('web')->check())
                    <div class="card">
                        <h4 class="mx-3 my-2">Price Details</h4>
                        <div class="row mx-0 px-3 py-3">
                            <div class="col-md-12 d-flex justify-content-between">
                                <p>Price of item(1item)</p>
                                <p class="product-price">
                                    <span class="font-bold text-md">₹{{ number_format($product->sale_price, 0) }}</span>
                                </p>
                            </div>
                            <div class="col-md-12 d-flex justify-content-between">
                                <p>Protected Promise Fee</p>
                                <p class="product-price">
                                    <span class="font-bold text-md">₹<span class="promise_price">99</span></span>
                                </p>
                            </div>
                            <hr class="w-100">
                            <div class="col-md-12 d-flex justify-content-between">
                                <h4 style="font-weight:bold">Total Payable Amount</h4>
                                <p class="product-price">
                                    <span class="font-bold text-md ">₹<span class="total_price">
                                            {{ number_format($product->sale_price + 99, 0) }}
                                        </span></span>

                                </p>
                            </div>
                        </div>

                    </div>
                @endif

            </div>
        </div>
    </div>
@endsection
@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {


            document.querySelector(".loginuserform").addEventListener("submit", async function(e) {

                e.preventDefault();

                const csrf = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

                const result = await fetch("{{ route('frontend.userbuynowlogin') }}", {

                    method: "POST",
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrf,

                    },
                    body: JSON.stringify({

                        email: document.querySelector('.verificationinput').value,
                        otp: document.querySelector('.otpinput').value,

                    })
                })

                if (!result.ok) {
                    alert('Something went wrong');
                }
                const data = await result.json();
                console.log(data);

                if (data.status == "success") {

                    if (data.verified) {

                        document.querySelector(".address_button").disabled = false;
                        document.querySelector('.loginbutton').disabled = true;

                        document.querySelector('.address_button').click();
                        Swal.fire({
                            toast: true, // Enable toast style
                            position: 'top-end', // Top right corner
                            icon: 'success', // Icon type: success, error, info, warning, question
                            title: data.message,
                            showConfirmButton: false, // Hide the "OK" button
                            timer: 3000, // Auto close after 3 seconds
                            timerProgressBar: true, // Show a progress bar
                            didOpen: (toast) => {
                                toast.addEventListener('mouseenter', Swal
                                    .stopTimer) // Pause on hover
                                toast.addEventListener('mouseleave', Swal.resumeTimer)
                            }
                        });

                    } else {
                        document.querySelector('.verificationinput').value = data.user.email;
                        document.querySelector('.otpinput').classList.remove('d-none');
                        Swal.fire({
                            toast: true, // Enable toast style
                            position: 'top-end', // Top right corner
                            icon: 'success', // Icon type: success, error, info, warning, question
                            title: data.message,
                            showConfirmButton: false, // Hide the "OK" button
                            timer: 3000, // Auto close after 3 seconds
                            timerProgressBar: true, // Show a progress bar
                            didOpen: (toast) => {
                                toast.addEventListener('mouseenter', Swal
                                    .stopTimer) // Pause on hover
                                toast.addEventListener('mouseleave', Swal.resumeTimer)
                            }
                        });

                    }
                } else if (data.error) {

                    Swal.fire({
                        toast: true, // Enable toast style
                        position: 'top-end', // Top right corner
                        icon: 'success', // success | error | info | warning | question
                        title: data.message, // Your dynamic message
                        showConfirmButton: false, // Hide "OK" button
                        timer: 3000, // Auto close after 3 seconds
                        timerProgressBar: true, // Show a progress bar
                        didOpen: (toast) => {
                            toast.addEventListener('mouseenter', Swal
                                .stopTimer); // Pause timer on hover
                            toast.addEventListener('mouseleave', Swal
                                .resumeTimer); // Resume on mouse leave
                        }
                    });

                }

            })

        })
    </script>

    {{-- this is for the quantity button --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {

            async function quantity_checker(value) {

                const result = await fetch("{{ route('frontend.quantity_checker') }}", {

                    method: "Post",
                    headers: {

                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrftoken,
                    },
                    body: JSON.stringify({

                        value: document.querySelector(".qty_input").value,
                        id: @json($product->id),
                    })

                })

                if (!result.ok) {

                    swal.fire({
                        toast: true,
                        position: "top-end",
                        icon: "error",
                        title: "Something went wrong",
                        timer: 3000,
                        timerProgressBar: true,
                        showConfirmButton: false, // Show a progress bar



                    });
                }

                const data = await result.json();
                console.log(data);
                if (data.status == "success") {
                    const protected_price = parseFloat(document.querySelector(".promise_price").innerText);
                    console.log(value);
                    const totalprice = parseFloat(data.price) * value + protected_price;


                    const total_price_ele = document.querySelector('.total_price');
                    total_price_ele.innerText = totalprice;




                    successalert(data);
                }

            }


            document.addEventListener('click', function(e) {

                if (e.target.classList.contains('quantity_btn')) {

                    if (e.target.classList.contains('increament')) {

                        const qtyinput = document.querySelector('.qty_input');
                        qtyinput.value = parseFloat(qtyinput.value) + 1;
                    }
                    if (e.target.classList.contains('decreament')) {
                        const qtyinput = document.querySelector('.qty_input');
                        qtyinput.value = parseFloat(qtyinput.value) - 1;
                        if (qtyinput.value < 0) {
                            qtyinput.value = 0;
                        }

                    }

                    const value = document.querySelector('.qty_input').value;


                    quantity_checker(value);
                }
            })

        })
        document.addEventListener("click", async function(e) {

            const continuebutton = e.target.closest('.continue');

            if (continuebutton) {

                try {
                    const amount_text = document.querySelector(".total_price").innerText;
                    const amount=amount_text.replaceAll(",",'');



                    const res = await fetch("{{ route('frontend.checkoutsession') }}", {

                        method: "post",

                        headers: {
                            "Content-Type": "application/json",
                            "X-CSRF-TOKEN": csrftoken,
                        },
                        body: JSON.stringify({
                            amount: amount,
                        })

                    });
                    const data = await res.json();


                    window.open(data.url, '_blank');


                } catch (error) {

                    Swal.fire('Error', error.message, 'error');
                }

            }



        })
    </script>
@endpush
