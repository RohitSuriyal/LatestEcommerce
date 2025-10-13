@props([
  "products" => [],
  "id" => "",
  "categories" => [],
  "brands"=>[],
  "selectedcategories"=>[],
])

@push("styles")
<style>
  /* --- Shadow and hover for product boxes --- */
  .box_shadow {
    box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
    border-radius: 8px;
    transition: all 0.3s ease;
  }
  .box_shadow:hover {
    transform: scale(1.01);
  }

  /* --- Custom dropdown styles --- */
  .custom-dropdown {
    width: 100%;
    margin-bottom: 2%!important;
  }

  .custom-dropdown button {
    width: 100%;
    text-align: left;
    background-color: #f8f9fa;
    border: 1px solid #ddd;
    border-radius: 8px;
    padding: 3px 12px;
    cursor: pointer;
    transition: all 0.2s ease;
  }

  .custom-dropdown button:hover {
    background-color: #f1f3f5;
  }

  .custom-dropdown button:focus {
    outline: none;
    
  }

  /* Dropdown content (animated open) */
  .custom-dropdown .dropdown-content {
    background: #fff;
    border: 1px solid #ddd;
    border-top: none;
    border-radius: 0 0 8px 8px;
    max-height: 0;
    overflow: hidden;
    opacity: 0;
    transition: max-height 0.25s ease, opacity 0.2s ease;
    will-change: max-height, opacity;
  }

  .custom-dropdown.open .dropdown-content {
    max-height: 200px;
    opacity: 1;
    overflow-y: auto;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
  }

  /* Prevent scrollbar flicker */
  .custom-dropdown .dropdown-content::-webkit-scrollbar {
    width: 6px;
  }
  .custom-dropdown .dropdown-content::-webkit-scrollbar-thumb {
    background-color: rgba(0, 0, 0, 0.15);
    border-radius: 4px;
  }

  .custom-dropdown ul {
    list-style: none;
    padding: 0;
    margin: 0;
  }

  .custom-dropdown li {
    padding: 8px 12px;
    cursor: pointer;
    transition: background 0.2s;
  }

  .custom-dropdown li:hover {
    background: #f8f9fa;
  }

  /* Filter title */
  .filter-title {
    font-weight: 600;
    margin-bottom: 1rem;
  }
  .category-list {
    list-style: none;
    padding-left: 0;
  }
  .category-list li {
    padding: 6px 10px;
    border-radius: 6px;
    cursor: pointer;
    transition: background 0.2s ease;
  }
  .category-list li:hover {
    background-color: #f7f7f7;
  }
  .category-checkbox {
    accent-color: #007bff; /* Bootstrap blue */
  }

  
</style>
@endpush

<div class="card p-3">
  <div class="row">
    <!-- Sidebar Filters -->
    <div class="col-md-2 border-right pr-3">
      <h5 class="filter-title">Filters</h5>

      <!-- First Dropdown -->
      <div class="custom-dropdown" id="dropdown1">
        <button type="button" class="d-flex justify-content-between align-items-center">
          <span id="selectedCategory1">Select Category</span>
         <i class="fas fa-caret-down"></i>
        </button>
        <div class="dropdown-content">
          <ul class="category-list">
                @foreach ($categories as $category)
                  <li data-target="selectedCategory1" class="d-flex align-items-center mb-2">
                    <input type="checkbox"
                        class="mr-2 category-checkbox"
                        value="{{ $category->id }}"
                        id="cat_{{ $category->id }}"
                        @if(isset($selectedcategories) && in_array($category->id, $selectedcategories)) checked @endif>

                    <label for="cat_{{ $category->id }}" class="m-0">{{ $category->name }}</label>
                  </li>
                @endforeach
          </ul>
        </div>
      </div>

      <!-- Second Dropdown -->
      <div class="custom-dropdown" id="dropdown2">
        <button class="d-flex justify-content-between align-items-center" type="button">
          <span id="selectedCategory2">Select Brand</span>
          <i class="fas fa-caret-down"></i>
        </button>
        <div class="dropdown-content">
          <ul class="category-list">
            @foreach ($brands as $brand)
              <li data-target="selectedCategory1" class="d-flex align-items-center mb-2">
                <input 
                    @if (isset($selctedbrands) && in_array($brand->id, $selctedbrands))
                        checked
                    @endif
                    type="checkbox" 
                    class="mr-2 brand-checkbox" 
                    value="{{ $brand->id }}" 
                    id="brand_{{ $brand->id }}">

                <label for="cat_{{ $brand->id }}" class="m-0">{{ $brand->name }}</label>
              </li>
            @endforeach
          </ul>

        </div>
      </div>
    </div>

    <!-- Product List -->
    <div class="col-md-9 pl-4 ">
      @foreach ($products as $product)
      <a class="list-unstyled text-decoration-none" href="{{ route('frontend.singleproduct',$product->id) }}">
        <div class="row d-flex mb-3 p-2 box_shadow ">
                <div class="col-md-2">
                  <img height="200px" style="object-fit:cover" class="w-100 rounded"
                    src="{{ asset('storage/' . $product->main_image) }}"
                    alt="{{ $product->name }}">
                </div>

                <div class="col-md-9 p-3">
                  <div class="row ">
                    <div class="col-md-6">
                      <h5 class="ml-3">{{ $product->name }}</h5>
                      <span class="ml-3 px-2 py-1 text-white font-weight-bold"
                        style="background-color: green; border-radius: 6px; font-size: 0.8rem;">
                        {{ $product->rating }} ★
                      </span>
                    
                      @if(is_array($product->description) && count($product->description))
                      <ul class="m-0 mt-2">
                        @foreach ($product->description as $desc)
                        <li>{{ $desc }}</li>
                        @endforeach
                      </ul>
                      @endif
                    </div>

                    <div class="col-md-6 text-right">
                      <h3 class="text-primary font-weight-bold mb-1">&#8377;{{ $product->sale_price }}</h3>
                      <p class="m-0">
                        <s class="text-muted">&#8377;{{ $product->price }}</s>
                        <span class="text-success font-weight-bold ml-2">{{ $product->discount }}% off</span>
                      </p>
                    </div>
                  </div>
                </div>
              </div>
      </a>
     
      @endforeach
      <!-- Pagination -->
      <div class="d-flex justify-content-center my-4">

        {{ $products->links() }}
      </div>
    </div>
  </div>
</div>


