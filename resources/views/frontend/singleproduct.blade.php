@extends('layouts.website')
@section('content')
<div class="conatainer">
    <x-frontend.singleproductcard :product="$product"/>
</div>


@endsection