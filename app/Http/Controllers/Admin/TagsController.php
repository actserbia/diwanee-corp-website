<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Tag;
use App\Constants\TagType;
use Illuminate\Support\Facades\Validator;

use Thumbor\Url\BuilderFactory;

class TagsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $tags = Tag::get()->toArray();
        return view('admin.tags.tags_list', compact('tags'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $types = TagType::populateTypes();
        
        $imagesConfig = config('images');
        $thumbnailUrlFactory = BuilderFactory::construct($imagesConfig['server'], $imagesConfig['secret']);
            
        $settings = array();
        $settings['width'] = 200;
        $settings['height'] = 100;
        $imageUrl =  $imagesConfig['imagesUrl'] . 'test.jpg';
        $image = $thumbnailUrlFactory->url($imageUrl)->resize($settings['width'], $settings['height'])->smartCrop(true);
        
        return view('admin.tags.tags_create', ['types' => $types, 'image' => $image]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $request->all();

        $validator = $this->validator($data, 0);
        if ($validator->fails()) {
            return back()->withInput()->withErrors($validator);
        }

        $tag = new Tag;
        $tag->saveTag($data);

        return redirect()->route('tags.index')->with('success', "The tag <strong>" . $tag->name . "</strong> has successfully been created.");
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $tag = Tag::findOrFail($id)->toArray();
        return view('admin.tags.tags_delete', ['tag' => $tag]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $tag = Tag::findOrFail($id);

        $types = TagType::populateTypes();

        $parentsByType = array();
        $parentsByType['subcategory'] = 'category';
        $parentsList = isset($parentsByType[$tag->type]) ? Tag::where('type', '=', $parentsByType[$tag->type])->get() : null;

        $childrenByType = array();
        $childrenByType['category'] = 'subcategory';
        $childrenList = isset($childrenByType[$tag->type]) ? Tag::where('type', '=', $childrenByType[$tag->type])->get() : null;

        return view('admin.tags.tags_edit', ['tag' => $tag, 'types' => $types, 'parentsList' => $parentsList, 'childrenList' => $childrenList]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $data = $request->all();

        $validator = $this->validator($data, $id);
        if ($validator->fails()) {
            return back()->withInput()->withErrors($validator);
        }

        $tag = Tag::find($id);
        $tag->saveTag($data);

        return redirect()->route('tags.index')->with('success', "The tag <strong>" . $tag->name . "</strong> has successfully been updated.");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $tag = Tag::find($id);
        $tag->delete();
        return redirect()->route('tags.index')->with('success', "The tag <strong>" . $tag->name . "</strong> has successfully been archived.");
    }

    private function validator(array $data, $id) {
        return Validator::make($data, [
            'name' => 'required|unique:tags,id,' . $id . '|max:255',
            'type' => 'required|exists:tags,type',
            'parents.*' => 'exists:tags,id|checkTagType:category',
            'children.*' => 'exists:tags,id|checkTagType:subcategory',
        ]);
    }
}
