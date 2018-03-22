<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Tag;
use App\Validation\Validators;
use App\Utils\HtmlElementsClasses;
use App\Utils\Utils;
use App\Constants\Settings;

class AdminTagsController extends Controller {
    public function __construct() {
        HtmlElementsClasses::$template = 'admin';
        Utils::$modelType = 'Tag';
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request) {
        $data = $request->all();

        if(isset($data['tag_type_id'])) {
            $object = new Tag(['model_type_id' => $data['tag_type_id']]);
            $tags = Tag::has('parents', '=', '0')->filterByModelType($data['tag_type_id'])->get();
        } else {
            $object = new Tag;
            $tags = [];
        }

        return view('admin.tags.list', compact('object', 'tags'));
    }

    public function tagsReorder(Request $request) {
        $data = $request->all();
        
        if(Tag::tagsListMaxLevelsCount($data['tags']) > Settings::MaximumTagsLevelsCount) {
            $class = 'error';
            $message = __('messages.check_tags_list_max_level');
        } else {
            $class = Tag::reorder($data['tags']) ? 'success' : 'error';
            $message = __('messages.tags_reorder_' . $class);
        }

        return view('blocks.alert', compact('class', 'message'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id) {
        $object = Tag::findOrFail($id);
        if(count($object->children) === 0) {
            return view('admin.tags.delete', compact('object'));
        } else {
            return view('admin.tags.not_delete', compact('object'));
        }
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

        $object = new Tag;
        
        $successName = $object->saveObject($data) ? 'success' : 'error';
        
        return redirect()->route('tags.index', ['tag_type_id' => $object->tag_type->id])->with($successName, __('messages.store_' . $successName, ['type' => __('models_labels.Tag.label_single'), 'name' => $object->name]));
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

        $object = Tag::findOrFail($id);
        
        $successName = $object->saveObject($data) ? 'success' : 'error';
        
        return redirect()->route('tags.index', ['tag_type_id' => $object->tag_type->id])->with($successName, __('messages.update_' . $successName, ['type' => __('models_labels.Tag.label_single'), 'name' => $object->name]));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id) {
        $object = Tag::findOrFail($id);
        
        $successName = $object->delete() ? 'success' : 'error';
        
        return redirect()->route('tags.index', ['tag_type_id' => $object->tag_type->id])->with($successName, __('messages.destroy_' . $successName, ['type' => __('models_labels.Tag.label_single'), 'name' => $object->name]));
    }
}
