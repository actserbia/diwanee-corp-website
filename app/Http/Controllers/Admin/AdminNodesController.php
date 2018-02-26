<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Node;
use App\Validation\Validators;
use App\Utils\HtmlElementsClasses;
use App\Utils\Utils;

class AdminNodesController extends Controller {
    public function __construct() {
        HtmlElementsClasses::$template = 'admin';
        Utils::$modelType = 'Node';
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        $object = new Node;
        return view('admin.nodes.list', compact('object'));
    }
    
    public function nodesList(Request $request) {
        $data = $request->all();

        $objects = [];
        if(isset($data['model_type_id'])) {
            $objects = Node::filterByModelType($data['model_type_id'])->get();
        }

        return view('blocks.nodes-list', compact('objects'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id) {
        $object = Node::findOrFail($id);
        return view('admin.nodes.delete', compact('object'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
        $object = new Node;
        return view('admin.nodes.create', compact('object'));
    }

    public function nodeFields(Request $request) {
        $data = $request->all();

        if(isset($data['model_type_id'])) {
            $modelType = $data['model_type_id'];
            $object = new Node(['model_type_id' => $data['model_type_id']]);
            $stFields = $object->modelType->getSTFieldsArray();
            $stReqFields = $object->modelType->getRequiredSTFieldsArray();
            return view('blocks.node-fields', compact('object', 'modelType', 'stFields', 'stReqFields'));
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) {
        $data = $request->all();
        
        if(isset($data['firstStep'])) {
            return redirect()->route('nodes.create', ['modelType' => $data['modelType']]);
        }
        
        Validators::nodesFormValidator($data)->validate();

        $object = new Node(['model_type_id' => $data['model_type']]);
        
        $successName = $object->saveObject($data) ? 'success' : 'error';
        
        return redirect()->route('nodes.index')->with($successName, __('messages.store_' . $successName, ['type' => 'node', 'name' => $object->name]));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id) {
        $object = Node::findOrFail($id);
        $stFields = $object->model_type->getSTFieldsArray();
        $stReqFields = $object->model_type->getRequiredSTFieldsArray();

        return view('admin.nodes.edit', compact('object', 'stFields', 'stReqFields'));
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
        
        Validators::nodesFormValidator($data, ['id' => $id])->validate();

        $object = Node::findOrFail($id);
        
        $successName = $object->saveObject($data) ? 'success' : 'error';
        
        return redirect()->route('nodes.index')->with($successName, __('messages.update_' . $successName, ['type' => 'node', 'name' => $object->name]));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id) {
        $object = Node::findOrFail($id);
        
        $successName = $object->delete() ? 'success' : 'error';
        
        return redirect()->route('nodes.index')->with($successName, __('messages.destroy_' . $successName, ['type' => 'node', 'name' => $object->name]));
    }

}
