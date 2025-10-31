<style>
    #exampleModalwishlist .add_to_cart {
        background-color: #ff9f00;
        padding: 6px 12px;
        color: #fff;
        font-weight: 600;
        font-size: 12px;
        border: none;
        border-radius: 6px;
        transition: background 0.3s;
    }

    #exampleModalwishlist .add_to_cart:hover {
        background-color: #e68900;
    }

    #exampleModalwishlist .wishlist-item {
        border: 1px solid #eee;
        border-radius: 10px;
        box-shadow: 0 3px 8px rgba(0, 0, 0, 0.05);
        padding: 10px;
        margin-bottom: 15px;
        background: #fff;
    }

    #exampleModalwishlist .wishlist-item img {
        border-radius: 8px;
        object-fit: cover;
        height: 80px;
        width: 100%;
    }
</style>

<!-- Wishlist Modal -->
<div class="modal fade" id="exampleModalwishlist" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <h5 class="modal-title fw-bold" id="exampleModalLabel">My Favourites ❤️</h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>

            </div>

            <div class="modal-body" style="max-height: 200px; overflow-y: auto;">
                <div class="wishlist_modal_content">
                    @if (isset($wishlistproducts) && $wishlistproducts->isNotEmpty())
                        @foreach ($wishlistproducts as $item)
                            <div class="row wishlist-item align-items-center">
                                <div class="col-md-4 text-center">
                                    <img src="{{ asset('storage/' . $item->product->main_image) }}"
                                        alt="{{ $item->product->name }}">
                                </div>
                                <div class="col-md-8">
                                    <h6 class="mb-1">{{ $item->product->name }}</h6>
                                    <x-frontend.rating rating="{{ $item->product->rating }}" />
                                    <p class="mb-1 fw-bold text-dark">₹{{ number_format($item->product->sale_price) }}
                                    </p>
                                    <x-frontend.addtocartbutton id="{{ $item->product->id }}" classname="add_to_cart" />
                                </div>
                            </div>
                        @endforeach
                    @else
                        <div class="d-flex justify-content-center align-items-center my-4">
                            <p class="text-muted">No items found in your wishlist 🛒</p>
                        </div>
                    @endif
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary">Proceed</button>
            </div>
        </div>
    </div>
</div>
@push('scripts')
    <script>
        document.addEventListener("DOMContentLoaded", () => {
            document.addEventListener("click", async (e) => {
                if (e.target.classList.contains("wishlist_modal")) {
                    try {
                        const res = await fetch("{{ route('frontend.wishlistproducts') }}", {
                            method: "POST",
                            headers: {
                                "X-CSRF-TOKEN": csrftoken,
                                "Content-Type": "application/json",
                            },
                        });

                        if (!res.ok) {
                            Swal.fire("Error", "Failed to load wishlist.", "error");
                            return;
                        }

                        const data = await res.json();
                        console.log(data);

                        if (data.status === "notloggedin") {
                            Swal.fire("Login Required", "Please log in to view your wishlist.",
                                "warning");
                            return;
                        }

                        if (data.html) {
                            document.querySelector(".wishlist_modal_content").innerHTML = data.html;
                            setTimeout(() => {

                                $("#exampleModalwishlist").modal("show");
                            }, 300);

                        } else {

                            Swal.fire("Empty Wishlist", "No items found in your wishlist.", "info");

                        }

                    } catch (error) {
                        console.error(error);
                        Swal.fire("Error", error.message, "error");
                    }
                }
            });
        });
    </script>
@endpush
