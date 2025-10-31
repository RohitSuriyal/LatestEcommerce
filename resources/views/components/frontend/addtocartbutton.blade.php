@props([
    'id' => '',
    'classname' => '',
])
@push('styles')
    <style>
         .add_to_cart_button {
            background-color: #fb641b;
            padding: 2% 8%;
            color: white !important;
            font-weight: 600;
        }
    </style>
@endpush


    <button id="{{ $id }}" class="add_to_cart add_to_cart_button btn">
        <i class="fas fa-cart-plus {{ $classname }}"></i> ADD TO CART
    </button>

