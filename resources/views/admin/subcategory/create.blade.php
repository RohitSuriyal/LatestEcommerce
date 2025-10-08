@extends('layouts.app')
@section('content')
    <x-admin.pageheader title="Create Sbcategory" />
    <x-admin.error/>
    <form method="post" action="{{route('admin.subcategory.store')}}" class="card w-75 mx-auto p-3">
        @csrf
        <x-admin.input label="Name" name="name" type="text" placeholder="Enter the subcategory"/>
        <x-admin.button btnclass="btn-success" type="submit" buttontext="Submit"/>

    </form>
@endsection