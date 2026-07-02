<?php

namespace App\Console\Commands;

use App\Models\Event;
use App\Models\Location;
use App\Models\Post;
use App\Models\Speaker;
use App\Models\Sponsor;
use App\Models\Talk;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Model;

class BackfillUlids extends Command
{
    protected $signature = 'ulids:backfill';

    protected $description = 'Backfill ULIDs for existing records that are missing one.';

    /** @var array<int, class-string<Model>> */
    private array $models = [
        Event::class,
        Location::class,
        Post::class,
        Speaker::class,
        Sponsor::class,
        Talk::class,
    ];

    public function handle(): int
    {
        foreach ($this->models as $modelClass) {
            $count = 0;

            $modelClass::query()
                ->whereNull('ulid')
                ->each(function (Model $record) use (&$count): void {
                    $record->setAttribute('ulid', $record->newUniqueId());
                    $record->saveQuietly();
                    $count++;
                });

            $this->info(class_basename($modelClass).": backfilled {$count} record(s).");
        }

        return self::SUCCESS;
    }
}
