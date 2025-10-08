@extends('layouts.app')
@section('content')
    <x-admin.pageheader title="Create Brand" />
     <x-admin.error/>
    <form class="card mx-auto w-75 p-3" method="post" action="{{route('admin.brand.store')}}">
        @csrf
        
        <x-admin.input type="text" name="name" placeholder="Enter brnad Name" />
        <x-admin.button type="submit" btnclass="btn-success" buttontext="Submit" />
    </form>

@endsection