@extends('layouts.app')

@section('content')
<x-admin.pageheader title="Product" link="{{route('admin.product.create')}}" buttontext="Add Product"/>
<x-admin.success/>
<div class="card-body">
    {!! $dataTable->table(['class' => 'table table-bordered table-striped'], true) !!}
</div>
{!! $dataTable->scripts() !!}
@endsection