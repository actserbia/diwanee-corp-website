<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Creativeorange\Gravatar\Facades\Gravatar;

use App\Constants\Models;
use App\Models\ModelDataManager;
use Illuminate\Support\Facades\DB;

class User extends Authenticatable
{
    use Notifiable;
    use SoftDeletes;
    use ModelDataManager;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];
    
    protected $fields = ['id', 'name', 'email', 'password', 'role', 'active', 'api_token', 'created_at', 'updated_at', 'deleted_at'];
    
    protected $attributeType = [
        'email' => Models::AttributeType_Email,
        'password' => Models::AttributeType_Password,
        'role' => Models::AttributeType_Enum,
        'active' => Models::AttributeType_Enum
    ];
    
    protected $required = ['name', 'email', 'password', 'role'];
     
    public function saveUser(array $data) {
        DB::beginTransaction();
        try {
            $this->name = $data['name'];
            $this->email = $data['email'];
            $this->role = $data['role'];

            if(isset($data['password'])) {
                $this->password = bcrypt($data['password']);
            }

            $this->save();

            DB::commit();
            return true;

        } catch(Exception $e) {
            DB::rollBack();
            return false;
        }
    }
     
     public function getAvatar($size = null) {
        $size = ($size) ? $size : config('gravatar.default.size');
        return $this->gravatar()->fallback('/pictures/user.png')->get($this->email, ['size' => $size]);
    }

    function gravatar() {
        return app('gravatar');
    }
}
