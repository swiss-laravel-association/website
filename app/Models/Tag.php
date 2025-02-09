<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    public function talks()
    {
        return $this->belongsToMany(Talk::class);
    }
}
