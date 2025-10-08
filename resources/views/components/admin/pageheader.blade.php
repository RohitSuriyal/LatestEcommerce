@props([

    "title"=>"",
    "buttontext"=>"",
    "link"=>"",

])  
  
  <div class="d-flex flex-column flex-sm-row align-items-sm-center justify-content-between mb-4 p-3 bg-white rounded shadow-sm"
        style="box-shadow: 0 4px 15px rgba(0,0,0,0.1);">
        <div>
            <h2>
                {{$title}}
            </h2>
        </div>

        @if($buttontext!="")
        <div class="mt-3 mt-sm-0">
            <a href={{$link}} class="btn btn-primary shadow-sm" style="transition: transform 0.2s, box-shadow 0.2s;"
                onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 6px 20px rgba(0,0,0,0.2)';"
                onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 2px 10px rgba(0,0,0,0.15)';">
                <i class="fas fa-plus-circle"></i> {{$buttontext}}
            </a>
        </div>

        @endif
    </div>