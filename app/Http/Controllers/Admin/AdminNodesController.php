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
        if(isset($data['node_type_id'])) {
            $nodeObject = new Node(['node_type_id' => $data['node_type_id']]);
            $objects = $nodeObject::where('node_type_id', '=', $data['node_type_id'])->get();
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
        return view('admin.nodes.new-create', compact('object'));
    }

    public function nodeFields(Request $request) {
        $data = $request->all();

        if(isset($data['node_type_id'])) {
            $nodeType = $data['node_type_id'];
            $object = new Node(['node_type_id' => $data['node_type_id']]);
        }

        return view('blocks.node-fields', compact('object', 'nodeType'));
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
            return redirect()->route('nodes.create', ['nodeType' => $data['nodeType']]);
        }
        
        Validators::nodesFormValidator($data)->validate();

        $object = new Node(['node_type_id' => $data['nodeType']]);
        
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

        return view('admin.nodes.edit', compact('object'));
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
