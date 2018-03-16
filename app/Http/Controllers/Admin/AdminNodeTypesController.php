<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\NodeType;
use App\Validation\Validators;
use App\Utils\HtmlElementsClasses;
use App\Utils\Utils;
use App\Models\ModelsUtils;
use Illuminate\Support\Facades\Route;

class AdminNodeTypesController extends Controller {
    public function __construct() {
        $routeParams = Route::current()->parameters();
        if(isset($routeParams['node_type'])) {
            if(ModelsUtils::checkIfNodeTypeIdIsInPredefinedTypesList($routeParams['node_type'])) {
                abort(403, __('messages.unauthorized_action'));
                return redirect('/');
            }
        }
        
        HtmlElementsClasses::$template = 'admin';
        Utils::$modelType = 'NodeType';
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        $objects = NodeType::get();
        return view('admin.node_types.list', compact('objects'));
    }
    
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id) {
        $object = NodeType::findOrFail($id);
        return view('admin.node_types.delete', compact('object'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
        $object = new NodeType;
        
        return view('admin.node_types.create', compact('object'));
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

        $object = new NodeType;
        
        $successName = $object->saveObject($data) ? 'success' : 'error';
        
        return redirect()->route('node-types.index')->with($successName, __('messages.store_' . $successName, ['type' => __('models_labels.NodeType.label_single'), 'name' => $object->name]));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id) {
        $object = NodeType::findOrFail($id);
        
        return view('admin.node_types.edit', compact('object'));
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

        $object = NodeType::findOrFail($id);
        
        $successName = $object->saveObject($data) ? 'success' : 'error';
        
        return redirect()->route('node-types.index')->with($successName, __('messages.update_' . $successName, ['type' => __('models_labels.NodeType.label_single'), 'name' => $object->name]));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id) {
        $object = NodeType::findOrFail($id);
        
        $successName = $object->delete() ? 'success' : 'error';
        
        return redirect()->route('node-types.index')->with($successName, __('messages.destroy_' . $successName, ['type' => __('models_labels.NodeType.label_single'), 'name' => $object->name]));
    }
}
