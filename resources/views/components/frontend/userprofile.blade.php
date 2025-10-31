@extends('layouts.website')

@section('content')
    @push('styles')
        <style>
            body {
                background-color: #f5f6fa;
                font-family: 'Roboto', sans-serif;
            }

            .profile-card {
                max-width: 850px;
                margin: 50px auto;
                background: #fff;
                border-radius: 20px;
                box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
                overflow: hidden;
            }

            .profile-header {
                background: rgb(249, 188, 73);
                color: #fff;
                text-align: center;
                padding: 40px 20px;
                border-top-left-radius: 20px;
                border-top-right-radius: 20px;
            }

            .profile-img {
                width: 110px;
                height: 110px;
                border-radius: 50%;
                border: 3px solid #fff;
                object-fit: cover;
                background-color: #f8f9fa;
                margin-bottom: 15px;
            }

            .profile-body {
                padding: 40px 30px;
            }

            .profile-section h5 {
                font-weight: bold;
                color: #333;
            }

            .profile-section label {
                font-weight: 600;
                color: #007bff;
            }

            .edit-btn {
                border-radius: 30px;
                padding: 8px 20px;
            }

            input.form-control {
                border: 1px solid #ccc;
                border-radius: 8px;
                padding: 6px 10px;
            }
        </style>
    @endpush

    @php
        $user = Auth::guard('web')->user();
    @endphp

    <div class="profile-card">
        <div class="profile-header">
            <img id="profileImage"
                src="{{ $user->image && file_exists(public_path('storage/' . $user->image))
                    ? asset('storage/' . $user->image)
                    : 'https://ui-avatars.com/api/?name=' . urlencode($user->name ?? 'User') . '&size=110&background=random' }}"
                onerror="this.onerror=null; this.src='https://ui-avatars.com/api/?name={{ urlencode($user->name ?? 'User') }}&size=110&background=random'"
                class="profile-img" alt="User Image" style="cursor:pointer">

            <input type="file" id="profileInput" style="display:none;" accept="image/*">

            <h4 class="mb-1">{{ $user->name ?? 'User Name' }}</h4>
            <p class="mb-0">{{ $user->email ?? 'user@example.com' }}</p>
        </div>

        <div class="profile-body">
            <form id="profileForm" action="{{ route('frontend.userprofilesubmit') }}">
                @csrf
                <input type="hidden" name="id" value="{{ $user->id }}" />
                <div class="row">
                    <div class="col-md-6 profile-section mb-4 mb-md-0">
                        <h5>Personal Info</h5>

                        <div class="form-group">
                            <label>Name:</label>
                            <input type="text" name="name" class="form-control" value="{{ $user->name ?? '' }}">
                        </div>

                        <div class="form-group">
                            <label>Email:</label>
                            <input type="email" name="email" class="form-control" value="{{ $user->email ?? '' }}">
                        </div>

                        <div class="form-group">
                            <label>Mobile:</label>
                            <input type="text" name="phone" class="form-control" value="{{ $user->phone ?? '' }}">
                        </div>
                    </div>

                    <div class="col-md-6 profile-section">
                        <h5>Address</h5>

                        <div class="form-group">
                            <label>Street:</label>
                            <input type="text" name="address" class="form-control" value="{{ $user->address ?? '' }}">
                        </div>

                        <div class="form-group">
                            <label>City:</label>
                            <input type="text" name="city" class="form-control" value="{{ $user->city ?? '' }}">
                        </div>

                        <div class="form-group">
                            <label>State:</label>
                            <input type="text" name="state" class="form-control" value="{{ $user->state ?? '' }}">
                        </div>
                    </div>
                </div>

                <div class="text-right mt-4">
                    <button type="submit" class="btn btn-success edit-btn">
                        <i class="fas fa-save mr-2"></i> Save Profile
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://kit.fontawesome.com/a076d05399.js"></script>
@endsection
