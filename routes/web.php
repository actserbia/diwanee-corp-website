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
use App\Search\NodesRepository;


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

      Route::post('sirtrevor/upload-image', 'ImagesController@uploadSirTrevorImage')->name('sirtrevor.upload.image');

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

          Route::get('/search', function (NodesRepository $repository) {
              $nodes = $repository->search((string) request('q'));

              return view('search', [
                  'nodes' => $nodes,
              ]);
          });
          
          Route::resource('tags', 'AdminTagsController');
          Route::match(['get', 'post'], 'tags-reorder-tags', 'AdminTagsController@tagsReorder')->name('tags-reorder-tags');
          
          Route::resource('tag-types', 'AdminTagTypesController');
          Route::resource('node-types', 'AdminNodeTypesController');
          Route::resource('fields', 'AdminFieldsController');

          Route::resource('node-lists', 'AdminNodeListsController');
          Route::get('node-lists/{id}/view', 'AdminNodeListsController@view')->name('node-lists.view');
          Route::get('node-list-tags', 'AdminNodeListsController@nodeListTags')->name('node-list.tags');
          
          Route::get('/model/add-relation-item', 'ModelController@modelAddRelationItem')->name('model.add-relation-item');
          Route::get('/model/populate-field', 'ModelController@modelPopulateField')->name('model.populate-field');
          Route::get('/model/tags-parenting/add-tag-subtags', 'ModelController@modelTagsParentingAddTagSubtags')->name('model.tags-parenting.add-tag-subtags');
          Route::get('/model/tag/get-children', 'ModelController@modelGetTagChildren')->name('model.tag.get-children');
          Route::get('/model/add-checkbox', 'ModelController@modelAddCheckbox')->name('model.add-checkbox');
          Route::get('/model/typeahead/diwanee-element/items', 'ModelController@typeaheadDiwaneeElementItems')->name('model.typeahead.diwanee-element-items');
          Route::get('/model/typeahead/diwanee-element/items-filters', 'ModelController@typeaheadDiwaneeElementItemsFilters')->name('model.typeahead.diwanee-element-items-filters');
          Route::get('/model/typeahead/model-relation-items', 'ModelController@typeaheadModelRelationItems')->name('model.typeahead.model-relation-items');
          Route::get('/model/add-new-relation-item', 'ModelController@modelAddNewRelationItem')->name('model.add-new-relation-item');
          
          Route::resource('nodes', 'AdminNodesController');
          
          Route::group(['prefix' => 'search'], function() {
              Route::match(['get', 'post'], 'users', 'AdminSearchController@users')->name('admin.search.users');
              Route::match(['get', 'post'], 'tags', 'AdminSearchController@tags')->name('admin.search.tags');
              Route::match(['get', 'post'], 'nodes', 'AdminSearchController@nodes')->name('admin.search.nodes');
              Route::match(['get', 'post'], 'elements', 'AdminSearchController@elements')->name('admin.search.elements');
              Route::match(['get', 'post'], 'node-lists', 'AdminSearchController@nodeLists')->name('admin.search.node-lists');

              Route::get('typeahead', 'AdminSearchController@typeahead')->name('admin.search.typeahead');
              Route::get('add-filter', 'AdminSearchController@searchAddFilter')->name('admin.search.add.filter');
              Route::get('add-input', 'AdminSearchController@searchAddInput')->name('admin.search.add.input');
              Route::get('nodes-list', 'AdminSearchController@nodesList')->name('admin.search.nodes.list');
          });
          
          Route::group(['prefix' => 'statistics'], function() {
              Route::match(['get', 'post'], 'nodes', 'AdminStatisticsController@nodes')->name('admin.statistics.nodes');
              Route::match(['get', 'post'], 'elements', 'AdminStatisticsController@elements')->name('admin.statistics.elements');
              Route::match(['get', 'post'], 'tags', 'AdminStatisticsController@tags')->name('admin.statistics.tags');
              Route::match(['get', 'post'], 'users', 'AdminStatisticsController@users')->name('admin.statistics.users');
              
              Route::get('items-list', 'AdminStatisticsController@itemsList')->name('admin.statistics.items.list');
          });
      });
  }