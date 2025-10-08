<?php

namespace App\Models;
use App\Models\Admin\Brand;
use App\Models\Admin\ProductCategory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
class Product extends Model
{
    
     protected $fillable = [
        'name',
        'price',
        'rating',
        'discount',
        'category',
        'subcategory',
        'description',
        'sale_price',
        'stock',          // or 'stock_quantity' if your input name differs
        'main_image',
        'product_images',
        'brand',
        
    ];

    public function categoryname(){

        return $this->belongsTo(ProductCategory::class,"category");
    }

    public function subcategoryname(){
        return $this->belongsTo(Subcategory::class,"subcategory");
    }

    public function brandname(){

        return $this->belongsTo(Brand::class,"brnad");
    }
    public function getCreatedAtAttribute($value)
    {
        return Carbon::parse($value)
                     ->timezone('Asia/Kolkata') // convert to your timezone
                     ->format('d-m-Y');         // format as dd-mm-yyyy
    }

    // Accessor for updated_at
    public function getUpdatedAtAttribute($value)
    {
        return Carbon::parse($value)
                     ->timezone('Asia/Kolkata');
    }
       
}
