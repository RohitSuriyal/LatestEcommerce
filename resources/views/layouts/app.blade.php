<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />

    <!-- Styles / Scripts -->

    {{-- @vite(['resources/css/app.css', 'resources/js/app.js']) --}}
    <link href="{{asset('vendor/fontawesome-free/css/all.min.css')}}" rel="stylesheet" type="text/css">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link href="{{asset('css/sb-admin-2.min.css')}}" rel="stylesheet" />
    <link href="https://unpkg.com/filepond@^4/dist/filepond.css" rel="stylesheet" />

    {{-- this is the link href --}}
    <link href="https://unpkg.com/filepond-plugin-image-preview/dist/filepond-plugin-image-preview.min.css"
        rel="stylesheet">
    <style>
        h1 {
            color: black;
        }

        p {
            color: grey;
        }

        h2 {
            color: black;
        }

        .form-control:focus {
            box-shadow: none !important;
        }

        td {
            color: black;
            font-weight: 500;
        }

        label {
            color: black;
            font-weight: bold;
        }

        .form_width {
            width: 93% !important;
        }
    </style>


    @stack("styles")
</head>

<body class="">

    <x-admin.header />

    @yield("content")

    <x-admin.footer />

    <link rel="stylesheet" href="https://cdn.datatables.net/2.3.4/css/dataTables.bootstrap4.min.css">
    <!-- DataTables JS -->
    <script src="https://cdn.datatables.net/2.3.4/js/dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/2.3.4/js/dataTables.bootstrap4.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://unpkg.com/filepond@^4/dist/filepond.js"></script>


    @push("scripts")
        {{--
        <script>
            document.addEventListener("DOMContentLoaded", function () {
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

            })
        </script> --}}
        <script>
            document.addEventListener("DOMContentLoaded", function () {
                const container = document.querySelector('.main_image_container');
                const icon = container.querySelector('.plus_icon');
                const input = container.querySelector('.main_image');

                icon.addEventListener('click', () => input.click());

                input.addEventListener('change', (event) => {
                    console.log("fsdfsfdsf");
                    const file = event.target.files[0];
                    if (file) {
                        const reader = new FileReader();
                        reader.readAsDataURL(file);  //this is thensertion process
                        reader.onload = (e) => {
                            // Remove any existing preview images first
                            const existingImg = container.querySelector('img');
                            if (existingImg) existingImg.remove();

                            // Insert the new image


                            container.insertAdjacentHTML(
                                'beforeend',
                                `<img src="${e.target.result}" style="width:100%;height:100%;object-fit:cover;border-radius:13px;">`
                            );
                        };

                    }
                });
            });
        </script>

        <script>
            document.addEventListener("DOMContentLoaded", function () {
                const csrftoken = document.querySelector("meta[name='csrf-token']").getAttribute('content');

                document.addEventListener("click", async function (e) {
                    const btn = e.target.closest(".delete-btn");
                    if (!btn) return; // not a delete button click

                    e.preventDefault();

                    if (!confirm("Are you sure you want to delete this?")) return;

                    try {
                        
                        const res = await fetch(btn.href, {
                            method: "DELETE",
                            headers: {
                                "X-CSRF-TOKEN": csrftoken,
                                "Accept": "application/json"
                            }
                        });

                        if (!res.ok) throw new Error("Delete failed");

                        alert("Deleted successfully!");
                        for (let tableId in window.LaravelDataTables) {

                            window.LaravelDataTables[tableId].ajax.reload(null, false);
                        }

                    } catch (err) {
                        console.error(err);
                        alert("Error: " + err.message);
                    }
                });
            });
        </script>
    @endpush

  

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

    @stack("scripts")


</body>

</html>