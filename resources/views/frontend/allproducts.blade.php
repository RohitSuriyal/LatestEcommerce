@extends('layouts.website')

@push('styles')
    <style>
        .box_shadow {
            box-shadow: 0.3px 0.4px 1px rgba(0, 0, 0, 0.5);
        }

        ul.m-0 li {
            margin-left: 0.8rem;
            /* adjust bullet indent */
        }
    </style>
@endpush

@section('content')
    <div id="product-container">
        <x-frontend.allproductsnew :products="$products" id="$id" :categories="$categories" :brands="$brands" />
    </div>
@endsection

@push('scripts')
    <script>
        function initCustomDropdowns() {
            const dropdowns = document.querySelectorAll(".custom-dropdown");
            const category_checkboxes = document.querySelectorAll(".category-checkbox");
            const brand_category = document.querySelectorAll(".brand-checkbox");

            category_checkboxes.forEach(function(item) {
                if (item.checked) {

                    item.closest(".custom-dropdown").classList.add('open');

                }
            });


            brand_category.forEach(function(item) {

                if (item.checked) {
                    console.log("inside this");
                    item.closest(".custom-dropdown").classList.add('open')

                }

            });

            dropdowns.forEach(dropdown => {
                const button = dropdown.querySelector("button");
                const content = dropdown.querySelector(".dropdown-content");

                button.addEventListener("click", (e) => {
                    const parent = e.target.closest(".dropdown-content");


                    // If NOT clicked inside dropdown-content → toggle this dropdown only
                    if (!parent) {
                        dropdowns.forEach(d => {
                            if (d !== dropdown) d.classList.remove("open");
                        });
                        dropdown.classList.toggle("open");
                    }
                });
            });
        }
        initCustomDropdowns();
    </script>

    <script>
        const id = @json($id ?? '');
        const csrfToken = document.querySelector("meta[name='csrf-token']").getAttribute('content');
        // Delegate click for pagination links

        document.addEventListener("click", e => {
            const link = e.target.closest(".pagination a");
            if (!link) return;
            e.preventDefault();
            const url = new URL(link.href);
            const page = url.searchParams.get("page") || 1;
            performSearch(page);
        });

        async function performSearch(page = 1) 
        {
            const categories = Array.from(document.querySelectorAll(".category-checkbox"))
                .filter(item => item.checked)
                .map(e => e.value);

            const brands = Array.from(document.querySelectorAll(".brand-checkbox")).filter(item => item.checked).map(
                e => e.value);

            try {
                const res = await fetch(`{{ route('frontend.paginateproducts') }}?page=${page}`, {
                    method: "POST",

                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": csrfToken
                    },
                    body: JSON.stringify({
                        id: id,
                        brands: brands,
                        categories: categories,
                    });
                })

                const data = await res.json();
                console.log(data);
                if (data.html) {

                    document.getElementById('product-container').innerHTML = data.html;
                    initCustomDropdowns();
                    if (window.AOS) 
                    {

                        setTimeout(() => AOS.refresh(), 50); // 50ms is usually enough
                    }

                    // Scroll to top of product list
                    window.scrollTo({
                        top: document.getElementById('product-container').offsetTop - 100,
                        behavior: 'smooth'
                    });
                }
            } catch (err) {

                console.error("Search error:", err);

            }
        }
    </script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            // Event delegation for category and brand checkboxes
            document.body.addEventListener("change", async function(e) {
                if (!e.target.matches(".brand-checkbox, .category-checkbox")) return;

                // Collect selected categories
                const categories = document.querySelectorAll('.category-checkbox');
                const selectedCategories = Array.from(categories)
                    .filter(c => c.checked)
                    .map(c => parseFloat(c.value));

                // Collect selected brands
                const brands = document.querySelectorAll('.brand-checkbox');
                const selectedBrands = Array.from(brands)
                    .filter(c => c.checked)
                    .map(c => parseFloat(c.value));

                const csrftoken = document.querySelector('meta[name="csrf-token"]').getAttribute(
                    'content');

                try {

                    const result = await fetch("{{ route('frontend.paginateproducts') }}", {
                        method: "POST",
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': csrftoken,
                        },
                        body: JSON.stringify({
                            categories: selectedCategories,
                            brands: selectedBrands,
                            filter: "filter_result"
                        })
                    });

                    if (!result.ok) {

                        const errorData = await result.json().catch(() => null);
                        const errorMessage = errorData?.message || 'Something went wrong!';
                        alert(errorMessage);
                        return;
                    }

                    const data = await result.json();
                   
                    if (data.html) 
                    {
                        
                        AOS.init();
                        document.getElementById('product-container').innerHTML = data.html;

                        // Re-initialize custom dropdowns if you have any
                        initCustomDropdowns();
                        // Scroll to top of product list
                        window.scrollTo({

                            top: document.getElementById('product-container').offsetTop - 100,
                            behavior: 'smooth'
                        });
                    }
                } catch (err) {
                    console.error(err);
                    alert("Network error: " + err.message);
                }
            });

        });
    </script>
@endpush
