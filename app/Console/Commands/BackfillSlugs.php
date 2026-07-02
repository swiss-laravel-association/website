<?php

namespace App\Console\Commands;

use App\Models\Event;
use App\Models\Speaker;
use App\Models\Talk;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class BackfillSlugs extends Command
{
    protected $signature = 'slugs:backfill';

    protected $description = 'Backfill slugs for existing records that are missing one.';

    /** @var array<class-string<Model>, string> */
    private array $models = [
        Event::class => 'name',
        Talk::class => 'title',
        Speaker::class => 'name',
    ];

    public function handle(): int
    {
        foreach ($this->models as $modelClass => $sourceField) {
            $count = 0;

            $modelClass::query()
                ->whereNull('slug')
                ->each(function (Model $record) use ($sourceField, &$count): void {
                    $record->setAttribute('slug', Str::slug((string) $record->getAttribute($sourceField)));
                    $record->saveQuietly();
                    $count++;
                });

            $this->info(class_basename($modelClass).": backfilled {$count} record(s).");
        }

        return self::SUCCESS;
    }
}
