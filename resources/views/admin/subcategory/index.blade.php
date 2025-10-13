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



    @endpush

@endsection