<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BookingsController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DocumentationsController;
use App\Http\Controllers\EventsController;
use App\Http\Controllers\LandingPageController;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\UsersController;
use Illuminate\Support\Facades\Route;

Route::get('/welcome', function () {
    return view('welcome');
});

Route::get('/register',function() {
    return view('auth.register');
});
Route::post('/register',[AuthController::class, 'register'])->name('register');
Route::get('/',function() {
    return view('auth.login');
});
Route::post('/',[AuthController::class, 'login'])->name('login');
Route::get('/logout', [AuthController::class, 'logout']);

Route::group(['middleware' => ['auth', 'check_role:admin']], function(){
    Route::get('/dashboard', [DashboardController::class, 'index']);
   
    Route::get('/users',[UsersController::class, 'index']);
    Route::post('/users',[UsersController::class,'store']);
    Route::get('/users/{id}/edit',[UsersController::class,'edit']);
    Route::put('/users/{id}',[UsersController::class,'update']);
    Route::delete('/users/{id}',[UsersController::class,'destroy'])->name('users.destroy');

    Route::controller(BookingsController::class)->group(function() {
        Route::get('/bookings', 'indexAdmin')->name('admin.bookings');
    });
    Route::get('bookings/list', [BookingsController::class, 'listBooking'])->name('bookings.list');
    Route::post('/bookings', [BookingsController::class, 'store']);
    Route::get('/bookings/{id}/edit', [BookingsController::class, 'edit']);
    Route::put('/bookings/{id}', [BookingsController::class, 'update']);
    Route::delete('/bookings/{id}', [BookingsController::class, 'destroy'])->name('bookings.destroy');
    Route::post('/bookings/{id}/approve', [BookingsController::class, 'approve'])->name('booking.approve');
    Route::post('/bookings/{id}/reject', [BookingsController::class, 'reject'])->name('booking.reject');

    Route::get('/events', [EventsController::class,'index']);
    Route::post('/events',[EventsController::class,'store']);
    Route::get('/events/{id}/edit',[EventsController::class,'edit']);
    Route::put('/events/{id}',[EventsController::class,'update']);
    Route::delete('/events/{id}',[EventsController::class,'destroy'])->name('events.destroy');

    Route::get('/documentations', [DocumentationsController::class, 'index']);
    Route::post('/documentations',[DocumentationsController::class, 'store']);
    Route::get('/documentations/{id}/edit', [DocumentationsController::class, 'edit']);
    Route::put('/documentations/{id}', [DocumentationsController::class, 'update']);
    Route::delete('/documentations/{id}',[DocumentationsController::class, 'destroy'])->name('documentations.destroy');
    Route::delete('/documentations/{id}/picture/{index}', [DocumentationsController::class, 'deletePicture'])->name('documentations.deletePicture');



});
Route::group(['middleware' => ['auth', 'check_role:karyawan']], function(){
    Route::post('/notifications/mark-all-read', [BookingsController::class, 'markAllAsRead']);
    Route::get('/landing-page', [LandingPageController::class, 'index']);
    Route::get('/booking',[BookingsController::class,'indexUser'])->name('user.booking');
    Route::post('/booking',[BookingsController::class, 'userStore'])->name('booking.store');
    Route::get('/bookings/by-date', [BookingsController::class, 'getBookingsByDate']);
    Route::delete('/booking/{id}', [BookingsController::class, 'destroy'])->name('booking.destroy');
    Route::get('/documentation', [DocumentationsController::class, 'indexUser']);
    Route::get('/profile', [UsersController::class, 'indexUser']);
    Route::put('/profile/{id}', [UsersController::class, 'updateUser'])->name('profile.update');

});
Route::get('/ticket/{id}', [TicketController::class, 'show'])->middleware('auth')->name('ticket.show');



















































    // Route::post('/notifications/read-all', function () { $user = \Illuminate\Support\Facades\Auth::user();if ($user) {$user->unreadNotifications->markAsRead();}return response()->json(['success' => true]);}); 