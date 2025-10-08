@props([
    "btnclass"=>"",
    "type"=>"",
    "buttontext"=>"",
  
])


<button style="width:20%" class="btn  my-3 {{$btnclass}}" type="{{$type}}">{!!$buttontext !!}</button>