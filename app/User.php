<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;

use DB;

class User extends Authenticatable
{
    use Notifiable;
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'api_token'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function saveUser(array $data) {
        DB::beginTransaction();
        try {
            $this->name = $data['name'];
            $this->email = $data['email'];
            $this->role = $data['role'];

            if(isset($data['password'])) {
                $this->password = bcrypt($data['password']);
            }

            if(!isset($this->api_token)) {
                $this->api_token = str_random(60);
            }

            $this->save();

            DB::commit();
            return true;

        } catch(Exception $e) {
            DB::rollBack();
            return false;
        }
    }
}