@extends('layouts.app')

@section('content')
    <x-admin.success />
    <x-admin.pageheader title="Subcategpry" buttontext=" +Add ProductCategory"
        link="{{ route('admin.subcategory.create') }}" />

    <div class="card-body">
        {!! $dataTable->table(['class' => 'table table-bordered table-striped'], true) !!}
    </div>
    @push('scripts')
        <!-- jQuery (must load before DataTables) -->


        <!-- Yajra DataTable generated scripts -->
        {!! $dataTable->scripts() !!}

       <!-- Include SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
document.addEventListener("DOMContentLoaded", function () {
    const csrftoken = document.querySelector("meta[name='csrf-token']").getAttribute('content');

    document.addEventListener("click", async function (e) {
        const btn = e.target.closest(".delete-btn");
        if (!btn) return; // not a delete button click

        e.preventDefault();

        // SweetAlert2 confirmation
        const result = await Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!',
            cancelButtonText: 'Cancel'
        });

        if (!result.isConfirmed) return;

        console.log(btn.href);
        try {
            const res = await fetch(btn.href, {
                method: "DELETE",
                headers: {
                    "X-CSRF-TOKEN": csrftoken,
                    "Accept": "application/json"
                }
            });

            if (!res.ok) throw new Error("Delete failed");

            // SweetAlert2 Toast Notification
            Swal.fire({
                toast: true,
                position: 'top-end',
                icon: 'success',
                title: 'Deleted successfully!',
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true
            });

            // Refresh DataTable
            for (let tableId in window.LaravelDataTables) {
                window.LaravelDataTables[tableId].ajax.reload(null, false);
            }

        } catch (err) {
            console.error(err);
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: "Error: " + err.message
            });
        }
    });
});
</script>

    @endpush

@endsection