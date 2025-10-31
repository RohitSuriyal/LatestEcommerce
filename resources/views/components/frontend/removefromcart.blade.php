@props([
  "id"=>"",
])
<style>
  .white{
    color:white!important;
  }

</style>



<a href="{{ route('frontend.removefromcart',["id"=>$id]) }}" class="btn btn-danger removefromcart white">Remove</a>