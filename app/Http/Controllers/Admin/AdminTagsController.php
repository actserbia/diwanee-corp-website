<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Tag;
use App\Validation\Validators;
use App\Utils\HtmlElementsClasses;

class AdminTagsController extends Controller {
    public function __construct() {
        HtmlElementsClasses::$template = 'admin';
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        $tags = Tag::get();
        return view('admin.tags.list', compact('tags'));
    }
    
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id) {
        $tag = Tag::findOrFail($id);
        return view('admin.tags.delete', compact('tag'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
        $object = new Tag;
        
        return view('admin.tags.create', compact('object'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) {
        $data = $request->all();

        Validators::tagsFormValidator($data)->validate();

        $tag = new Tag;
        
        $successName = $tag->saveTag($data) ? 'success' : 'error';
        
        return redirect()->route('tags.index')->with($successName, __('messages.store_' . $successName, ['type' => 'tag', 'name' => $tag->name]));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id) {
        $object = Tag::findOrFail($id);

        return view('admin.tags.edit', compact('object'));
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

        Validators::tagsFormValidator($data, ['id' => $id])->validate();

        $tag = Tag::findOrFail($id);
        
        $successName = $tag->saveTag($data) ? 'success' : 'error';
        
        return redirect()->route('tags.index')->with($successName, __('messages.update_' . $successName, ['type' => 'tag', 'name' => $tag->name]));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id) {
        $tag = Tag::findOrFail($id);
        
        $successName = $tag->delete() ? 'success' : 'error';
        
        return redirect()->route('tags.index')->with($successName, __('messages.destroy_' . $successName, ['type' => 'tag', 'name' => $tag->name]));
    }
}
