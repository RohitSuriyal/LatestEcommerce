@props([
    'products' => [],
])

<style>
    /* General modal look */
    #exampleModal .modal-content {
        border-radius: 15px;
        border: none;
        box-shadow: 0 5px 25px rgba(0, 0, 0, 0.1);
    }

    #exampleModal .modal-header {
        background-color: #2874f0;
        color: white;
        border-top-left-radius: 15px;
        border-top-right-radius: 15px;
    }

    #exampleModal .modal-title {
        font-weight: 600;
        font-size: 1.2rem;
        letter-spacing: 0.5px;
    }

    /* Product card look */
    .product_div {
        border-radius: 12px;
        transition: all 0.2s ease-in-out;
        background-color: #fff;
        border: 1px solid #f1f1f1;
    }

    .product_div:hover {
        box-shadow: 0 6px 16px rgba(0, 0, 0, 0.08);
        transform: translateY(-2px);
    }

    /* Image column */
    .product_div img {
        border-radius: 8px;
        background-color: #fafafa;
        padding: 6px;
    }

    /* Product details */
    .product_div h6 {
        font-weight: 600;
        color: #212121;
        margin-bottom: 5px;
    }

    .product_div span.rating-badge {
        background-color: #388e3c;
        padding: 3px 6px;
        border-radius: 5px;
        font-size: 0.75rem;
        font-weight: 600;
        color: white;
        display: inline-flex;
        align-items: center;
    }

    /* Quantity Section */
    .quantity {
        display: flex;
        align-items: center;
        gap: 10px;
        justify-content: flex-start;
        margin-top: 5px;
    }

    .quantity_btn {
        width: 20;
        height: 21px;
        border-radius: 50%;
        border: none;
        background-color: #ff9f00;
        color: white;
        font-size: 1.1rem;
        font-weight: bold;
        transition: background 0.2s;
        display: flex;
        justify-content: center;
        align-items: center;
    }

    .quantity_btn:hover {
        background-color: #fb641b;
    }

    .qty_input {
        text-align: center;
        border: 1px solid #ddd;
        border-radius: 6px;
        width: 55px !important;
        font-size: 0.9rem;
        padding: 0px 0px !important;
        height: auto !important;
    }

    /* Footer Total */
    .card.bg-light {
        background: #f8f9fa !important;
        border-radius: 10px;
        border: 1px solid #eee;
    }

    .card.bg-light h5 {
        font-weight: 600;
        color: #555;
    }

    .card.bg-light h4 {
        color: #388e3c;
        font-weight: 700;
    }

    /* Buttons */
    .modal-footer .btn-primary {
        background-color: #fb641b;
        border: none;
        font-weight: 600;
        padding: 8px 18px;
        border-radius: 6px;
    }

    .modal-footer .btn-primary:hover {
        background-color: #f75a0d;
    }

    .modal-footer .btn-secondary {
        border-radius: 6px;
        font-weight: 600;
    }
</style>

<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header d-flex justify-content-between align-items-center">
                <h5 class="modal-title" id="exampleModalLabel">🛒 Your Cart</h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close"
                    style="opacity: 0.9;">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            @php $total = 0; @endphp

            @if (!empty($products))
                <div class="modal-body">
                    @foreach ($products as $cartproduct)
                        @php $total += (int) $cartproduct->product->sale_price * (int)$cartproduct->quantity; @endphp
                        <div class="row align-items-center my-3 p-3 product_div">
                            <div class="col-md-3 text-center">
                                <img src="{{ asset('storage/' . $cartproduct->product->main_image) }}" alt="Product"
                                    width="100">
                            </div>
                            <div class="col-md-9">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div>
                                        <h6>{{ $cartproduct->product->name }}</h6>
                                        <span class="rating-badge">{{ $cartproduct->product->rating }} ★</span>


                                    </div>
                                    <div class="text-right">
                                        <h6 class="text-dark mb-1">
                                            ₹{{ number_format($cartproduct->product->sale_price, 2) }}
                                        </h6>
                                        <div class="d-flex align-items-center">
                                            <span class="text-muted">x </span>
                                            <span class="quantity">{{ $cartproduct->quantity }}</span>
                                        </div>

                                    </div>
                                </div>
                                <div class="quantity mt-2">
                                    <button id="{{ $cartproduct->product_id }}"
                                        class="decreament quantity_btn">−</button>
                                    <input class="qty_input form-control" type="number"
                                        value="{{ $cartproduct->quantity }}" readonly>
                                    <button id="{{ $cartproduct->product_id }}"
                                        class="increament quantity_btn">+</button>
                                    <x-frontend.removefromcart classname="" id="{{ $cartproduct->product_id }}" />
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif

            <div class=" shadow-sm border-0 p-3 d-flex flex-row justify-content-between align-items-center bg-light">
                <h5 class="mb-0">Grand Total</h5>
                <h4><span>₹</span><span id="grandTotal">{{ number_format($total, 2) }}</span></h4>

            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary proceed_to_pay">Proceed to Purchase</button>
            </div>
        </div>
    </div>
</div>
@push('scripts')
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            document.addEventListener("click", async function(e) {
                if (e.target.classList.contains('quantity_btn')) {
                    const productRow = e.target.closest(".product_div");
                    const input = productRow.querySelector(".qty_input");

                    let value = e.target.classList.contains('increament') ? 1 : -1;

                    if (e.target.classList.contains('decreament') && parseInt(input.value) <= 1) return;

                    const id = e.target.id;
                    try {
                        const res = await fetch("{{ route('frontend.cartproductqty') }}", {
                            method: "POST",
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': csrftoken,
                            },
                            body: JSON.stringify({
                                value,
                                id
                            })
                        });

                        const data = await res.json();

                        input.value = data.quantity;
                        productRow.querySelector(".quantity").innerHTML = data.quantity;
                        document.querySelector("#grandTotal").innerHTML = parseFloat(data.total_amount)
                            .toLocaleString('en-IN');
                    } catch (error) {
                        console.error(error);
                    }
                }
            });
        });


        document.addEventListener("click", async function(e) {

            if (e.target.classList.contains("proceed_to_pay")) {

                e.preventDefault();
                const totalText = document.querySelector("#grandTotal").innerText;
                const amount = Number(totalText.replaceAll(',', ''));

                const res = await fetch("{{ route('frontend.checkoutsession') }}", {
                    method: "POST",
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrftoken,
                    },
                    body: JSON.stringify({
                        amount
                    })
                });

                const data = await res.json();
                if (data.error) return alert(data.error);
                window.open(data.url, '_blank');
            }

        })


        document.addEventListener("click", async function(e) {



            if (e.target.classList.contains('removefromcart')) {


                e.preventDefault();

                const url = e.target.getAttribute("href");


                const res = await fetch(url, {

                    method: "post",
                    headers: {
                        'Content-Type': "application/json",
                        'X-CSRF-TOKEN': csrftoken,
                    }
                });

                const data = await res.json();

                if (data.status == "success") {
                   
                    // Hide the modal first
                    $('#exampleModal').modal('hide');

                  

                    // Once hidden, reopen it
                    $('#exampleModal').one('hidden.bs.modal', function() {
                          //this is the modal_content dynamically setted
                    document.querySelector(".modal_content").innerHTML = data.html;
                        $('#exampleModal').modal('show');
                    });


                }




            }



        })
    </script>
@endpush
