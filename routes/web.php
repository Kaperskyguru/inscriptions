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

// Route::get('/', 'SpaController@redirect');
// Route::get('/login', 'SpaController@redirect');



//Route::get('/{any}', 'SpaController@index')->where('any', '.*');
//Route::get('/quickbooks', 'QuickbooksController@index');
Route::get('/quickbooks/callback', 'QuickbooksController@callback');
Route::get('/services/schedules/set-order/{schedule_id}', 'ServicesController@setOrder');
Route::get('/services/schedules/title/clean', 'ServicesController@cleanScheduleTitle');
Route::get('/services/clear-cache', 'ServicesController@clearCache');
Route::get('/services/storage-link', 'ServicesController@storageLink');

Route::get('/get-last-modification', 'SpaController@getLastModification');
Route::get('/{any?}', 'SpaController@index')->where('any', '^(?!storage\/)[\/\w\.-]*');
