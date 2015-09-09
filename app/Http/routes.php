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



/**
 *  / - show date picker.
 *  report - list top ten account codes with the most calls for the selected date range.
 *  report/{account-code} - list all calls for the selected account code within the specifed date range.
 *
 */

Route::get('/', 'ReportController@index');
Route::post('report', 'ReportController@report');
Route::get('report/{account-code}', 'ReportController@details');