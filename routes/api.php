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

Route::post('register', 'Api\AuthController@register');
Route::post('login', 'Api\AuthController@login');

//اي شي داخل القروب هذا بيكتب قبل الراوت الاسم الي داخل البريفكس
Route::middleware('api:auth')->prefix('user')->group(function() {
    Route::post('update/password', 'Api\UserController@updatePassword');
    Route::post('update/profile', 'Api\UserController@updatePassword');
});


Route::group(['middleware' => ['auth:api']], function () {  //this auth is laravel default auth
    Route::resource('categories', 'Api\CategoryController');
    Route::resource('recipes', 'Api\RecipeController');
    Route::resource('recipe-steps', 'Api\RecipeStepController');
    Route::resource('ingredients', 'Api\IngredientController');

    Route::post('do-like', 'Api\LikeController@doLike');
    Route::post('do-favorite', 'Api\FavoriteController@doFavorite');

    Route::get('liked-recipies', 'Api\LikeController@getLikedRecipes');
    Route::get('favorite-recipies', 'Api\FavoriteController@getFavoriteRecipes');
});


Route::get('categories', 'Api\CategoryController@index');

Route::get('recipes', 'Api\RecipeController@index');

Route::get('recipe-steps', 'Api\RecipeStepController@index');

Route::get('ingredients', 'Api\IngredientController@index');
