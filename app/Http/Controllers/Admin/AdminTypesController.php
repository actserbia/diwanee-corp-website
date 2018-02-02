<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Type;
use App\Validation\Validators;
use App\Utils\HtmlElementsClasses;

class AdminTypesController extends Controller {
    public function __construct() {
        HtmlElementsClasses::$template = 'admin';
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        $types = Type::get();
        return view('admin.types.list', compact('types'));
    }
    
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id) {
        $type = Type::findOrFail($id);
        return view('admin.types.delete', compact('type'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
        $object = new Type;
        
        return view('admin.types.create', compact('object'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) {
        $data = $request->all();

        //Validators::typesFormValidator($data)->validate();

        $type = new Type;
        
        $successName = $type->saveType($data) ? 'success' : 'error';
        
        return redirect()->route('types.index')->with($successName, __('messages.store_' . $successName, ['type' => 'type', 'name' => $type->name]));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id) {
        $object = Type::findOrFail($id);

        return view('admin.types.edit', compact('object'));
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

        Validators::typesFormValidator($data, ['id' => $id])->validate();

        $type = Type::findOrFail($id);
        
        $successName = $type->saveType($data) ? 'success' : 'error';
        
        return redirect()->route('types.index')->with($successName, __('messages.update_' . $successName, ['type' => 'type', 'name' => $type->name]));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id) {
        $type = Type::findOrFail($id);
        
        $successName = $type->delete() ? 'success' : 'error';
        
        return redirect()->route('types.index')->with($successName, __('messages.destroy_' . $successName, ['type' => 'type', 'name' => $type->name]));
    }
}
