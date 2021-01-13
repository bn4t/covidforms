<?php

use App\Http\Controllers\AttendeeController;
use App\Http\Controllers\EventController;
use Illuminate\Http\Request;
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

Route::middleware(['throttle:global'])->group(function () {
    Route::get('/ip', function (Request $request) {
        dd($request->ip());
    });
    Route::get('/signup/{date}', [AttendeeController::class, 'create'])->name('attendees.create');

    Route::resource('events', EventController::class);
    Route::get('/events/{event}/remaining_seats', [EventController::class, 'remainingSeats'])->name('events.remaining_seats');
    Route::post('/events/{event}/new_attendee', [AttendeeController::class, 'store'])->name('attendees.store');
    Route::get('/events/{event}/download_attendees', [AttendeeController::class, 'downloadCsv'])->name('attendees.download_csv');


    Route::resource('attendees', AttendeeController::class)->only('destroy');

    require __DIR__.'/auth.php';
});



