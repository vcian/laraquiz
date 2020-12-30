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

Route::prefix('admin')->name('admin.')->group(function () {
    Auth::routes();

    Route::resource('quiz', 'BackEnd\\QuizController');
    Route::get('download-sample', 'BackEnd\QuizController@downloadSample')->name('download.sample');
    Route::get('/dashboard', 'HomeController@index')->name('dashboard');
    Route::get('export-quiz-results/{quiz_id}','BackEnd\QuizController@exportQuizResults')->name('quiz-results.export');
});



/**
 * Frontend quiz related routes
 */

Route::prefix('quiz')->group(function () {
    Route::group(['middleware' => ['disablepreventback']], function () {
        Route::get('/{slug}', 'FrontEnd\\QuizController@index')->name('quiz.login');
        Route::post('/{slug}/register', 'FrontEnd\\QuizController@registerUser')->name('quiz.registerUser');
        Route::get('/{slug}/start', 'FrontEnd\\QuizController@quizStart')->name('quiz.play');
        Route::get('/{slug}/thank-you', 'FrontEnd\\QuizController@thankYou')->name('quiz.thankYou');
        Route::get('/{slug}/dashboard', 'FrontEnd\\QuizController@dashboard')->name('quiz.dashboard');
    });
    
    Route::post('/{slug}', 'FrontEnd\\QuizController@quizStore')->name('quiz.store');
    Route::get('/{slug}/winners', 'FrontEnd\\QuizController@winners')->name('quiz.winners');
    Route::get('/{slug}/fetch-winners', 'FrontEnd\\QuizController@fetchWinners')->name('quiz.fetchWinners');
    Route::get('/{slug}/fetch-players', 'FrontEnd\\QuizController@fetchPlayers')->name('quiz.fetchPlayers');
});