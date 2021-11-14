<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RoleUser extends Model
{
    protected $table = 'role_user';
    protected $primaryKey = 'user_id';

    public function role(){
        return $this->belongsTo('App\Models\Role','role_id');
    }
}
