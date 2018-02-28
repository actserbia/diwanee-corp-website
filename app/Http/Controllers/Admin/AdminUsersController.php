<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use App\Validation\Validators;
use App\Utils\HtmlElementsClasses;
use App\Utils\Utils;

class AdminUsersController extends Controller {
    public function __construct() {
        HtmlElementsClasses::$template = 'admin';
        Utils::$modelType = 'User';
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        $userdetail = User::withTrashed()->get();
        return view('admin.users.list', compact('userdetail'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
        $object = new User;

        return view('admin.users.create', compact('object'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) {
        $data = $request->all();

        Validators::usersFormValidator($data)->validate();

        $user = new User;

        $successName = $user->saveObject($data) ? 'success' : 'error';

        return redirect()->route('users.index')->with($successName, __('messages.store_' . $successName, ['type' => __('models_labels.User.label_single'), 'name' => $user->name]));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id) {
        $user = User::findOrFail($id)->toArray();

        return view('admin.users.delete', compact('user'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id) {
        $object = User::findOrFail($id);

        return view('admin.users.edit', compact('object'));
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

        Validators::usersFormValidator($data, ['id' => $id])->validate();

        $user = User::findOrFail($id);

        $successName = $user->saveObject($data) ? 'success' : 'error';

        return redirect()->route('users.index')->with($successName, __('messages.update_' . $successName, ['type' => __('models_labels.User.label_single'), 'name' => $user->name]));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id) {
        $user = User::findOrFail($id);

        $successName = $user->delete() ? 'success' : 'error';

        return redirect()->route('users.index')->with($successName, __('messages.destroy_' . $successName, ['type' => __('models_labels.User.label_single'), 'name' => $user->name]));
    }
}
