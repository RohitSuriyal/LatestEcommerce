@extends('layouts.app')

@push('styles')
    <style>
        .upload-section {
            background: white;
            padding: 1.5rem;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            margin-bottom: 2rem;
        }

        .upload-form-row {
            display: flex;
            align-items: flex-start;
            gap: 1rem;
        }

        .file-input-wrapper {
            flex: 1;
        }

        .file-input-wrapper>label {
            display: block;
            margin-bottom: 0.5rem;
            color: #334155;
            font-weight: 500;
            font-size: 0.95rem;
        }

        .file-input-group {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .custom-file-input {
            display: none;
        }

        .select-file-btn {
            flex: 1;
            padding: 0.5rem 1rem;
            border: 2px solid #e2e8f0;
            border-radius: 6px;
            background: white;
            font-size: 0.95rem;
            color: #64748b;
            cursor: pointer;
            transition: all 0.3s ease;
            text-align: left;
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .select-file-btn:hover {
            border-color: #6c63ff;
            background: #f0f4ff;
        }

        .select-file-btn i {
            color: #6c63ff;
        }

        .file-placeholder {
            color: #94a3b8;
        }

        .file-selected {
            color: #334155;
            font-weight: 500;
        }

        .upload-btn {
            margin-top: 1.85rem;
            padding: 0.5rem 2rem;
            background: #6c63ff;
            color: white;
            border: none;
            border-radius: 6px;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.3s ease;
            white-space: nowrap;
            height: fit-content;
        }

        .upload-btn:hover:not(:disabled) {
            background: #5a52d5;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(108, 99, 255, 0.3);
        }

        .upload-btn:disabled {
            background: #cbd5e1;
            cursor: not-allowed;
        }

        .upload-btn i {
            margin-right: 0.5rem;
        }

        .file-info {
            margin-top: 0.5rem;
            padding: 0.5rem 1rem;
            background: #f0fdf4;
            border: 1px solid #86efac;
            border-radius: 6px;
            display: none;
            align-items: center;
            gap: 0.75rem;
            font-size: 0.9rem;
        }

        .file-info.active {
            display: flex;
        }

        .file-info-icon {
            color: #22c55e;
        }

        .file-info-text {
            flex: 1;
            color: #166534;
        }

        .file-info-text strong {
            color: #15803d;
        }

        .error-alert {
            margin-top: 1rem;
            padding: 0.75rem 1rem;
            background: #fef2f2;
            border: 1px solid #fecaca;
            border-radius: 6px;
            color: #991b1b;
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .error-alert i {
            color: #dc2626;
        }

        .accepted-formats {
            margin-top: 0.25rem;
            font-size: 0.85rem;
            color: #64748b;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .download-demo-link {
            color: #6c63ff;
            text-decoration: none;
            font-weight: 500;
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
            transition: all 0.3s ease;
        }

        .download-demo-link:hover {
            color: #5a52d5;
            text-decoration: underline;
        }

        .download-demo-link i {
            font-size: 0.9rem;
        }
    </style>
@endpush

@section('content')
    <x-admin.pageheader title="Product" link="{{ route('admin.product.create') }}" buttontext="Add Product" />
    <x-admin.success />

    <div class="upload-section">
        <form method="post" action="{{ route('admin.bulkupload') }}" enctype="multipart/form-data" id="uploadForm">
            @csrf

            <div class="upload-form-row">
                <div class="file-input-wrapper">
                    <label>
                        <i class="fas fa-file-excel"></i> Select Excel File
                    </label>
                    <div class="file-input-group">
                        <input type="file" name="excel_file" id="excelFileInput" class="custom-file-input"
                            accept=".xlsx,.xls,.csv">
                        <button type="button" class="select-file-btn" id="selectFileBtn">
                            <i class="fas fa-upload"></i>
                            <span id="fileDisplayText" class="file-placeholder">Click to choose Excel file</span>
                        </button>
                    </div>
                    <div class="accepted-formats">
                        <span>Accepted formats: XLSX, XLS, CSV</span>
                        <a href="{{ asset('documents/demo1.xlsx') }}" class="download-demo-link" download>
                            <i class="fas fa-download"></i> Download Demo Sheet
                        </a>
                    </div>

                    <div class="file-info" id="fileInfo">
                        <i class="fas fa-check-circle file-info-icon"></i>
                        <div class="file-info-text">
                            <strong id="fileName"></strong>
                            <span id="fileSize"></span>
                        </div>
                    </div>
                </div>

                <button type="submit" class="upload-btn" id="submitBtn" disabled>
                    <i class="fas fa-paper-plane"></i> Upload
                </button>
            </div>

            @if (session('upload_errors'))
                <div class="error-alert mt-3">
                    <h6>Upload Errors:</h6>
                    <ul>
                        @foreach (session('upload_errors') as $uploadError)
                            <li>{{ $uploadError }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
        </form>
    </div>

    <div class="card-body">
        {!! $dataTable->table(['class' => 'table table-bordered table-striped'], true) !!}
    </div>

    {!! $dataTable->scripts() !!}

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const fileInput = document.getElementById('excelFileInput');
                const selectFileBtn = document.getElementById('selectFileBtn');
                const fileDisplayText = document.getElementById('fileDisplayText');
                const fileInfo = document.getElementById('fileInfo');
                const fileName = document.getElementById('fileName');
                const fileSize = document.getElementById('fileSize');
                const submitBtn = document.getElementById('submitBtn');

                // Click on button to trigger file input
                selectFileBtn.addEventListener('click', function() {
                    fileInput.click();
                });

                // File input change handler
                fileInput.addEventListener('change', function(e) {
                    handleFile(e.target.files[0]);
                });

                // Handle file display
                function handleFile(file) {
                    if (!file) {
                        fileInfo.classList.remove('active');
                        fileDisplayText.textContent = 'Click to choose Excel file';
                        fileDisplayText.className = 'file-placeholder';
                        submitBtn.disabled = true;
                        return;
                    }

                    // Validate file type
                    const validTypes = ['.xlsx', '.xls', '.csv'];
                    const fileExtension = '.' + file.name.split('.').pop().toLowerCase();

                    if (!validTypes.includes(fileExtension)) {
                        alert('Please upload a valid Excel file (.xlsx, .xls, or .csv)');
                        fileInput.value = '';
                        fileInfo.classList.remove('active');
                        fileDisplayText.textContent = 'Click to choose Excel file';
                        fileDisplayText.className = 'file-placeholder';
                        submitBtn.disabled = true;
                        return;
                    }

                    // Display file info
                    fileDisplayText.textContent = file.name;
                    fileDisplayText.className = 'file-selected';
                    fileName.textContent = file.name;
                    fileSize.textContent = ' (' + formatFileSize(file.size) + ')';
                    fileInfo.classList.add('active');
                    submitBtn.disabled = false;
                }

                // Format file size
                function formatFileSize(bytes) {
                    if (bytes === 0) return '0 Bytes';
                    const k = 1024;
                    const sizes = ['Bytes', 'KB', 'MB', 'GB'];
                    const i = Math.floor(Math.log(bytes) / Math.log(k));
                    return Math.round(bytes / Math.pow(k, i) * 100) / 100 + ' ' + sizes[i];
                }
            });
        </script>
    @endpush
@endsection
