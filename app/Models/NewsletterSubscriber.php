<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property string $name
 * @property string $email
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @method static Builder<static>|NewsletterSubscriber newModelQuery()
 * @method static Builder<static>|NewsletterSubscriber newQuery()
 * @method static Builder<static>|NewsletterSubscriber query()
 * @method static Builder<static>|NewsletterSubscriber whereCreatedAt($value)
 * @method static Builder<static>|NewsletterSubscriber whereEmail($value)
 * @method static Builder<static>|NewsletterSubscriber whereId($value)
 * @method static Builder<static>|NewsletterSubscriber whereName($value)
 * @method static Builder<static>|NewsletterSubscriber whereUpdatedAt($value)
 *
 * @mixin \Eloquent
 */
class NewsletterSubscriber extends Model
{
    //
}
