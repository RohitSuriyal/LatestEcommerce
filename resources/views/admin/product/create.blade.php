@extends('layouts.app')
@section('content')

    @push("styles")
        <style>
            .description {
                box-shadow: 1px 1px 3px rgba(0, 0, 0, 0.3);

            }

            .delete_description {
                cursor: pointer;
            }
        </style>
    @endpush
    <x-admin.pageheader title="Create Product" />
    <x-admin.error />
   
    <form method="post" action="{{route('admin.product.store')}}" id="productform" class="card w-100 mx-auto p-5 form_width"
        enctype="multipart/form-data">
        @csrf

        <div class="row my-3">
            <div class="col-md-6">
                <x-admin.input name="name" label="Name" type="text" placeholder="Enter Product Name" />
            </div>

            <div class="col-md-6">
                <x-admin.input name="price" classname="price" label="Price" type="Number" placeholder="Enter the Price" />

            </div>

        </div>
        <div class="row my-3">
            <div class="col-md-6">
                <x-admin.input classname="rating" name="rating" label="Rating" type="Number"
                    placeholder="Enter the rating" />
            </div>

            <div class="col-md-6">
                <x-admin.input prop="disabled" name="discount" classname="discount" label="Discount(%)" type="Number"
                    placeholder="Discount" />
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <x-admin.input label="brand" name="brand" type="select" :items="$brands" />
            </div>
        </div>

        <div class="row my-3">
            <div class="col-md-6">
                <x-admin.input name="category" :items="$productcategories" label="Category" type="select"
                    placeholder="Select the Category" />
            </div>
            <div class="col-md-6">
                <x-admin.input name="subcategory" :items="$productsubcategories" label="Subcategory" type="select"
                    placeholder="Select the  Subcategory" />

            </div>
        </div>

        <div class="row description_div">
            <div class="col-md-12 d-flex align-items-center description_row ">
                <x-admin.input name="description[]" type="text" />
                <x-admin.button type="button" btnclass="btn btn-success description_button mx-3"
                    buttontext="Add Description" />
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <x-admin.input prop="disabled" label="Sale Price" classname="sale_price" type="Number" name="sale_price"
                    placeholder="Sale Price" />
            </div>
            <div class="col-md-6">
                <x-admin.input label="Stock Quantity" name="stock" type="Number" placeholder="Enter the Stock" />
            </div>

        </div>
        <div class="row">
            <div class="col-md-12">
                <x-admin.input type="file" name="main_image" label="Image" placeholder="Select the Image" />
            </div>

        </div>

        <input type="file" class="mt-5" id="filepondinput" name="product_images[]" multiple>

        <x-admin.button btnclass="btn btn-success" buttontext="Submit" type="submit" />

    </form>
    @if ($errors->any())
        <script>
            window.isErrorReload = true;
        </script>
    @endif

@endsection
@push("scripts")
    <script>
        document.addEventListener('DOMContentLoaded', function () {

            document.querySelector(".description_button").addEventListener("click", function () {

                const description_row =
                    `
                        <div class="col-md-12 d-flex align-items-center gap-3 description_row mb-2">
                            <input type="text" name="description[]" class="form-control flex-grow-1" placeholder="Enter description" />
                            <i class="fas fa-trash delete_description mx-3" style="color: red;"></i>
                            </div>`;

                document.querySelector(".description_div").insertAdjacentHTML("beforeend", description_row);

            })
        })

        document.addEventListener("click", function (e) {
            // Check if the clicked element is the trash icon
            if (e.target.classList.contains("delete_description")) {
                // Assuming the trash icon is directly inside the description_row
                const row = e.target.parentElement;
                if (row && row.classList.contains("description_row")) {
                    row.remove();
                }
            }
        });

    </script>
@endpush

@push("scripts")

    <script>
        document.addEventListener('DOMContentLoaded', function () 
        {    
            // Attach listener to both .discount and .price inputs
            document.querySelectorAll('.discount, .price').forEach(function (element) {

                element.addEventListener('input', function () {

                    const value = parseFloat(document.querySelector(".discount").value);
                    console.log(value);

                    if (value > 100) 
                    {
                        document.querySelector(".discount").value = 100;

                        return;
                    }
                    document.querySelectorAll('.discount,.sale_price').forEach(function (element) {

                        element.disabled = false;
                    });

                    console.log("Input changed");

                    const discount = parseFloat(document.querySelector(".discount")?.value) || 0;
                    const price = parseFloat(document.querySelector(".price")?.value) || 0;

                    const sale_price = price - (discount / 100) * price;

                    const salePriceInput = document.querySelector('.sale_price');
                    if (salePriceInput) {
                        salePriceInput.value = sale_price.toFixed(2);
                    }
                });
            });

        });
        window.addEventListener("DOMContentLoaded", () => {
            if (window.isErrorReload) 
            {

                const priceEl = document.querySelector(".price");

                if (priceEl && priceEl.value) 
                {
                    console.log("Laravel redirect with error — enabling fields...");
                    document.querySelectorAll('.sale_price, .discount').forEach(el => el.disabled = false);
                }

            } else {
                console.log("Normal load");
            }
        });


    </script>
    {{-- //for the discount valiadtion --}}
    <script>
     document.addEventListener('DOMContentLoaded',function(){
        
        document.querySelector(".rating").addEventListener('input', function () {

                const value = this.value;
                if (value > 5) {
                    this.value = 5;
                    return;
                }
            })
     })

    </script>


@endpush