@extends('layouts.website')

@section('content')
    <x-frontend.success />
    <x-frontend.banner :banners="$banners" />
    <x-frontend.slider :items="$latesproducts" name="topdeals" :wishlist="$wishlist" />
    <x-frontend.slider :items="$latesproducts" name="topproducts" :wishlist="$wishlist" />
    <x-frontend.slider :items="$latesproducts" name="topappliances" :wishlist="$wishlist" />
@endsection

@push('scripts')
    <script>
        document.addEventListener("DOMContentLoaded", function() {

            document.addEventListener("click", async function(e) {

                if (e.target.classList.contains('liked_one')) {

                    e.preventDefault(); // prevent <a> navigation

                    e.stopPropagation();

                    const id = e.target.getAttribute("id");

                    let filled = "";

                    let heart = e.target;
                    

                    if(e.target.classList.contains("filled")){
                        filled="filled"

                    }
                    else{
                        filled="empty"
                    }
                    try {
                        const res = await fetch("{{ route('frontend.liked_products') }}", {

                            method: "post",
                            headers: {
                                "Content-Type": "application/json",
                                "X-CSRF-TOKEN": csrftoken,

                            },
                            body: JSON.stringify({
                                id: id,
                                filled: filled,

                            })
                        });

                        const data = await res.json();

                        if (data.status == "success") {

                            heart.classList.toggle('far');
                            heart.classList.toggle('fas');
                            heart.classList.toggle('filled');
                          

                        }

                        if (data.status == "failure") {
                            const data = {
                                "message": "something went wrong",
                            }
                            failurealert(data);
                        }

                        if (data.status == "filled") {

                            heart.classList.toggle('fas');
                            heart.classList.toggle('far');
                           
                            heart.classList.toggle('filled');


                        }

                        if (data.status == "notloggedin") {
                            const data = {
                                "message": "please Log in"
                            };
                            failurealert(data);
                        }

                    } catch (error) {

                        alert(error.message);
                    }

                }
            });
        })
    </script>
@endpush
