<?php

use App\Http\Controllers\AttendeeController;
use App\Http\Controllers\EventController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/signup/{date}', [AttendeeController::class, 'create']);

Route::resource('events', EventController::class);
Route::get('/events/{event}/remaining_seats', [EventController::class, 'remainingSeats'])->name('events.remaining_seats');
Route::post('/events/{event}/new_attendee', [AttendeeController::class, 'store'])->name('attendees.store');
Route::get('/events/{event}/download_attendees', [AttendeeController::class, 'downloadCsv'])->name('attendees.download_csv');


Route::resource('attendees', AttendeeController::class)->only('destroy');

require __DIR__.'/auth.php';
