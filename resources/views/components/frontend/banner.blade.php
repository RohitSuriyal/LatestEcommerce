@props([
    'banners' => [],
])
@push('styles')
    <style>
        .carousel_buttton {
            background-color: #ff9100;
            padding: 10%;
            border-radius: 30px;
        }
    </style>
@endpush

<div id="carouselExampleControls" class="carousel slide" data-ride="carousel" data-interval="2000">
    <div class="carousel-inner">
        @foreach ($banners as $key => $banner)
            <div style="height:70vh" class="carousel-item {{ $key == 0 ? 'active' : '' }}">
                <img style="object-fit: cover" height="100% " class="d-block w-100"
                    src="{{ asset('storage/' . $banner->image) }}" alt="First slide">
            </div>
        @endforeach
    </div>

</div>
