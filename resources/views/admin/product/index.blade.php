@extends('layouts.app')

@section('content')
    <x-admin.pageheader title="Product" link="{{route('admin.product.create')}}" buttontext="Add Product" />
    <x-admin.success />
   <form method="post" action="{{ route('admin.bulkupload') }}"  enctype="multipart/form-data">
    @csrf
    <input type="file" name="excel_file" class="form-control">
    <button type="submit" class="btn btn-primary mt-2">Upload</button>
</form>


    <div class="card-body">
        {!! $dataTable->table(['class' => 'table table-bordered table-striped'], true) !!}
    </div>
    {!! $dataTable->scripts() !!}
@endsection