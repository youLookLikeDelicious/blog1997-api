<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'role';

    /**
     * The storage format of the model's date columns.
     *
     * @var string
     */
    protected $dateFormat = 'U';
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'remark'];

    /**
     * Define many to many relations with auth
     *
     * @return Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function authorities () {
        return $this->belongsToMany('App\Model\Auth', 'role_auth');
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'manager_roles', 'role_id', 'user_id');
    }
}
