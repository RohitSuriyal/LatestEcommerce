    @extends('layouts.app')
    @section('content')
        @push('styles')
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
            </style>
        @endpush
        <x-admin.pageheader title="Create Product" />

        <div id="error-container"></div>

        <form method="post" id="productform" class="card w-100 mx-auto p-5 form_width" enctype="multipart/form-data">
            @csrf

            <div class="row my-3">
                <div class="col-md-6">
                    <x-admin.input name="name" label="Name" type="text" placeholder="Enter Product Name" />
                </div>

                <div class="col-md-6">
                    <x-admin.input name="price" classname="price" label="Price" type="Number"
                        placeholder="Enter the Price" />
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

            <div class="row">
                <div class="col-md-12 d-flex align-items-center description_row ">
                    <x-admin.input name="description[]" classname="first_description" type="text" />
                    <x-admin.button type="button" btnclass="btn btn-success description_button mx-3"
                        buttontext="Add Description" />
                </div>
                <span class="first_desc_error">

                </span>
                <div class="description_div w-100">

                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <x-admin.input prop="disabled" label="Sale Price" classname="sale_price" type="Number"
                        name="sale_price" placeholder="Sale Price" />
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
            <div>
                <input type="file" class="mt-5" id="filepondinput" name="product_images[]" multiple>

            </div>
            <x-admin.button btnclass="btn btn-success" id="submit-btn" buttontext="Submit" type="submit" />
        </form>
    @endsection

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // Add description button handler
                document.querySelector(".description_button").addEventListener("click", function() {
                    const description_value = document.querySelector(".first_description").value;
                    const description_row = `
                            <div class="col-md-12 w-100  gap-3 description_row mb-2">
                                <input  value="${description_value}" type="text" name="description[]" class="form-control w-100 flex-grow-1" placeholder="Enter description" />
                                <i class="fas fa-trash delete_description mx-3" style="color: red;"></i>
                            </div>

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
                    const fileInput = document.getElementById('filepondinput');
                    const files = fileInput.files;


                    // Clear previous errors
                    document.getElementById('error-container').innerHTML = '';
                    document.querySelectorAll('.is-invalid').forEach(el => el.classList.remove(
                        'is-invalid'));
                    document.querySelectorAll('.invalid-feedback').forEach(el => el.remove());


                    // Check FilePond files using getFiles() method
                    const fileInputnew = document.getElementById('filepondinput');
                    const pondFiles = pond.getFiles(); // Get files from FilePond instance

                    if (!pondFiles || pondFiles.length === 0) {
                        // Show error if no files selected
                        fileInput.classList.add('is-invalid');
                        const errorDiv = document.createElement('div');
                        errorDiv.className = 'invalid-feedback d-block';
                        errorDiv.textContent = 'Please select at least one product image.';
                        fileInputnew.parentElement.appendChild(errorDiv);
                        showAlert('danger', 'Please select at least one product image.');

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

                        if (response.ok) {
                            // Success
                            showAlert('success', data.message || 'Product created successfully!');

                            // Reset form
                            form.reset();

                            // Optional: Redirect after success
                            setTimeout(() => {
                                if (data.redirect) {
                                    window.location.href = data.redirect;
                                }
                            }, 1500);
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
                        if (field.includes('.')) 
                        {

                            const parts = field.split('.');
                            const baseField = parts[0]; // 'description'
                            const index = parts[1]; // '0', '1', '2'

                            // Get all inputs with this name
                            const inputs = form.querySelectorAll(`[name="${baseField}[]"]`);

                            if (inputs.length > 0 && !isNaN(index)) {
                                // Target specific index
                                const targetInput = inputs[parseInt(index)];
                                if (index == 0) {
                                    targetInput.classList.add('is-invalid');
                                    // Remove existing error message if any
                                    const existingError = targetInput.parentElement.querySelector('.invalid-feedback');
                                    if (existingError) {
                                        existingError.remove();
                                    }

                                    const errorDiv = document.createElement('div');
                                    errorDiv.className = 'invalid-feedback d-block';
                                    errorDiv.textContent = messages[0];
                                    document.querySelector(".first_desc_error").appendChild(errorDiv);

                                } else {

                                    targetInput.classList.add('is-invalid');
                                    // Remove existing error message if any
                                    const existingError = targetInput.parentElement.querySelector('.invalid-feedback');
                                    if (existingError) {

                                        existingError.remove();

                                    }

                                    const errorDiv = document.createElement('div');
                                    errorDiv.className = 'invalid-feedback d-block';
                                    errorDiv.textContent = messages[0];
                                    targetInput.parentElement.appendChild(errorDiv);
                                }

                            }
                        } else {

                            // Handle regular fields
                            const input = form.querySelector(`[name="${field}"]`);
                            console.log(`Looking for regular field: [name="${field}"]`, input);

                            if (input) {
                                input.classList.add('is-invalid');

                                // Remove existing error message if any
                                const existingError = input.parentElement.querySelector('.invalid-feedback');
                                if (existingError) {
                                    existingError.remove();
                                }
                                const errorDiv = document.createElement('div');
                                errorDiv.className = 'invalid-feedback d-block';
                                errorDiv.textContent = messages[0];

                                // For select elements, append after the select
                                if (input.tagName === 'SELECT') {
                                    input.parentElement.appendChild(errorDiv);
                                } else {
                                    input.parentElement.appendChild(errorDiv);
                                }
                            }
                        }
                    }

                    // Show general error alert
                    showAlert('danger', 'Please correct the errors below.');
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
