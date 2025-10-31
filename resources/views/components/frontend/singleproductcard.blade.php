@props([
    'product' => '',
])

@push('styles')
    <style>
        .demo_images {
            height: 90px;
            width: 90px;
            object-fit: cover;
            cursor: pointer;
            margin-bottom: 5px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }

        .main_image {
            height: 300px;
            width: 100%;
            object-fit: cover;
            border: 1px solid #ddd;
            border-radius: 5px;
        }

        .image_border {
            border-radius: 13px;
        }

        .add_to_cart {
          
            padding: 2% 0%;
            color: white;
            font-weight: 600;
        }

    </style>

    </style>
@endpush

<div class="row mx-0 p-3 ">
    <!-- Thumbnails -->
    <div data-aos="fade-right" class="col-md-1 d-flex flex-column">
        @foreach (json_decode($product->product_images) as $image)
            <img src="{{ asset('storage/' . $image) }}" class="demo_images image_border"
                onclick="document.getElementById('main_image_{{ $product->id }}').src=this.src">
        @endforeach
    </div>

    <div data-aos="fade-right" class="col-md-4 h-100">
        <img width=100% style="height:410px;object-fit:cover" id="main_image_{{ $product->id }}"
            src="{{ asset('storage/' . json_decode($product->product_images)[0]) }}" class="main_image">

        <div class="my-5 d-flex gap-3 my-2">
            <x-frontend.addtocartbutton id="{{ $product->id }}"/>
            <x-frontend.buynowbutton classname="add_to_cart" id="{{ $product->id }}" />
        </div>
    </div>w

    <div data-aos="fade-left" style="height:493px" class="col-md-6 bg-white ">
        <h5 class="my-3" style="color:white">{{ $product->name }}</h5>
        <span class="px-2 my-3 py-1 text-white font-weight-bold"
            style="background-color: green; border-radius: 6px; font-size: 0.7rem;">
            {{ $product->rating }} ★
        </span>
        <h5 style="font-weight:bold" class="mt-2">Price: ₹{{ $product->sale_price ?? '' }}</h5>
        <ul>
            @foreach ($product->description as $description)
                <li>
                    {{ $description }}
                </li>
            @endforeach
        </ul>
        
    </div>
</div>
