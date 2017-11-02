<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;
use App\Validation\Validators;

class UsersController extends Controller
{
    public function profile() {
        return view('profile');
    }
    
    public function updateProfile(Request $request) {
        $user = Auth::user();
        
        $data = $request->all();

        $validator = Validators::usersFormValidator($data, ['id' => $user->id]);
        if ($validator->fails()) {
            return back()->withInput()->withErrors($validator);
        }

        $successName = $user->saveUser($data) ? 'success' : 'error';

        return redirect()->route('profile')->with($successName, __('messages.users.update_' . $successName, ['name' => $user->name]));
    }
}
