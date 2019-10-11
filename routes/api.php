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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

//list Students
Route::get('students/{perList}', 'StudentController@index');

//list single Student
Route::get('student/{id}', 'StudentController@show');
  
//create new Student
Route::post('student', 'StudentController@store');


//update Student
Route::put('student', 'StudentController@store');

//delete Student
Route::delete('student/{id}', 'StudentController@destroy');
