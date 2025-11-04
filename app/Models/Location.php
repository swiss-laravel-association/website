<?php

namespace App\Models;

use Database\Factories\LocationFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property string $name
 * @property string|null $address
 * @property string $zip_code
 * @property string $city
 * @property string|null $description
 * @property string|null $notes
 * @property int|null $capacity
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @method static \Database\Factories\LocationFactory factory($count = null, $state = [])
 * @method static Builder<static>|Location newModelQuery()
 * @method static Builder<static>|Location newQuery()
 * @method static Builder<static>|Location query()
 * @method static Builder<static>|Location whereAddress($value)
 * @method static Builder<static>|Location whereCapacity($value)
 * @method static Builder<static>|Location whereCity($value)
 * @method static Builder<static>|Location whereCreatedAt($value)
 * @method static Builder<static>|Location whereDescription($value)
 * @method static Builder<static>|Location whereId($value)
 * @method static Builder<static>|Location whereName($value)
 * @method static Builder<static>|Location whereNotes($value)
 * @method static Builder<static>|Location whereUpdatedAt($value)
 * @method static Builder<static>|Location whereZipCode($value)
 *
 * @mixin \Eloquent
 */
class Location extends Model
{
    /** @use HasFactory<LocationFactory> */
    use HasFactory;
}
