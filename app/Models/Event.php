<?php

namespace App\Models;

use Carbon\CarbonImmutable;
use Exception;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;
    protected function casts(): array
    {
        return [
            'api_code' => 'encrypted',
            'sign_in_start' => 'datetime',
            'sign_in_end' => 'datetime'
        ];
    }

    public function users()
    {
        return $this->belongsToMany(User::class, '');
    }

    public static function getBySignature($signature): ?Event
    {
        $today = CarbonImmutable::now();
        try{
            return Event::all()
                ->filter(fn(Event $e) => $today->between($e->sign_in_start, $e->sign_in_end))
                ->firstOrFail(fn(Event $e) => md5($e->api_code) === $signature);
        }catch (Exception){
            return null;
        }
    }
}
