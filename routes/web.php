<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
})->name('home');

// Redirects to forms
Route::redirect('/feedback', 'https://forms.gle/aGtW8T8GrgZX4kAfA')->name('links.feedback');
Route::redirect('/rfp', 'https://forms.gle/M7gXenkv7F21ZYbMA')->name('links.rfp');
Route::redirect('/newsletter', 'https://sla.mailcoach.app/newsletter')->name('links.newsletter');
Route::redirect('/code-of-conduct', 'https://github.com/swiss-laravel-association/policies/blob/main/policies/CODE_OF_CONDUCT.md')->name('links.code_of_conduct');

// Social Media Redirects
Route::redirect('/discord', 'https://discord.gg/Zpjd9ZkPcd')->name('links.discord');
Route::redirect('/whatsapp', 'https://chat.whatsapp.com/J8l1kyqIwK209nrT89Ns2H')->name('links.whatsapp');
Route::redirect('/youtube', 'https://www.youtube.com/@swiss-laravel-association')->name('links.youtube');
Route::redirect('/github', 'https://github.com/swiss-laravel-association')->name('links.github');
Route::redirect('/bluesky', 'https://bsky.app/profile/laravel.swiss')->name('links.bluesky');
Route::redirect('/linkedin', 'https://www.linkedin.com/company/swiss-laravel-association/')->name('links.linkedin');
Route::redirect('/mastodon', 'https://phpc.social/@swiss_laravel_association')->name('links.mastodon');
Route::redirect('/twitter', 'https://x.com/swisslaravel')->name('links.twitter');
Route::redirect('/x', 'https://x.com/swisslaravel')->name('links.x');

Route::get('/meetups/calendar.ics', \App\Http\Controllers\MeetupEventsCalendarController::class)->name('meetups.calendar');
