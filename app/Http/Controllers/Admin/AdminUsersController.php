<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use App\Constants\Role;
use App\Validation\Validators;


class AdminUsersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        $userdetail = User::withTrashed()->get()->toArray();
        return view('admin.users.users_list', compact('userdetail'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
        $roles = Role::getAllForDropdown();

        return view('admin.users.users_create', compact('roles'));
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

        $successName = $user->saveUser($data) ? 'success' : 'error';

        return redirect()->route('users.index')->with($successName, __('messages.tags.store_' . $successName, ['name' => $user->name]));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id) {
        $user = User::findOrFail($id)->toArray();

        return view('admin.users.users_delete', compact('user'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id) {
        $user = User::findOrFail($id);
        $roles = Role::getAllForDropdown();

        return view('admin.users.users_edit', compact('user', 'roles'));
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

        $successName = $user->saveUser($data) ? 'success' : 'error';

        return redirect()->route('users.index')->with($successName, __('messages.users.update_' . $successName, ['name' => $user->name]));
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

        return redirect()->route('users.index')->with($successName, __('messages.users.destroy_' . $successName, ['name' => $user->name]));
    }
}
