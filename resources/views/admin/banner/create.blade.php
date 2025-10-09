@extends('layouts.app')

@section('content')
    <x-admin.pageheader title="Create Banner" />

<x-admin.error/>
    <form method="post" action="{{route('admin.banner.store')}}" class="card w-75 mx-3 mx-auto p-3"
        enctype="multipart/form-data">
        @csrf
        <div class="col-md-12">
            <x-admin.input label="name" name="name" type="text" placeholder="Enter banner name" />
        </div>

        <div class="col-md-12">
            <x-admin.input label="image" name="image" type="file" />
        </div>

        <x-admin.button type="submit" buttontext="Submit" btnclass="btn btn-success" />

    </form>

@endsection