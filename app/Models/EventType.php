<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EventType extends Model
{
    use HasFactory;

    public static function boot() {
        parent::boot();

        static::creating(function ($element) {
            // Get biggest sort_order in the model and add 1
            $biggestSortOrder = EventType::max('sort_order');
            $element->sort_order = $biggestSortOrder + 1;
        });
    }    
}
