<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Tag;
use App\Constants\TagType;
use App\Validators\Validators;

//use Thumbor\Url\BuilderFactory;

class TagsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        $tags = Tag::get()->toArray();
        return view('admin.tags.tags_list', compact('tags'));
    }
    
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id) {
        $tag = Tag::findOrFail($id)->toArray();
        return view('admin.tags.tags_delete', compact('tag'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
        /*$imagesConfig = config('images');
        $thumbnailUrlFactory = BuilderFactory::construct($imagesConfig['server'], $imagesConfig['secret']);
            
        $settings = array();
        $settings['width'] = 200;
        $settings['height'] = 100;
        $imageUrl =  $imagesConfig['imagesUrl'] . 'test.jpg';
        $image = $thumbnailUrlFactory->url($imageUrl)->resize($settings['width'], $settings['height'])->smartCrop(true);*/
      
        $types = TagType::all;
        
        return view('admin.tags.tags_create', compact('types'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) {
        $data = $request->all();

        $validator = Validators::tagsFormValidator($data, ['id' => 0]);
        if ($validator->fails()) {
            return back()->withInput()->withErrors($validator);
        }

        $tag = new Tag;
        
        $successName = $tag->saveTag($data) ? 'success' : 'error';
        
        return redirect()->route('tags.index')->with($successName, __('messages.tags.store_' . $successName, ['name' => $tag->name]));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id) {
        $tag = Tag::findOrFail($id);
        
        $types = TagType::all;

        $parentsByType = array();
        $parentsByType['subcategory'] = 'category';
        $parentsList = isset($parentsByType[$tag->type]) ? Tag::where('type', '=', $parentsByType[$tag->type])->get() : null;
        
        $childrenByType = array();
        $childrenByType['category'] = 'subcategory';
        $childrenList = isset($childrenByType[$tag->type]) ? Tag::where('type', '=', $childrenByType[$tag->type])->get() : null;

        return view('admin.tags.tags_edit', compact('tag', 'types', 'parentsList', 'childrenList'));
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

        $validator = Validators::tagsFormValidator($data, ['id' => $id]);
        if ($validator->fails()) {
            return back()->withInput()->withErrors($validator);
        }

        $tag = Tag::findOrFail($id);
        
        $successName = $tag->saveTag($data) ? 'success' : 'error';
        
        return redirect()->route('tags.index')->with($successName, __('messages.tags.update_' . $successName, ['name' => $tag->name]));
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
        
        return redirect()->route('tags.index')->with($successName, __('messages.tags.destroy_' . $successName, ['name' => $tag->name]));
    }
}
