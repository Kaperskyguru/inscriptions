<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::namespace('Api')->group(function () {

    Route::prefix('v1')->group(function () {
        Route::prefix('auth')->group(function () {
            // Below mention routes are public, user can access those without any restriction.
            // Create New User
            Route::post('register', 'AuthController@register');
            Route::post('validateStep/{step}', 'AuthController@validateStep');
            // Login User
            Route::post('login', 'AuthController@login');
            Route::get('authenticate', 'AuthController@authenticate');

            // Send reset password mail
            Route::post('reset-password', 'AuthController@sendPasswordResetLink');
        
            // handle reset password form process
            Route::post('reset/password', 'AuthController@callResetPassword');
            
            // Refresh the JWT Token
            //Route::get('refresh', 'AuthController@refresh');
            
            // Below mention routes are available only for the authenticated users.
            Route::middleware('auth:api')->group(function () {
                // Get user info
                Route::get('user', 'AuthController@user');
                // Logout user from application
                Route::post('logout', 'AuthController@logout');
                Route::post('update', 'AuthController@update');

            });
        });
       
        Route::prefix('organization')->group(function () {
            Route::middleware('auth:api')->group(function () {
                // Organization
                Route::post('update', 'OrganizationsController@update');
            });
        });

        Route::prefix('dancers')->group(function () {
           
            Route::middleware('auth:api')->group(function () {
                Route::get('/', 'DancersController@index');
                Route::get('/check', 'DancersController@check');
                Route::post('store', 'DancersController@store');
                Route::post('search', 'DancersController@search');
                Route::post('update', 'DancersController@update');
                Route::delete('/{id}', 'DancersController@destroy');
            });
        });
        Route::prefix('routines')->group(function () {
           
            Route::middleware('auth:api')->group(function () {
                Route::get('/', 'RoutinesController@index');
                Route::post('store', 'RoutinesController@store');
                Route::get('create', 'RoutinesController@create');
                Route::get('edit/{id}', 'RoutinesController@edit');
                Route::post('update/{id}', 'RoutinesController@update');
                Route::post('uploadSong', 'RoutinesController@uploadSong');
                Route::post('subscription/{id}', 'RoutinesController@getSubscriptionRoutines');
                Route::delete('/{id}', 'RoutinesController@destroy');
            });
        });
        Route::prefix('events')->group(function () {
            Route::get('/', 'EventsController@index');
        });
        Route::prefix('statuses')->group(function () {
            Route::get('/', 'StatusesController@index');
        });
        Route::prefix('subscriptions')->group(function () {
            Route::get('/', 'SubscriptionsController@index');

            Route::middleware('auth:api')->group(function () {
                Route::post('store', 'SubscriptionsController@store');
                Route::post('update/{id}', 'SubscriptionsController@update');
                Route::get('/{id}', 'SubscriptionsController@show');
                Route::delete('/{id}', 'SubscriptionsController@destroy');

                Route::get('export/{event_name}/dancer', 'SubscriptionsController@exportSubscriptionDancer');

               
            });
        });
        Route::prefix('categories')->group(function () {
            Route::get('/', 'CategoriesController@index');
        });
        Route::prefix('levels')->group(function () {
            Route::get('/', 'LevelsController@index');
        });
        Route::prefix('styles')->group(function () {
            Route::get('/', 'StylesController@index');
        });

        Route::prefix('admin')->group(function () {

            
            Route::middleware('auth:api')->group(function () {
                Route::get('/', 'AdminController@index');
                Route::post('search', 'AdminController@search');
                Route::post('add/payment', 'AdminController@addPayment');
                Route::post('update/payment', 'AdminController@updatePayment');
                Route::delete('delete/payment/{id}', 'AdminController@deletePayment');
                Route::post('update/status', 'AdminController@updateStatus');
                Route::get('export/subscription/{subscription_id}', 'AdminController@exportSubscription');
                Route::get('export/schedule/{schedule_id}', 'SchedulesController@exportSchedule');


                Route::post('fee/add', 'FeesController@store');
                Route::post('fee/update', 'FeesController@update');
                Route::delete('fee/delete/{id}', 'FeesController@delete');

                Route::get('export/schedule/organization/{event_name}', 'SchedulesController@exportScheduleOrganizations');
                
                Route::get('export/schedule/organization/{event_name}/{organization_id}', 'SchedulesController@exportScheduleByOrganization');

                

                Route::get('export/schedule-item/{schedule_id}', 'SchedulesController@exportScheduleItem');
                Route::get('export/schedule-item/by-position/{event}', 'SchedulesController@exportScheduleOrderPosition');
                


                Route::post('schedule-item/updateAll', 'ScheduleItemsController@updateAllItems');
                Route::post('schedule-item/addToSchedule', 'ScheduleItemsController@addToSchedule');


                //Route::post('schedule-title/update/{id}', 'ScheduleTitlesController@update');

                Route::post('schedule-title/delete/{id}', 'ScheduleTitlesController@delete');


                Route::post('schedule/update/{id}', 'SchedulesController@update');

                Route::get('/{city}', 'AdminController@event');
                Route::get('/{city}/schedule', 'AdminController@schedule');
                Route::get('/{city}/schedule/get-by-position', 'AdminController@scheduleOrderByPosition');
                Route::get('/{city}/schedule/get-items', 'AdminController@scheduleGetItems');
                Route::get('/{city}/{subscription_id}', 'AdminController@subscription');



                
            });
        });

       

        // Route::prefix('export')->group(function () {
        //     Route::middleware('auth:api')->group(function () {
               
        //     });
        // });


        Route::prefix('states')->group(function () {
            Route::get('/getByCountryID/{country_id}', 'StatesController@getByCountryID');
        });
    });

});
