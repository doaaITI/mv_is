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


Route::group(['prefix' => 'auth'], function () {
    Route::post('login', 'APIController@login');
    Route::post('signup', 'APIController@signup');

    Route::group(['middleware' => 'auth:api'], function() {
        Route::get('logout', 'APIController@logout');
        Route::get('user', 'APIController@user');


        Route::get('childs/{id}', 'APIController@childCategories');

        Route::get('category/articles/{id}', 'APIController@findCateogryArticles');

        Route::post('add/category','APIController@addCategory');

        Route::post('add/article','APIController@addArticle');

        Route::put('update/category/{id}','APIController@updateCategory');
        Route::put('update/article/{id}','APIController@updateArticle');

        Route::get('all/categories','APIController@AllCategories');
    });
});
