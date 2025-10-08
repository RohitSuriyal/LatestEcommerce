<?php

use Illuminate\Support\Str;


if(!function_exists('generateUniqueSlug')){

    function generateUniqueSlug($string, $modelClass, $slugField = 'slug', $ignoreId = null)
    {
        // Basic slug
        $slug = Str::slug($string);

        $original = $slug;
        $count = 1;

        // Check for duplicates
        while ($modelClass::where($slugField, $slug)
            ->when($ignoreId, fn($q) => $q->where('id', '!=', $ignoreId))
            ->exists()) 
        {
            $slug = "{$original}-{$count}";
            $count++;
        }

        return $slug;
    }

}