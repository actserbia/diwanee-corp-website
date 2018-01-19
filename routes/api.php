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

Route::group(['namespace' => 'Api'], function() {
    Route::get('/', 'ApiController@all')->name('api.all');

    Route::get('articles', 'ApiArticlesController@index')->name('api.articles.index');
    Route::get('articles/{id}', 'ApiArticlesController@show')->name('api.articles.show');

    Route::get('tags', 'ApiTagsController@index')->name('api.tags.index');
    Route::get('tags/{id}', 'ApiTagsController@show')->name('api.tags.show');
    
    Route::group(['middleware' => ['local.or.api.auth']], function() {
        Route::post('articles', 'ApiArticlesController@store')->name('api.articles.store');
        Route::put('articles/{id}', 'ApiArticlesController@update')->name('api.articles.update');
        Route::delete('articles/{id}', 'ApiArticlesController@destroy')->name('api.articles.destroy');

        Route::post('upload-image', 'ApiImagesController@uploadImage')->name('api.upload.image');

        Route::post('tags', 'ApiTagsController@store')->name('api.tags.store');
        Route::put('tags/{id}', 'ApiTagsController@update')->name('api.tags.update');
        Route::delete('tags/{id}', 'ApiTagsController@destroy')->name('api.tags.destroy');
    });
    
    Route::get('/search/articles', 'ApiSearchController@articles')->name('api.search.articles');
    Route::get('/search/tags', 'ApiSearchController@tags')->name('api.search.tags');
});
