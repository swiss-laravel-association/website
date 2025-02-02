<?php

namespace App\Models;

use Carbon\CarbonImmutable;
use Exception;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Event extends Model
{
    use HasFactory;
    protected function casts(): array
    {
        return [
            'api_code' => 'encrypted',
            'sign_in_start' => 'datetime',
            'sign_in_end' => 'datetime',
            'start_date' => 'immutable_datetime',
            'end_date' => 'immutable_datetime',
        ];
    }

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'event_has_users');
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
