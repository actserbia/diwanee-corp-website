<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\TagType;
use App\Validation\Validators;
use App\Utils\HtmlElementsClasses;

class AdminTagTypesController extends Controller {
    public function __construct() {
        HtmlElementsClasses::$template = 'admin';
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        $tagTypes = TagType::get();
        return view('admin.tag_types.list', compact('tagTypes'));
    }
    
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id) {
        $tagType = TagType::findOrFail($id);
        return view('admin.tag_types.delete', compact('tagType'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
        $object = new TagType;
        
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

        $tagType = new TagType;
        
        $successName = $tagType->saveTagType($data) ? 'success' : 'error';
        
        return redirect()->route('tag-types.index')->with($successName, __('messages.store_' . $successName, ['type' => 'tag type', 'name' => $tagType->name]));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id) {
        $object = TagType::findOrFail($id);

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

        $tagType = TagType::findOrFail($id);
        
        $successName = $tagType->saveTagType($data) ? 'success' : 'error';
        
        return redirect()->route('tag-types.index')->with($successName, __('messages.update_' . $successName, ['type' => 'tag type', 'name' => $tagType->name]));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id) {
        $tagType = TagType::findOrFail($id);
        
        $successName = $tagType->delete() ? 'success' : 'error';
        
        return redirect()->route('tag-types.index')->with($successName, __('messages.destroy_' . $successName, ['type' => 'tag type', 'name' => $tagType->name]));
    }
}
