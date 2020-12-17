<?php
use Illuminate\Support\Facades\Route;

// Route::get('greeting', function () {
//     return 'Hi, this is your awesome package!';
// });

// Route::get('test', 'EdgeWizz\Fillup\Controllers\FillupController@test')->name('test');

Route::post('dad/store', 'EdgeWizz\Dad\Controllers\DadController@store')->name('fmt.dad.store');
Route::post('dad/update/{id}', 'EdgeWizz\Dad\Controllers\DadController@update')->name('fmt.dad.update');
Route::any('dad/delete/{id}', 'EdgeWizz\Dad\Controllers\DadController@delete')->name('fmt.dad.delete');

Route::post('fmt/Dad/uploadFile', 'EdgeWizz\Dad\Controllers\DadController@uploadFile')->name('fmt.dad.csv');

Route::any('fmt/dad/inactive/{id}',  'EdgeWizz\Dad\Controllers\DadController@inactive')->name('fmt.dad.inactive');
Route::any('fmt/dad/active/{id}',  'EdgeWizz\Dad\Controllers\DadController@active')->name('fmt.dad.active');