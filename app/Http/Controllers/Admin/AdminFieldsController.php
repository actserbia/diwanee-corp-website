<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Field;
use App\Constants\FieldTypeCategory;
use App\Validation\Validators;
use App\Utils\HtmlElementsClasses;
use App\Utils\Utils;
use App\Models\ModelsUtils;
use Illuminate\Support\Facades\Route;

class AdminFieldsController extends Controller {
    public function __construct() {
        $routeParams = Route::current()->parameters();
        if(isset($routeParams['field'])) {
            if(ModelsUtils::checkIfFieldIdIsInPredefinedFieldsList($routeParams['field'])) {
                abort(403, __('messages.unauthorized_action'));
                return redirect('/');
            }
        }
        
        HtmlElementsClasses::$template = 'admin';
        Utils::$modelType = 'Field';
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        $objects = Field::filter(['field_type.category' => [FieldTypeCategory::Attribute]])->get();
        return view('admin.fields.list', compact('objects'));
    }
    
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id) {
        $field = Field::findOrFail($id);
        return view('admin.fields.delete', compact('field'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
        $object = new Field;
        
        return view('admin.fields.create', compact('object'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) {
        $data = $request->all();

        Validators::fieldsFormValidator($data)->validate();

        $object = new Field;
        
        $successName = $object->saveObject($data) ? 'success' : 'error';
        
        return redirect()->route('fields.index')->with($successName, __('messages.store_' . $successName, ['type' => 'field', 'name' => $object->title]));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id) {
        $object = Field::findOrFail($id);

        return view('admin.fields.edit', compact('object'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id) {
        $data = $request->all();

        Validators::fieldsFormValidator($data, ['id' => $id])->validate();

        $object = Field::findOrFail($id);
        
        $successName = $object->saveObject($data) ? 'success' : 'error';
        
        return redirect()->route('fields.index')->with($successName, __('messages.update_' . $successName, ['type' => 'field', 'name' => $object->title]));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id) {
        $object = Field::findOrFail($id);
        
        $successName = $object->delete() ? 'success' : 'error';
        
        return redirect()->route('fields.index')->with($successName, __('messages.destroy_' . $successName, ['type' => 'field', 'name' => $object->title]));
    }
}
