<?php

namespace App\Builders;

use App\Models\Event;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * @template TModelClass of Model&Event
 *
 * @extends Builder<TModelClass>
 */
class EventBuilder extends Builder
{
    /**
     * @return self<Event>
     */
    public function published(): self
    {
        return $this->where('is_published', true);
    }

    /**
     * @return self<Event>
     */
    public function notPublished(): self
    {
        return $this->where('is_published', false);
    }

    /**
     * @return self<Event>
     */
    public function upcoming(): self
    {
        return $this->where('start_date', '>', now());
    }

    /**
     * @return self<Event>
     */
    public function past(): self
    {
        return $this->where('start_date', '<', now());
    }
}
