@props([
    "name" => "",
    "type" => "",
    "placeholder" => "",
    "label" => "",
    "items" => [],
    "classname"=>"",
    "prop"=>"",
    "step"=>""
])

@push("styles")
<style>
.main_image_container {
    height: 200px;
    width: 200px;
    border-radius: 13px;
    box-shadow: 1px 1px 2px rgba(0,0,0,0.5);
    margin-left: 4%!important;
    position: relative;
    cursor: pointer;
    z-index: 1;
}

.plus_icon {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    font-size: 4rem;
    z-index:2;
}
</style>
@endpush

@if($type == "text" || $type == "Number")

    <label for="{{$name}}">{{$label}}</label>
    <input value="{{old($name)}}" {{$step}} {{$prop}} class="form-control {{$classname}}" step="0.01" name="{{$name}}" placeholder="{{$placeholder}}" type="{{$type}}" />

@elseif($type == "select" && isset($items))

    <label for="{{$name}}">{{$label}}</label>
    <select name="{{$name}}" id="{{$name}}" class="form-control">
          <option disabled selected>Select {{$name}}</option>
        @foreach ($items as $item)
         
            <option value="{{$item->id}}" {{ old($name) == $item->id ? 'selected' : '' }}>{{$item->name}}</option>
        @endforeach
    </select>

@elseif($type == "file" && $name=="main_image"||$name=="image")
    <label class="mx-3 my-2" for="{{$label}}">{{$label}}</label>
    <div class="main_image_container">
        <i class="fas fa-plus plus_icon" style="color: grey;"></i>
        <input type="file" class="main_image" name="{{$name}}" id="{{$name}}" accept="image/*" style="display: none;">
    </div>

 @elseif($type=="file" && $name=="product_images") 
 <label class="mx-3 my-2" for="{{$label}}">{{$label}}</label>
  <input type="file" class="product_images form-control" name="{{$name}}" id="{{$name}}" accept="image/*">
@endif

@error($name)
    <span class="text-danger"> 
        {{$message}}
    </span>
@enderror
 