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
    <form method="post" action="{{route('admin.product.store')}}" id="productform" class="card w-75 mx-auto p-3" enctype="multipart/form-data">
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
                <x-admin.input name="rating" label="Rating" type="Number" placeholder="Enter the rating" />
            </div>

            <div class="col-md-6">
                <x-admin.input prop="disabled" name="discount" classname="discount" label="Discount(%)" type="Number"
                    placeholder="Discount" />
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
              <x-admin.input label="brand" name="brand" type="select" :items="$brands"/>
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
@endsection
@push("scripts")

    <!-- FilePond image preview plugin -->
    <script src="https://unpkg.com/filepond-plugin-image-preview/dist/filepond-plugin-image-preview.min.js"></script>

    <script>
        // Register the plugin
        FilePond.registerPlugin(FilePondPluginImagePreview);

        let uploadedFilePaths = []; // Store paths externally

        // Initialize FilePond
        const pond = FilePond.create(document.querySelector('#filepondinput'), {
            allowMultiple: true,
            maxFiles: 10,
            acceptedFileTypes: ['image/*'],
            server: {
                process: {
                    url: '{{ route("admin.upload.process") }}',
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    onload: (response) => {
                        const res = JSON.parse(response);
                        uploadedFilePaths.push(res.path); // Store in external array
                        return res.path; // Return JUST the path string
                    },
                    onerror: (response) => {
                        console.error('Upload failed:', response);
                    }
                },

            },
            onremovefile: (error, file) => {
                if (!error && file.serverId) {
                    // Remove from our array when file is removed
                    uploadedFilePaths = uploadedFilePaths.filter(path => path !== file.serverId);
                }
            }
        });

        // Listen to form submission
        document.querySelector('#productform').addEventListener('submit', function (e) {
            // Check if all files are processed
            const allFilesProcessed = pond.getFiles().every(file => file.serverId !== null);

            if (!allFilesProcessed) {
                e.preventDefault();
                alert('Please wait for all files to finish uploading');
                return false;
            }

            // Create/update hidden input with the file paths
            let hiddenInput = document.querySelector('input[name="uploaded_files"]');
            if (!hiddenInput) {
                hiddenInput = document.createElement('input');
                hiddenInput.type = 'hidden';
                hiddenInput.name = 'uploaded_files';
                this.appendChild(hiddenInput);
            }

            // Use the external array we've been maintaining
            hiddenInput.value = JSON.stringify(uploadedFilePaths);

            console.log('Submitting with files:', uploadedFilePaths); // Debug
        });
    </script>

@endpush

@push("scripts")
    <script>
        document.addEventListener('DOMContentLoaded', function () {

            document.querySelector(".description_button").addEventListener("click", function () {

                const description_row = `
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
       const container = document.querySelector('.main_image_container');
const icon = container.querySelector('.plus_icon');
const input = container.querySelector('.main_image');

icon.addEventListener('click', () => input.click());

input.addEventListener('change', (event) => {
    const file = event.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = (e) => {
            container.insertAdjacentHTML(
                'beforeend',
                `<img src="${e.target.result}" style="width:100%;height:100%;object-fit:cover;border-radius:13px;">`
            );
        };
        reader.readAsDataURL(file);
    }
});

    </script>
@endpush


@push("scripts")
    <script>
        document.addEventListener('DOMContentLoaded', function () {

            // Attach listener to both .discount and .price inputs
            document.querySelectorAll('.discount, .price').forEach(function (element) {

                element.addEventListener('input', function () {
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
    </script>


@endpush