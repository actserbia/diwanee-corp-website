<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\NodeList;
use App\Validation\Validators;
use App\Utils\HtmlElementsClasses;
use App\Utils\Utils;

class AdminNodeListsController extends Controller {
    public function __construct() {
        HtmlElementsClasses::$template = 'admin';
        Utils::$modelType = 'NodeList';
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        $objects = NodeList::get();
        return view('admin.node_lists.list', compact('objects'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id) {
        $object = NodeList::findOrFail($id);
        return view('admin.node_lists.delete', compact('object'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
        $object = new NodeList;

        return view('admin.node_lists.create', compact('object'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) {
        $data = $request->all();

        Validators::nodeTypesFormValidator($data)->validate();

        $object = new NodeList;

        $successName = $object->saveObject($data) ? 'success' : 'error';

        return redirect()->route('node-lists.index')->with($successName, __('messages.store_' . $successName, ['type' => __('models_labels.NodeList.label_single'), 'name' => $object->name]));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id) {
        $object = NodeList::findOrFail($id);

        return view('admin.node_lists.edit', compact('object'));
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

        Validators::nodeTypesFormValidator($data, ['id' => $id])->validate();

        $object = NodeList::findOrFail($id);

        $successName = $object->saveObject($data) ? 'success' : 'error';

        return redirect()->route('node-lists.index')->with($successName, __('messages.update_' . $successName, ['type' => __('models_labels.NodeList.label_single'), 'name' => $object->name]));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id) {
        $object = NodeList::findOrFail($id);

        $successName = $object->deleteObject() ? 'success' : 'error';

        return redirect()->route('node-lists.index')->with($successName, __('messages.destroy_' . $successName, ['type' => __('models_labels.NodeList.label_single'), 'name' => $object->name]));
    }


    public function view($id) {
        $object = NodeList::findOrFail($id);

        return view('admin.node_lists.view', compact('object'));
    }

    public function nodeListTags(Request $request) {
        $data = $request->all();

        if(isset($data['model_type_id'])) {
            $modelType = $data['model_type_id'];
            $object = new NodeList(['model_type_id' => $data['model_type_id']]);
            return view('blocks.node-list-tags', compact('object', 'modelType'));
        }
    }
}