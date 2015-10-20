<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

/*** Reports ***/
Route::get('/', 'ReportController@home');
Route::post('report', 'ReportController@summary');
Route::get('{accountcode}/report', 'ReportController@accountCodes');
Route::get('{phonenumber}/report', 'ReportController@phoneNumbers');

/*** Account Codes ***/
Route::get('accountcodes', 'AccountCodeController@index');
Route::post('accountcode', 'AccountCodeController@store');
Route::get('accountcode/{id}/edit', 'AccountCodeController@edit');
Route::patch('accountcode/{id}', 'AccountCodeController@update');
Route::delete('accountcode/{id}', 'AccountCodeController@destroy');

/*** Download ***/
Route::get('download/report', 'DownloadController@report');