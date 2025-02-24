<?php

namespace App\Helpers;

use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Vite;
use Spatie\SchemaOrg\Schema;

class SchemaHelper
{
    /**
     * @var array<int, array{name: string, url: string, description: string|null}>
     */
    protected static array $subsites = [];

    protected static ?string $canonicalUrl = null;

    public static function addSubsite(string $name, string $url, ?string $description = null): void
    {
        self::$subsites[] = [
            'name' => $name,
            'url' => $url,
            'description' => $description,
        ];
    }

    public static function setCanonicalUrl(string $url): void
    {
        self::$canonicalUrl = $url;
    }

    public static function render(): string
    {
        $schema = Schema::organization()
            ->name('Swiss Laravel Association')
            ->url(config('app.url'))
            ->logo(Vite::asset('resources/images/logos/swiss-laravel-association.webp'))
            ->foundingDate(Carbon::parse('2024-09-26')->startOfDay())
            ->contactPoint(
                Schema::contactPoint()
                    // ->email('info@laravel.swiss')
                    ->availableLanguage(Schema::language()->name('English')),
            )
            ->sameAs([
                'https://www.youtube.com/@swiss-laravel-association',
                'https://bsky.app/profile/laravel.swiss',
                'https://www.linkedin.com/company/swiss-laravel-association/',
                'https://phpc.social/@swiss_laravel_association',
                'https://twitter.com/swisslaravel',
                'https://x.com/swisslaravel',
                'https://github.com/swiss-laravel-association',
            ])
            ->description('Bringing Laravel developers together across Switzerland.');

        foreach (self::$subsites as $subsite) {
            $schema->hasPart( // @phpstan-ignore-line
                Schema::webPage()
                    ->name($subsite['name'])
                    ->url($subsite['url'])
                    ->description($subsite['description'] ?? '')
            );
        }

        if (self::$canonicalUrl) {
            $schema->potentialAction(
                Schema::viewAction()->target(self::$canonicalUrl)
            );
        }

        return $schema->toScript();
    }
}
