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

    Route::get('types', 'ApiNodeTypesController@index')->name('api.nodetypes.index');
    Route::get('types/typeahead', 'ApiNodeTypesController@typeahead')->name('api.types.typeahead');
    Route::get('types/{id}', 'ApiNodeTypesController@show')->name('api.nodetypes.show');

    Route::get('nodes/{type}', 'ApiNodesController@index')->name('api.nodes.index');
    Route::get('nodes/typeahead/{type}', 'ApiNodesController@typeahead')->name('api.nodes.typeahead');
    Route::get('node/{id}', 'ApiNodesController@show')->name('api.nodes.show');

    Route::get('lists/typeahead', 'ApiListsController@typeahead')->name('api.lists.typeahead');

    Route::get('tags', 'ApiTagsController@index')->name('api.tags.index');
    Route::get('tags/{id}', 'ApiTagsController@show')->name('api.tags.show');

});