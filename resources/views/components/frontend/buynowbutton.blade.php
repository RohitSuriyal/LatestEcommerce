
@props([

 "id"=>"",

])
@push('styles')
<style>

    .buy_now_button{
        background-color: #fb641b;
        padding:2% 8%;
        color:white!important;
        font-weight: 600;
    }
</style>

@endpush

<a href="{{ route('frontend.userauthcheck',$id) }}" class="buy_now_button btn">
<i class="fas fa-bolt mr-2" style="color: #ffffff;"></i>BUY NOW
</a>