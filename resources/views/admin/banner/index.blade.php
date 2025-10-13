@extends('layouts.app')
@section('content')
<x-admin.success/>
<x-admin.pageheader title="Banner" buttontext="Add Banner" link="{{route('admin.banner.create')}}"/>
   <div class="card-body">
        {!! $dataTable->table(['class' => 'table table-bordered table-striped'], true) !!}
    </div>
@endsection

@push("scripts")

  {!! $dataTable->scripts() !!}

@endpush