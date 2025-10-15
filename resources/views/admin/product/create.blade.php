@extends('layouts.app')
@section('content')
    @push('styles')
        <!-- FilePond CSS -->
        <link href="https://unpkg.com/filepond@^4/dist/filepond.css" rel="stylesheet" />
        <link href="https://unpkg.com/filepond-plugin-image-preview/dist/filepond-plugin-image-preview.css" rel="stylesheet" />

        <style>
            .description {
                box-shadow: 1px 1px 3px rgba(0, 0, 0, 0.3);
            }

            .delete_description {
                cursor: pointer;
            }

            .alert {
                position: fixed;
                top: 20px;
                right: 20px;
                z-index: 9999;
                min-width: 300px;
            }

            .error {
                color: #dc3545;
                font-size: 0.875rem;
                margin-top: 0.25rem;
                display: none;
            }

            .error.show {
                display: block;
            }
        </style>
    @endpush
    <x-admin.pageheader title="Create Product" />

    <div id="error-container"></div>

    <form method="post" id="productform" class="card w-100 mx-auto p-5 form_width" enctype="multipart/form-data">
        @csrf

        <div class="row my-3">
            <div class="col-md-6">
                <x-admin.input name="name" label="Name" type="text" placeholder="Enter Product Name" />
                <span class="error" id="error-name"></span>
            </div>

            <div class="col-md-6">
                <x-admin.input name="price" classname="price" label="Price" type="Number"
                    placeholder="Enter the Price" />
                <span class="error" id="error-price"></span>
            </div>
        </div>

        <div class="row my-3">
            <div class="col-md-6">
                <x-admin.input classname="rating" name="rating" label="Rating" type="Number"
                    placeholder="Enter the rating" />
                <span class="error" id="error-rating"></span>
            </div>

            <div class="col-md-6">
                <x-admin.input prop="disabled" name="discount" classname="discount" label="Discount(%)" type="Number"
                    placeholder="Discount" />
                <span class="error" id="error-discount"></span>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <x-admin.input label="brand" name="brand" type="select" :items="$brands" />
                <span class="error" id="error-brand"></span>
            </div>
        </div>

        <div class="row my-3">
            <div class="col-md-6">
                <x-admin.input name="category" :items="$productcategories" label="Category" type="select"
                    placeholder="Select the Category" />
                <span class="error" id="error-category"></span>
            </div>
            <div class="col-md-6">
                <x-admin.input name="subcategory" :items="$productsubcategories" label="Subcategory" type="select"
                    placeholder="Select the  Subcategory" />
                <span class="error" id="error-subcategory"></span>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12 d-flex align-items-center description_row ">
                <x-admin.input name="description" classname="first_description" type="text" />
                <x-admin.button type="button" btnclass="btn btn-success description_button mx-3"
                    buttontext="Add Description" />
            </div>
            <span class=" first_desc_error ms-1" style="font-size:0.87rem" id="error-description"></span>
            <div class="description_div w-100"></div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <x-admin.input prop="disabled" label="Sale Price" classname="sale_price" type="Number" name="sale_price"
                    placeholder="Sale Price" />
                <span class="error" id="error-sale_price"></span>
            </div>
            <div class="col-md-6">
                <x-admin.input label="Stock Quantity" name="stock" type="Number" placeholder="Enter the Stock" />
                <span class="error" id="error-stock"></span>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <x-admin.input type="file" name="main_image" label="Image" placeholder="Select the Image" />
                <span class="error" id="error-main_image"></span>
            </div>
        </div>
        <div>
            <input type="file" class="mt-5" id="filepondinput" name="product_images[]" multiple>
            <span class="error" id="error-product_images"></span>
        </div>
        <x-admin.button btnclass="btn btn-success" id="submit-btn" buttontext="Submit" type="submit" />
    </form>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // FilePond is already initialized in the layout file
            // Access the global pond variable defined in layouts.app

            // Add description button handler
            document.querySelector(".description_button").addEventListener("click", function() {
                
                //this is the removal of erro od fist desc item;
                document.querySelector(".first_desc_error").classList.add('d-none');

                const description_value = document.querySelector(".first_description").value;
                const descriptionCount = document.querySelectorAll('[name="description[]"]').length;

                const description_row = `
                        <div class="col-md-12 w-100 gap-3 d-flex align-items-center description_row mb-2">
                            <input value="${description_value}" type="text" name="description[]" class="form-control w-100 flex-grow-1" placeholder="Enter description" />
                            <i class="fas fa-trash delete_description mx-3" style="color: red;"></i>
                        </div>
                        <span class="error w-100" id="error-description-${descriptionCount}"></span>
                        `;
                document.querySelector(".description_div").insertAdjacentHTML("beforeend", description_row);
                document.querySelector(".first_description").value = "";
            });

            // Delete description handler
            document.addEventListener("click", function(e) {
                if (e.target.classList.contains("delete_description")) {
                    const row = e.target.parentElement;
                    if (row && row.classList.contains("description_row")) {
                        row.remove();
                    }
                }
            });

            // Discount and price calculation
            document.querySelectorAll('.discount, .price').forEach(function(element) {
                element.addEventListener('input', function() {
                    const value = parseFloat(document.querySelector(".discount").value);

                    if (value > 100) {
                        document.querySelector(".discount").value = 100;
                        return;
                    }

                    document.querySelectorAll('.discount,.sale_price').forEach(function(element) {
                        element.disabled = false;
                    });

                    const discount = parseFloat(document.querySelector(".discount")?.value) || 0;
                    const price = parseFloat(document.querySelector(".price")?.value) || 0;
                    const sale_price = price - (discount / 100) * price;

                    const salePriceInput = document.querySelector('.sale_price');
                    if (salePriceInput) {
                        salePriceInput.value = sale_price.toFixed(2);
                    }
                });
            });

            // Rating validation
            document.querySelector(".rating").addEventListener('input', function() {
                const value = this.value;
                if (value > 5) {
                    this.value = 5;
                }
            });

            // Async form submission
            const form = document.getElementById('productform');
            const submitBtn = document.getElementById('submit-btn');

            form.addEventListener('submit', async function(e) {


                e.preventDefault();

               const descriptions = document.querySelectorAll('input[name="description[]"]');



                if (descriptions.length==0) {
                    const errorEl = document.querySelector(".first_desc_error");
                    errorEl.innerText = "Description cannot be empty";
                    errorEl.classList.add("show","text-danger");
                }
                


                // Clear previous errors
                document.getElementById('error-container').innerHTML = '';
                document.querySelectorAll('.error').forEach(el => {
                    el.textContent = '';
                    el.classList.remove('show');
                });
                document.querySelectorAll('.is-invalid').forEach(el => el.classList.remove(
                    'is-invalid'));

                // Check FilePond files
                const pondFiles = pond.getFiles();
                if (!pondFiles || pondFiles.length === 0) {
                    const errorSpan = document.getElementById('error-product_images');
                    errorSpan.textContent = 'Please select at least one product image.';
                    errorSpan.classList.add('show');
                  
                }



                // Create FormData object
                const formData = new FormData(form);
                const csrftoken = document.querySelector('meta[name="csrf-token"]').getAttribute(
                    'content');

                try {
                    const response = await fetch('{{ route('admin.product.store') }}', {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'X-CSRF-TOKEN': csrftoken,
                        }
                    });

                    const data = await response.json();

                    if (response.ok) 
                    {
                        // Success
                       
                        form.reset();
                        pond.removeFiles();

                        // Optional: Redirect after success
                        setTimeout(() => {
                            if (data.redirect) {
                                window.location.href = data.redirect;
                            }
                        }, 1000);

                        const result={
                            "message":"Product Added Successfully",
                        }
                        
                        successalert(result);

                    } else {
                        // Validation errors
                        if (data.errors) {
                            displayErrors(data.errors);
                        } else {
                            showAlert('danger', data.message || 'Something went wrong!');
                        }
                    }
                } catch (error) {
                    console.error('Error:', error);
                    showAlert('danger', 'An error occurred while submitting the form.');
                } finally {
                    // Re-enable submit button
                    submitBtn.disabled = false;
                    submitBtn.textContent = 'Submit';
                }
            });

            // Function to display validation errors
            function displayErrors(errors) {
                console.log('Errors received:', errors);

                for (const [field, messages] of Object.entries(errors)) {
                    console.log(`Processing field: ${field}`, messages);

                    // Handle array field errors like 'description.0', 'description.1'
                    if (field.includes('.')) {
                        const parts = field.split('.');
                        const baseField = parts[0];
                        const index = parts[1];

                        // Find error span for this specific index
                        const errorSpan = document.getElementById(`error-${baseField}-${index}`);
                        if (errorSpan) {
                            errorSpan.textContent = messages[0];
                            errorSpan.classList.add('show');
                        }

                        // Add is-invalid class to input
                        const inputs = form.querySelectorAll(`[name="${baseField}[]"]`);
                        if (inputs[parseInt(index)]) {
                            inputs[parseInt(index)].classList.add('is-invalid');
                        }
                    } else {
                        // Handle regular fields
                        const errorSpan = document.getElementById(`error-${field}`);
                        if (errorSpan) {
                            errorSpan.textContent = messages[0];
                            errorSpan.classList.add('show');
                        }

                        // Add is-invalid class to input
                        const input = form.querySelector(`[name="${field}"]`);
                        if (input) {
                            input.classList.add('is-invalid');
                        }
                    }
                }

                //scoll to the first element havinf class #dc3545is-invalid
                const firstInvalidElement = document.querySelector('.is-invalid');

                // Scroll to it if it exists
                if (firstInvalidElement) {
                    firstInvalidElement.scrollIntoView({
                        behavior: 'smooth',
                        block: 'center'
                    });
                }

                // Show general error alert
                const data={
                    "message":"Please fill the complete details"
                }
                failurealert(data);
               
            }

            // Function to show alert messages
            function showAlert(type, message) {
                const alertHtml = `
                    <div class="alert alert-${type} alert-dismissible fade show" role="alert">
                        ${message}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>`;

                document.getElementById('error-container').innerHTML = alertHtml;

                // Auto-dismiss after 5 seconds
                setTimeout(() => {
                    const alert = document.querySelector('.alert');
                    if (alert) {
                        alert.remove();
                    }
                }, 5000);
            }
        });
    </script>
@endpush
