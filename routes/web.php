<?php

use App\Http\Controllers\AttendeeController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\EventNotificationSettingController;
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


    Route::get('/signup/{date}', [AttendeeController::class, 'create'])->name('attendees.create');

    Route::resource('events', EventController::class);
    Route::get('/events/{event}/remaining_seats', [EventController::class, 'remainingSeats'])->name('events.remaining_seats');
    Route::post('/events/{event}/new_attendee', [AttendeeController::class, 'store'])->name('attendees.store');
    Route::get('/events/{event}/download_attendees', [AttendeeController::class, 'downloadCsv'])->name('attendees.download_csv');
    Route::post('/events/{event}/new_attendee_admin', [AttendeeController::class, 'store_admin'])->name('attendees.store_admin');

    Route::get('/events/{event}/notifications', [EventNotificationSettingController::class, 'edit'])->name('notification_settings.edit');
    Route::post('/events/{event}/notifications', [EventNotificationSettingController::class, 'store'])->name('notification_settings.store');

    Route::resource('attendees', AttendeeController::class)->only('destroy');
    Route::post('/events/{event}/{attendee}/toggle_attendance', [AttendeeController::class, 'toggleAttendance'])->name('attendees.toggle_attendance');

    require __DIR__.'/auth.php';
});

Route::get('/', function () {
    return redirect('/login');
});


