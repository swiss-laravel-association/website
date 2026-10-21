<?php

namespace App\Mcp\Tools\Concerns;

use App\Models\Event;
use App\Models\Location;
use Carbon\CarbonImmutable;
use Carbon\Exceptions\InvalidFormatException;
use Closure;
use Illuminate\Validation\ValidationException;

/**
 * Validation rules for create-event and update-event, and the conversion of validated input
 * into attributes for the CreateEvent and UpdateEvent actions.
 *
 * Dates are ISO 8601 strings. Without an offset they are read as Europe/Zurich time; with an
 * offset they are converted to Europe/Zurich. They are stored as Zurich wall-clock time.
 */
trait ValidatesEventInput
{
    /**
     * @param  bool  $partial  Whether the fields are optional (update) or required (create).
     * @return array<string, array<int, mixed>>
     */
    protected function eventRules(bool $partial): array
    {
        $required = $partial ? ['sometimes', 'required'] : ['required'];
        $optional = $partial ? ['sometimes', 'nullable'] : ['nullable'];

        return [
            'name' => [...$required, 'string', 'max:255'],
            'description' => [...$required, 'string'],
            'start_date' => [...$required, 'string', $this->eventDateRule()],
            'end_date' => [...$required, 'string', $this->eventDateRule()],
            'location_ulid' => [...$optional, 'string', 'exists:locations,ulid'],
            'meetup_link' => [...$optional, 'url', 'max:255'],
        ];
    }

    /**
     * @return array<string, string>
     */
    protected function eventMessages(): array
    {
        return [
            'location_ulid.exists' => 'No location with this ulid exists. Use list-locations or find-or-create-location to get one.',
        ];
    }

    /**
     * Convert validated input into action attributes: dates become Zurich wall-clock strings and
     * `location_ulid` becomes `location_id`. On update, a date that is not passed is compared with
     * the stored value.
     *
     * @param  array{name?: string, description?: string, start_date?: string, end_date?: string, location_ulid?: string|null, meetup_link?: string|null}  $validated
     * @return array{name?: string, description?: string, start_date?: string, end_date?: string, location_id?: int|null, meetup_link?: string|null}
     *
     * @throws ValidationException When the end date is not after the start date.
     */
    protected function eventAttributes(array $validated, ?Event $event = null): array
    {
        $attributes = [];

        foreach (['name', 'description', 'meetup_link'] as $key) {
            if (array_key_exists($key, $validated)) {
                $attributes[$key] = $validated[$key];
            }
        }

        foreach (['start_date', 'end_date'] as $key) {
            if (isset($validated[$key])) {
                $attributes[$key] = $this->parseEventDate($validated[$key])?->format('Y-m-d H:i:s');
            }
        }

        $start = $attributes['start_date'] ?? $event?->start_date->format('Y-m-d H:i:s');
        $end = $attributes['end_date'] ?? $event?->end_date->format('Y-m-d H:i:s');

        if ($start !== null && $end !== null && $end <= $start) {
            throw ValidationException::withMessages(['end_date' => 'The end_date must be after the start_date.']);
        }

        if (array_key_exists('location_ulid', $validated)) {
            $attributes['location_id'] = $validated['location_ulid'] === null
                ? null
                : Location::query()->where('ulid', $validated['location_ulid'])->value('id');
        }

        /** @var array{name?: string, description?: string, start_date?: string, end_date?: string, location_id?: int|null, meetup_link?: string|null} $attributes */
        return $attributes;
    }

    private function eventDateRule(): Closure
    {
        return function (string $attribute, mixed $value, Closure $fail): void {
            if (! is_string($value) || $this->parseEventDate($value) === null) {
                $fail("The {$attribute} must be an ISO 8601 date-time such as 2026-11-10T18:30:00 (read as Europe/Zurich time) or 2026-11-10T17:30:00Z.");
            }
        };
    }

    /**
     * Parse an ISO 8601 date-time and return it in Europe/Zurich, or null when the value is not
     * a valid date-time. Calendar overflows such as 2026-02-30 are rejected.
     */
    private function parseEventDate(string $value): ?CarbonImmutable
    {
        $pattern = '/^(\d{4}-\d{2}-\d{2})[T ](\d{2}:\d{2})(:\d{2}(\.\d{1,6})?)?(Z|[+-]\d{2}:?\d{2})?$/';

        if (preg_match($pattern, $value, $matches) !== 1) {
            return null;
        }

        try {
            $date = CarbonImmutable::parse($value, 'Europe/Zurich');
        } catch (InvalidFormatException) {
            return null;
        }

        if ($date->format('Y-m-d H:i') !== "{$matches[1]} {$matches[2]}") {
            return null;
        }

        return $date->setTimezone('Europe/Zurich');
    }
}
