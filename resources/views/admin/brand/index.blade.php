@extends('layouts.app')
@section('content')
    <x-admin.success />
    <x-admin.pageheader title="Brand" buttontext="Add Brand" link="{{route('admin.brand.create')}}" />

    <div class="card-body">
        {!! $dataTable->table(['class' => 'table table-bordered table-striped'], true) !!}
    </div>

@endsection

@push("scripts")
    {!! $dataTable->scripts() !!}

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

                        window.LaravelDataTables[taleId].ajax.reload(null, false);
                    }

                } catch (err) {
                    console.error(err);
                    alert("Error: " + err.message);
                }
            });
        });
    </script>


@endpush