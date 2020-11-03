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
Route::get('/getIngredients','AdminController@getIngredients');
Route::post('/ingredients','AdminController@createIngredient');
Route::delete('/ingredients/{id}','AdminController@deleteIngredient');
Route::get('/switchAvailableIngredients/{id}','AdminController@switchAvailableIngredients');

Route::get('/getMedicaments','AdminController@getMedicaments');
Route::post('/medicaments','AdminController@createMedicament');
Route::put('/medicaments','AdminController@updateMedicament');
Route::delete('/medicaments/{id}','AdminController@deleteMedicament');

Route::post('searchMedicaments','UserController@search');
Route::get('/autocompliteIngredients/{query}','UserController@autocompliteIngredients');



