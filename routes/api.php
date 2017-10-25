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

Route::get('/', 'ApiController@all')->name('api.all');

Route::get('articles', 'ArticleController@index')->name('api.articles.index');
Route::get('articles/{id}', 'ArticleController@show')->name('api.articles.show');

Route::get('tags', 'TagController@index')->name('api.tags.index');
Route::get('tags/{id}', 'TagController@show')->name('api.tags.show');

Route::group(['middleware' => ['local.or.api.auth']], function() {
    Route::post('articles', 'ArticleController@store')->name('api.articles.store');
    Route::put('articles/{id}', 'ArticleController@update')->name('api.articles.update');
    Route::delete('articles/{id}', 'ArticleController@destroy')->name('api.articles.destroy');

    Route::post('upload-image', 'ImagesController@uploadImage')->name('api.upload.image');

    Route::post('tags', 'TagController@store')->name('api.tags.store');
    Route::put('tags/{id}', 'TagController@update')->name('api.tags.update');
    Route::delete('tags/{id}', 'TagController@destroy')->name('api.tags.destroy');
});
