<?php

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

Auth::routes();

Route::get('/', 'HomeController@index')->name('home');

Route::resource('articles', 'ArticleController', ['only' => ['index', 'show']]);

Route::post('sirtrevor/upload-image', 'ImagesController@uploadSirTrevorImage')->name('sirtrevor.upload.image');

//only admin can access
Route::group(['prefix' => 'admin', 'middleware' => 'admin', 'namespace' => 'Admin'], function() {
    Route::resource('users', 'UsersController');
});

Route::group(['prefix' => 'admin', 'middleware' => 'App\Http\Middleware\EditorMiddleware', 'namespace' => 'Admin'], function(){
    Route::get('/', 'DashboardController@index');

    Route::resource('tags', 'TagsController');
    Route::resource('articles', 'ArticlesController');
    //Route::resource('users', 'UsersController');
});

Route::group(['prefix' => 'admin', 'namespace' => 'Auth'],function(){
    // Authentication Routes...
    Route::get('login', 'LoginController@showLoginForm')->name('login');
    Route::post('login', 'LoginController@login');
    Route::post('logout', 'LoginController@logout')->name('logout');

    // Password Reset Routes...
    Route::get('password/reset', 'ForgotPasswordController@showLinkRequestForm')->name('password.reset');
    Route::post('password/email', 'ForgotPasswordController@sendResetLinkEmail')->name('password.email');
    Route::get('password/reset/{token}', 'ResetPasswordController@showResetForm')->name('password.reset.token');
    Route::post('password/reset', 'ResetPasswordController@reset');
});


Route::group(['prefix' => 'ajax'], function() {
    Route::get('/subcategories/{category_id?}', 'AjaxController@subcategories')->name('subcategories');
    Route::get('/tags/{type}', 'AjaxController@tagsByType')->name('tags.by.type');
});