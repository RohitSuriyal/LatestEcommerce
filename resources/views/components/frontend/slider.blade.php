@props([
    'images' => [],
    'name' => '',
    'items' => [],
    'wishlist'=>[],
])

@push('styles')
    <style>
        .slider_container {
            display: flex;
            overflow-x: auto;
            scroll-behavior: smooth;
            gap: 24px;
            padding: 20px 10px;
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.05);
        }

        .card {
            flex: 0 0 auto;
            width: 200px;
            border: none;
            border-radius: 12px;
            background: #fff;
            box-shadow: 0 3px 8px rgba(0, 0, 0, 0.08);
            transition: all 0.3s ease;
        }

        .card:hover {
            transform: translateY(-6px);
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.12);
        }

        .card img {
            width: 100%;
            height: 180px;
            object-fit: cover;
            border-radius: 12px 12px 0 0;
            transition: transform 0.3s ease;
        }

        .card:hover img {
            transform: scale(1.05);
        }

        .card-body {
            padding: 0.75rem 1rem 1rem;
        }

        .card h4 {
            font-size: 1rem;
            font-weight: 600;
            color: #222;
            margin-bottom: 6px;
        }

        .card h5 {
            font-size: 0.95rem;
            font-weight: 500;
            color: #f28b00;
            margin: 0;
        }

        .backbutton,
        .nextbutton {
            position: absolute;
            top: 45%;
            transform: translateY(-50%);
            z-index: 10;
            box-shadow: 0 3px 6px rgba(0, 0, 0, 0.2);
            transition: all 0.3s ease;
        }

        .carousel_button {
            background-color: #f28b00;
            color: white;
            border-radius: 50%;
            width: 45px;
            height: 45px;
            display: flex;
            align-items: center;
            justify-content: center;
            border: none;
            font-size: 1.3rem;
            font-weight: bold;
        }

        .carousel_button:hover {
            background-color: #d97706;
            transform: scale(1.1);
        }

        .backbutton {
            left: 1%;
        }

        .nextbutton {
            right: 1%;
        }

        /* Hide scrollbar */
        .slider_container::-webkit-scrollbar {
            display: none;
        }

        .slider_container {
            cursor: grab;
        }

        h3.roboto {
            font-weight: 600;
            color: #333;
            margin-top: 1.5rem;
            margin-bottom: 1rem;
            font-family: 'Roboto', sans-serif;
        }
    </style>
@endpush

@if ($name === 'topdeals')
    <h3 class="roboto mx-3 my-3">🔥 Top Deals for You</h3>
@elseif ($name === 'topproducts')
    <h3 class="roboto mx-3 my-3">⭐ Top Products for You</h3>
@elseif ($name === 'topappliances')
    <h3 class="roboto mx-3 my-3">⚡ Top Appliances for You</h3>
@endif

<div data-aos="fade-up" class="position-relative container-fluid px-4">
    <div class="slider_container">
        @foreach ($items as $item)
            <a class="text-decoration-none text-dark" href="{{ route('frontend.allproducts', $item->id) }}">
                <x-frontend.wishlist id="{{ $item->id }}" :wishlist="$wishlist" />
                
                <div class="card">
                    <img src="{{ asset('storage/' . $item->main_image) }}" alt="Product Image">
                    <div class="card-body">
                        <h4>{{ Str::limit($item->name, 20) }}</h4>
                        <h5>₹{{ number_format($item->price, 2) }}</h5>
                    </div>
                </div>
            </a>
        @endforeach
    </div>

    @if (count($items) > 7)
        <button class="btn carousel_button backbutton">&lt;</button>
        <button class="btn carousel_button nextbutton">&gt;</button>
    @endif


</div>

@push('scripts')
    <script>
        document.addEventListener('click', function(e) {
            if (e.target.classList.contains('nextbutton')) {
                const parent = e.target.closest('.position-relative');
                const slider = parent.querySelector('.slider_container');
                slider.scrollBy({
                    left: 300,
                    behavior: "smooth"
                });
            }

            if (e.target.classList.contains('backbutton')) {
                const parent = e.target.closest('.position-relative');
                const slider = parent.querySelector('.slider_container');
                slider.scrollBy({
                    left: -300,
                    behavior: "smooth"
                });
            }
        });

        document.querySelectorAll('.position-relative').forEach(function(wrapper) {
            const slider = wrapper.querySelector('.slider_container');
            let scrollAmount = 100;
            const scrollStep = 100;
            const intervalTime = 2000;
            let direction = 1;
            let intervalId;
            let isHovering = false;

            function startAutoScroll() {
                intervalId = setInterval(() => {
                    if (isHovering) return;

                    scrollAmount += scrollStep * direction;

                    if (scrollAmount >= slider.scrollWidth - slider.clientWidth) {
                        direction = -1;
                        scrollAmount = slider.scrollWidth - slider.clientWidth;
                    } else if (scrollAmount <= 0) {
                        direction = 1;
                        scrollAmount = 0;
                    }

                    slider.scrollTo({
                        left: scrollAmount,
                        behavior: "smooth"
                    });
                }, intervalTime);
            }

            slider.addEventListener('mouseenter', () => {
                isHovering = true;
            });
            slider.addEventListener('mouseleave', () => {
                isHovering = false;
            });

            startAutoScroll();
        });
    </script>


    
@endpush
