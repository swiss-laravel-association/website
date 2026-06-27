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
        $address = Schema::postalAddress()
            ->streetAddress(config('sla.address.street'))
            ->postalCode(config('sla.address.postal_code'))
            ->addressLocality(config('sla.address.city'))
            ->addressCountry(config('sla.address.country_code'));

        $schema = Schema::organization()
            ->name(config('sla.name'))
            ->legalName(config('sla.legal_name'))
            ->url(config('sla.url'))
            ->logo(Vite::asset(config('sla.logo')))
            ->foundingDate(Carbon::parse(config('sla.founding_date'))->startOfDay())
            ->description(config('sla.description'))
            ->email(config('sla.contact.email'))
            ->telephone(config('sla.contact.phone'))
            ->address($address)
            ->contactPoint(
                Schema::contactPoint()
                    ->email(config('sla.contact.email'))
                    ->telephone(config('sla.contact.phone'))
                    ->availableLanguage(Schema::language()->name('English')),
            )
            ->sameAs(array_values(config('sla.socials')));

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
