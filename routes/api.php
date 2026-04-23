<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

/*Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
// Route::middleware(['auth'])->group(function () {
    // user
    Route::get('usersforrole', 'NovoAppController@userForRole')->name('usersForRole');
    // Quotation
    Route::get('/cot', 'NovoAppController@quotation');
    Route::get('showquotation/{id}', 'NovoAppController@showQuotation');
    // Negotiation
    Route::get('neg', 'NovoAppController@negotiation')->name('negotiation');
    Route::get('shownegotiation/{id}', 'NovoAppController@showNegotiation')->name('showNegotiation');
    // Others
    Route::get('scalename', 'NovoAppController@scalename')->name('scaleName');
    Route::get('conceptname', 'NovoAppController@conceptName')->name('conceptName');
// });
Route::group(['prefix' => 'v1'], function () {
    Route::post('/login', 'NovoAppController@login');
    Route::post('/register', 'NovoAppController@register');
    Route::get('/logout', 'NovoAppController@logout')->middleware('auth:api');
    // Quotation
    Route::post('/cot', 'NovoAppController@quotation');
    Route::get('/showquotation/{id}', 'NovoAppController@showquotation');
    Route::post('/producfordetail', 'NovoAppController@producForDetail');
    // Negotiation
    Route::post('/neg', 'NovoAppController@negotiation');
    Route::post('/authorizenegoty', 'NovoAppController@authorizeNegotiation');
    Route::get('shownegotiation/{id}', 'NovoAppController@showNegotiation');
});*/
