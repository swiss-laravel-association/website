<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
})->name('home');

// Redirects to forms
Route::redirect('/feedback', 'https://forms.gle/aGtW8T8GrgZX4kAfA');
Route::redirect('/rfp', 'https://forms.gle/M7gXenkv7F21ZYbMA');
Route::redirect('/newsletter', 'https://sla.mailcoach.app/newsletter');
Route::redirect('/code-of-conduct', 'https://github.com/swiss-laravel-association/policies/blob/main/policies/CODE_OF_CONDUCT.md');

// Social Media Redirects
Route::redirect('/discord', 'https://discord.gg/Zpjd9ZkPcd');
Route::redirect('/whatsapp', 'https://chat.whatsapp.com/J8l1kyqIwK209nrT89Ns2H');
Route::redirect('/youtube', 'https://www.youtube.com/@swiss-laravel-association');
Route::redirect('/github', 'https://github.com/swiss-laravel-association');
Route::redirect('/bluesky', 'https://bsky.app/profile/laravel.swiss');
Route::redirect('/linkedin', 'https://www.linkedin.com/company/swiss-laravel-association/');
Route::redirect('/mastodon', 'https://phpc.social/@swiss_laravel_association');
Route::redirect('/twitter', 'https://x.com/swisslaravel');
Route::redirect('/x', 'https://x.com/swisslaravel');

Route::get('/meetups/calendar', \App\Http\Controllers\MeetupEventsCalendarController::class)->name('meetups.calendar');
