<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Creativeorange\Gravatar\Facades\Gravatar;
use App\Models\Search;
use App\Models\Statistics;
use App\Constants\Models;
use App\Models\ModelDataManager;

class User extends Authenticatable
{
    use Notifiable;
    use SoftDeletes;
    use ModelDataManager;
    use Search;
    use Statistics;

    protected $fillable = ['name', 'email', 'password', 'role'];

    protected $hidden = ['password', 'remember_token',];
    
    protected $allAttributesFields = ['id', 'name', 'email', 'password', 'role', 'active', 'api_token', 'created_at', 'updated_at', 'deleted_at'];
    
    protected $allFieldsFromPivots = [];
    
    protected $filterFields = [
        'id' => false,
        'name' => true,
        'email' => true,
        'role' => false,
        'active' => false,
        'api_token' => false,
        'created_at' => true,
        'updated_at' => true,
        'deleted_at' => false,
        'nodes:id' => false,
        'nodes:title' => true,
        'nodes:status' => false,
        'nodes:created_at' => false,
        'nodes:updated_at' => false,
        'nodes:deleted_at' => false
    ];
    
    protected $statisticFields = [
        'role'
    ];
    
    protected $defaultDropdownColumn = 'name';
    
    protected $attributeType = [
        'email' => Models::AttributeType_Email,
        'password' => Models::AttributeType_Password,
        'role' => Models::AttributeType_Enum,
        'active' => Models::AttributeType_Enum
    ];
    
    protected $requiredFields = ['name', 'email', 'password', 'role'];
    
    protected $relationsSettings = [
        'nodes' => [
            'relationType' => 'hasMany',
            'model' => 'App\\Node',
            'foreignKey' => 'author_id',
            'relationKey' => 'id'
        ]
    ];
     
    public function saveData(array $data) {
        $this->name = $data['name'];
        $this->email = $data['email'];
        $this->role = $data['role'];

        if(isset($data['password'])) {
            $this->password = bcrypt($data['password']);
        }

        $this->save();
    }
     
     public function getAvatar($size = null) {
        $size = ($size) ? $size : config('gravatar.default.size');
        return $this->gravatar()->fallback('/pictures/user.png')->get($this->email, ['size' => $size]);
    }

    function gravatar() {
        return app('gravatar');
    }
}