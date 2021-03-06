<?php

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

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::resource('/user', 'UserController');
Route::resource('/attendance', 'AttendanceController')->only(['index', 'show']);
Route::get('/print', 'PdfController@print')->name('print');
Route::resource('/shift', 'ShiftController');

Route::post('/getAttendanceDetail', 'AttendanceController@getAttendanceDetail')->name('getAttendanceDetail');
Route::get('/getAttendanceByMonth/{date}', 'AttendanceController@getAttendanceByMonth')->name('getAttendanceByMonth');
