<?php

use App\Http\Controllers\Association\SponsorsController;
use App\Http\Controllers\HomepageController;
use App\Http\Controllers\MeetupEventsCalendarController;
use Illuminate\Support\Facades\Route;

Route::get('/', HomepageController::class)->name('home');

// TODO: Implement the following routes
// Route::get('/association/about' fn () => '');
// Route::get('/association/membership' fn () => '');
// Route::get('/association/become-a-member' fn () => ''); // Not sure if we should have a dedicated page for this
 Route::get('/association/sponsors', SponsorsController::class)->name('association.sponsors');
// Route::get('/association/leadership' fn () => '');
// Route::get('/association/members/{member:slug}' fn () => '');

// Route::get('/meetups' fn () => '');
// Route::get('/meetups/speakers/{speaker:slug}' fn () => '');
// Route::get('/meetups/talks/{talk:slug}' fn () => '');
// Route::get('/meetups/calendar' fn () => '');
// Route::get('/meetups/apply-to-speak' fn () => '');
// Route::get('/meetups/apply-to-host' fn () => ''); // Not sure if we should have this

// Route::get('/blog' fn () => '');
// Route::get('/blog/{post:slug}' fn () => '');

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

Route::get('/meetups/calendar.ics', MeetupEventsCalendarController::class)->name('meetups.calendar');
