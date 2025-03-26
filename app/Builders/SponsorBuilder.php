<?php

namespace App\Builders;

use App\Enums\SponsorType;
use App\Models\Sponsor;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * @template TModelClass of Model&Sponsor
 *
 * @extends Builder<TModelClass>
 */
class SponsorBuilder extends Builder
{
    /**
     * @return self<Sponsor>
     */
    public function founding(): self
    {
        return $this->where('type', SponsorType::Founding);
    }

    /**
     * @return self<Sponsor>
     */
    public function location(): self
    {
        return $this->where('type', SponsorType::Location);
    }
}
