@extends('layouts.app')

@section('content')
    <div class="container py-4">
        <div class="row g-4">

            <!-- Total Products Card -->
            <div class="col-md-4">
                <div class="card border-0 shadow-sm rounded-4 text-white p-4"
                    style="background: linear-gradient(135deg, #4385a0, #3773b1);">
                    <div class="card-body">
                        <h4 class="fw-bold text-uppercase mb-2">Total Products</h4>
                        <h1 class="display-5 fw-bold mb-0 text-white">
                            {{ Auth::guard('admin')->user()->products()->count() }}
                        </h1>
                    </div>
                </div>
            </div>

            <!-- Total Brands Card -->
            <div class="col-md-4">
                <div class="card border-0 shadow-sm rounded-4 text-white p-4"
                    style="background: linear-gradient(135deg, #4385a0, #3773b1);">
                    <div class="card-body">
                        <h4 class="fw-bold text-uppercase mb-2">Total Brands</h4>
                        <h1 class="display-5 fw-bold mb-0 text-white">
                            {{ $brandcount }}
                        </h1>
                    </div>
                </div>
            </div>

            <!-- Total Orders Card (optional) -->
            <div class="col-md-4">
                <div class="card border-0 shadow-sm rounded-4 text-white p-4"
                    style="background: linear-gradient(135deg, #4385a0, #3773b1);">
                    <div class="card-body">
                        <h4 class="fw-bold text-uppercase mb-2">Total Orders</h4>
                        <h1 class="display-5 fw-bold mb-0 text-white">
                            {{ $ordercount ?? 0 }}
                        </h1>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <style>
        .card {
            transition: all 0.3s ease;
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
        }
    </style>
@endsection
