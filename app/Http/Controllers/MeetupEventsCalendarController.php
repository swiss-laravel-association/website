<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Spatie\IcalendarGenerator\Components\Calendar;
use Spatie\IcalendarGenerator\Components\Event;

class MeetupEventsCalendarController extends Controller
{
    public function __invoke(Request $request): Response
    {
        $calendar = Calendar::create()
            ->name('Laravel Switzerland Meetups')
            ->description('Stay up-to-date when the next Laravel Switzerland Meetup is happening.')
            ->refreshInterval(60 * 24); // Instruct clients to refresh the calendar every 24 hours

        \App\Models\Event::query()
            ->where('is_published', true)
            ->orderBy('start_date')
            ->each(function (\App\Models\Event $event) use ($calendar): void {
                $event = Event::create('Laravel Switzerland Meetup - '.$event->start_date->format('F Y'))
                    ->description('🇨🇭 Bringing artisans together across Switzerland. 🤝 In-person meetups where community and learning thrive.')
                    ->uniqueIdentifier('laravel-switzerland-meetup-'.$event->start_date->format('Y-m-d'))
                    ->startsAt($event->start_date)
                    ->endsAt($event->end_date);

                $calendar->event([
                    $event,
                ]);
            });

        return response($calendar->get())
            ->header('Content-Type', 'text/calendar; charset=utf-8');
    }
}
