<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CustomAuthController;
use App\Http\Controllers\NavigationController;
use App\Http\Controllers\FunctionController;
use App\Http\Controllers\RerservationController;
use App\Http\Controllers\UserController;

Route::get('/', function () {
    return view('public.welcome');
});
//Authentication
Route::get('/login',[CustomAuthController::class, 'login'])->middleware('alreadyLoggedIn');
Route::get('/registration',[CustomAuthController::class, 'registration'])->middleware('alreadyLoggedIn');
Route::post('/register-user',[CustomAuthController::class, 'registerUser'])->name('register-user');
Route::post('/login-user',[CustomAuthController::class, 'loginUser'])->name('login-user');
//Route::get('/dashboard',[CustomAuthController::class, 'dashboard'])->middleware('isLoggedIn');
Route::get('/logout',[CustomAuthController::class, 'logout']);
//Navigation-Public
Route::get('/contact',[NavigationController::class, 'contact']);
Route::get('/gallery',[NavigationController::class, 'gallery']);
Route::get('/services',[NavigationController::class, 'services']);
//Navigation-Private
Route::get('/dashboard',[NavigationController::class, 'dashboard'])->middleware('isLoggedIn');
Route::get('/profile',[NavigationController::class, 'profile'])->middleware('isLoggedIn');
Route::get('/reservation',[NavigationController::class, 'reservation'])->middleware('isLoggedIn');
//Navigation-Admin
Route::get('/admin-dashboard',[NavigationController::class, 'admin_dashboard'])->middleware('isAdmin');
Route::get('/users',[NavigationController::class, 'users'])->middleware('isAdmin');
Route::get('/admin-reservations',[NavigationController::class, 'admin_reservations'])->middleware('isAdmin');
Route::get('/make-schedule',[NavigationController::class, 'make_schedule'])->middleware('isAdmin');
Route::get('/requests',[NavigationController::class, 'requests'])->middleware('isAdmin');
//Functions
Route::post('/make-reservation',[FunctionController::class, 'reservationUser'])->name('make-reservation');
Route::get('/reservation',[FunctionController::class, 'GetServicesData'])->middleware('isLoggedIn');
Route::get('/users',[UserController::class, 'GetUserDataForAdmin'])->middleware('isAdmin');
Route::get('/admin-reservations',[RerservationController::class, 'GetReservations'])->middleware('isAdmin');
Route::get('/admin-reservationcall',[UserController::class, 'index'])->middleware('isAdmin');
Route::delete('/delete-user/{user}', [UserController::class, 'DeleteUser'])->name('delete-user');
Route::post('/add-user', [UserController::class, 'AddUser'])->name('add-user');
Route::post('/edit-user/{user}', [UserController::class, 'Edit'])->name('edit-user');
Route::post('/contact-submit', [FunctionController::class, 'submitContactForm'])->name('contact-submit');
Route::put('/requests/{contact}', [FunctionController::class, 'update'])->name('edit-request');
Route::delete('/delete-contact/{contact}', [FunctionController::class, 'destroy'])->name('delete-contact');
Route::post('/edit-user-data/{user}', [UserController::class, 'EditUserData'])->name('edit-user-data');
Route::get('/get-appointments',[FunctionController::class, 'GetAppointments'])->name('get-appointments');
Route::delete('/cancelReservation/{id}', [FunctionController::class, 'CancelReservation'])->name('cancelReservation');
Route::delete('/deleteReservation/{id}', [FunctionController::class, 'DeleteReservation'])->name('deleteReservation');
Route::delete('/deleteService/{id}', [FunctionController::class, 'DeleteService'])->name('deleteService');
Route::post('/editService/{id}', [FunctionController::class, 'EditService'])->name('editService');
Route::post('/addService', [FunctionController::class, 'AddService'])->name('addService');
Route::post('/saveSchedule', [FunctionController::class, 'SaveSchedule'])->name('saveSchedule');
Route::get('/get-appointments-data', [FunctionController::class, 'GetAppointmentsData'])->name('get-appointments-data');

