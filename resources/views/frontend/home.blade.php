@extends('layouts.website')

@section("content")
    
    <x-frontend.banner :banners="$banners" />
    <x-frontend.slider :items="$latesproducts" name="topdeals" />
    <x-frontend.slider :items="$latesproducts" name="topproducts"/>
    

@endsection