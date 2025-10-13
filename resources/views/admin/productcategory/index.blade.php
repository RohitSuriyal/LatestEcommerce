@extends('layouts.app')

@section('content')
<x-admin.success/>
<x-admin.pageheader title="ProductCategory" buttontext=" +Add ProductCategory" link="{{ route('admin.Productcategory.create') }}"/>

<div class="card-body">
    {!! $dataTable->table(['class' => 'table table-bordered table-striped'], true) !!}
</div>
@push('scripts')
<!-- jQuery (must load before DataTables) -->


<!-- Yajra DataTable generated scripts -->
{!! $dataTable->scripts() !!}

 
@endpush

@endsection

