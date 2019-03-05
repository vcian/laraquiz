<?php

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

// Route::get('/{any}', 'SinglePageController@index')->where('any', '.*');
Route::prefix('admin')->group(function () {
    Auth::routes();

    Route::resource('quiz', 'QuizController');
});

Route::get('/dashboard', 'HomeController@index')->name('admin.dashboard');
