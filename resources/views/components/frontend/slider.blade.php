@props([
    "images" => [],
    "name" => "",
    "items" => [],
])

@push("styles")
<style>
    .slider_container {
        display: flex;
        overflow-x: auto;
        scroll-behavior: smooth;
        gap: 30px;
        padding: 10px;
        background: white;
    }

    .card {
        flex: 0 0 auto;
        width: 18rem; /* fixed width for slider */
        display: flex;
        flex-direction: column;
        border-radius: 25px;
    }

    .card img {
        width: 100%;
        height: 200px; /* allow image to keep aspect ratio */
        object-fit: cover;
        border-top-left-radius: .25rem;
        border-top-right-radius: .25rem;
    }

    .card-body {
        padding: 0.5rem 1rem;
    }

    .backbutton, .nextbutton {
        position: absolute;
        top: 50%;
        transform: translateY(-50%);
        z-index: 10;
    }

    .backbutton { left: 3%; }
    .nextbutton { right: 3%; }

    .carousel_button {
        background-color: #f28b00;
        color: white;
        border-radius: 50%;
        width: 40px;
        height: 40px;
        display: flex;
        align-items: center;
        justify-content: center;
        border: none;
    }

    /* Optional: Hide scrollbar */
    .slider_container::-webkit-scrollbar {
        display: none;
    }

    .card h5 {
        margin: 0.25rem 0; /* reduce spacing for compact cards */
    }
    .slider_container{
        cursor: pointer;
    }

</style>
@endpush

@if($name == "topdeals")
    <h3 class="roboto mx-3 my-3">Top Deals for You</h3>
@endif
@if($name == "topproducts")
    <h3 class="roboto mx-3 my-3">Top Products for you</h3>
@endif
<div class="position-relative">
    <div  class="slider_container container-fluid">
        @foreach ($items as $item)
        <a class="h-100 text-decoration-none" href="{{ route('frontend.allproducts',$item->id) }}">
            <div class="card">
                <img src="{{ asset('storage/' . $item->main_image) }}" class="card-img-top" alt="Product Image">
                <div class="card-body">
                    <h4>{{Str::limit($item->name,20) }}</h4>
                    <h5>₹{{ $item->price }}</h5>
                </div>
            </div>
        </a>
            
        @endforeach
    </div>

    <button class="btn carousel_button backbutton">&lt;</button>
    <button class="btn carousel_button nextbutton">&gt;</button>
</div>

@push("scripts")

<script>
    document.addEventListener('click', function (e) {

        
      

        if (e.target.classList.contains('nextbutton')) {

            const parent = e.target.closest('.position-relative');
            const slider=parent.querySelector('.slider_container');

            slider.scrollBy({
                left: 300,
                behavior: "smooth"
            });
        }

        if (e.target.classList.contains('backbutton')) {
             const parent = e.target.closest('.position-relative');
            const slider=parent.querySelector('.slider_container');
            slider.scrollBy({
                left: -300,
                behavior: "smooth"
            });
        }
    });


 document.querySelectorAll('.position-relative').forEach(function(wrapper) {
    const slider = wrapper.querySelector('.slider_container');
    let scrollAmount = 100;
    const scrollStep = 100; // pixels per interval
    const intervalTime = 2000; // ms
    let direction = 1; // 1 = scroll right, -1 = scroll left
    let intervalId;
    let isHovering = false;

    function startAutoScroll() {
        intervalId = setInterval(() => {
            if (isHovering) return; // Skip scrolling if hovering

            // Update scroll position
            scrollAmount += scrollStep * direction;

            // Check boundaries and reverse direction
            if (scrollAmount >= slider.scrollWidth - slider.clientWidth) {
                direction = -1; // start scrolling left
                scrollAmount = slider.scrollWidth - slider.clientWidth; // ensure it doesn't exceed
            } else if (scrollAmount <= 0) {
                direction = 1; // start scrolling right
                scrollAmount = 0; // ensure it doesn't go negative
            }

            slider.scrollTo({ left: scrollAmount, behavior: "smooth" });
        }, intervalTime);
    }

    // Stop auto-scroll on hover
    slider.addEventListener('mouseenter', () => {
        isHovering = true;
    });

    // Resume auto-scroll when mouse leaves
    slider.addEventListener('mouseleave', () => {
        isHovering = false;
    });

    // Start the auto-scroll
    startAutoScroll();
});
</script>

@endpush
