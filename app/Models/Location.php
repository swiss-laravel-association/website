<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string $name
 * @property string|null $address
 * @property string $zip_code
 * @property string $city
 * @property string|null $description
 * @property string|null $notes
 * @property int|null $capacity
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Location newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Location newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Location query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Location whereAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Location whereCapacity($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Location whereCity($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Location whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Location whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Location whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Location whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Location whereNotes($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Location whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Location whereZipCode($value)
 *
 * @mixin \Eloquent
 */
class Location extends Model {}
