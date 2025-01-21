<?php

namespace App\Http\Controllers;

use Carbon\CarbonImmutable;
use Illuminate\Http\Request;
use Spatie\IcalendarGenerator\Components\Calendar;
use Spatie\IcalendarGenerator\Components\Event;

class MeetupEventsCalendarController extends Controller
{
    public function __invoke(Request $request)
    {
        $timezone = 'Europe/Zurich';

        $calendar = Calendar::create()
            ->name('Laravel Switzerland Meetups')
            ->description('Stay up-to-date when the next Laravel Switzerland Meetup is happening.')
            ->refreshInterval(60 * 24); // Instruct clients to refresh the calendar every 24 hours

        $meetupDates = [
            '2025-01-30',
            '2025-02-25',
            '2025-03-26',
            '2025-04-30',
            '2025-05-28',
            '2025-06-26',
            '2025-07-29',
            '2025-08-28',
            '2025-09-30',
            '2025-10-29',
            '2025-11-20',
            '2025-12-09',
        ];

        foreach ($meetupDates as $meetupDate) {
            $date = CarbonImmutable::parse($meetupDate, $timezone);

            $event = Event::create('Laravel Switzerland Meetup - '.$date->format('F Y'))
                ->description('🇨🇭 Bringing artisans together across Switzerland. 🤝 In-person meetups where community and learning thrive.')
                ->uniqueIdentifier('laravel-switzerland-meetup-'.$date->format('Y-m-d'))
                ->startsAt($date->setTime(19, 0))
                ->endsAt($date->setTime(22, 0));

            $calendar->event([
                $event,
            ]);
        }

        return response($calendar->get())
            ->header('Content-Type', 'text/calendar; charset=utf-8');
    }
}
