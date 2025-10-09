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

   


@endpush