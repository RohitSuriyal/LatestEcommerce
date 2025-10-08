@extends('layouts.app')
@section('content')
    <x-admin.pageheader title="Create Category" />
    <form method="post" action="{{route('admin.Productcategory.store')}}" class="card w-75 mx-auto p-3">
        @csrf
        <h2>Create Category</h2>
        <x-admin.input name="name" type="text" placeholder="Enter Category name" />
        <x-admin.button type="submit" btnclass="btn-success" buttontext="Submit" />
    </form>



@endsection