<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\FieldType;
use App\Constants\FieldTypeCategory;
use App\Validation\Validators;
use App\Utils\HtmlElementsClasses;
use App\Utils\Utils;

class AdminTagTypesController extends Controller {
    public function __construct() {
        HtmlElementsClasses::$template = 'admin';
        Utils::$modelType = 'TagType';
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        $objects = FieldType::where('category', '=', FieldTypeCategory::Tag)->get();
        return view('admin.tag_types.list', compact('objects'));
    }
    
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id) {
        $object = FieldType::findOrFail($id);
        return view('admin.tag_types.delete', compact('object'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
        $object = new FieldType;
        
        return view('admin.tag_types.create', compact('object'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) {
        $data = $request->all();

        Validators::tagTypesFormValidator($data)->validate();
        
        $object = new FieldType;
        
        $data['category'] = FieldTypeCategory::Tag;
        $successName = $object->saveObject($data) ? 'success' : 'error';
        
        return redirect()->route('tag-types.index')->with($successName, __('messages.store_' . $successName, ['type' => 'tag type', 'name' => $object->name]));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id) {
        $object = FieldType::findOrFail($id);

        return view('admin.tag_types.edit', compact('object'));
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

        Validators::tagTypesFormValidator($data, ['id' => $id])->validate();

        $object = FieldType::findOrFail($id);
        
        $successName = $object->saveObject($data) ? 'success' : 'error';
        
        return redirect()->route('tag-types.index')->with($successName, __('messages.update_' . $successName, ['type' => 'tag type', 'name' => $object->name]));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id) {
        $object = FieldType::findOrFail($id);
        
        $successName = $object->deleteObject() ? 'success' : 'error';
        
        return redirect()->route('tag-types.index')->with($successName, __('messages.destroy_' . $successName, ['type' => 'tag type', 'name' => $object->name]));
    }
}
