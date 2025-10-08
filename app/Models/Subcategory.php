<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
class Subcategory extends Model
{
    protected $fillable=["name"];


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
                     ->timezone('Asia/Kolkata')
                     ->format('d-m-Y');
    }
}

