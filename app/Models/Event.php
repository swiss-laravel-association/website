<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    protected function casts(): array
    {
        return [
            'start_date' => 'immutable_datetime',
            'end_date' => 'immutable_datetime',
        ];
    }
}
