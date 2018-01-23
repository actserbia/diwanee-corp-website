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

  $locale = Request::segment(1);
  if(in_array($locale, config('app.locales'))) {
      Route::group(['prefix' => $locale], function() use($locale) {
          app()->setLocale($locale);
          setAllWebRoutes();
      });
  } else {
      setAllWebRoutes();
  }


  function setAllWebRoutes() {
      Auth::routes();

      Route::get('/', 'HomeController@index')->name('home');
      
      Route::group(['middleware' => 'auth'], function() {
          Route::get('profile', 'UsersController@profile')->name('profile');
          Route::put('profile', 'UsersController@updateProfile')->name('profile.update');
          Route::get('graphiql','GraphiqlController@index')->name('graphiql');
      });
      
      Route::group(['prefix' => 'admin', 'middleware' => 'admin', 'namespace' => 'Admin'], function() {
          Route::resource('users', 'AdminUsersController');
      });
      
      Route::group(['prefix' => 'admin', 'middleware' => 'editor', 'namespace' => 'Admin'], function() {
          Route::get('/', 'DashboardController@index')->name('admin.home');
          
          Route::resource('tags', 'AdminTagsController');
          Route::resource('tag-types', 'AdminTagTypesController');
      });
      
      Route::group(['prefix' => 'ajax'], function() {
          Route::get('/model/populate-field', 'AjaxController@modelPopulateField')->name('model.populate.field');
          Route::get('/model/add-selected-item', 'AjaxController@modelAddSelectedItem')->name('model.add.selected.item');
      });

  }