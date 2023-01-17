<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Auth extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'auth';

    /**
     * The storage format of the model's date columns.
     *
     * @var string
     */
    protected $dateFormat = 'U';

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = ['id'];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'parent_id' => 'int'
    ];

    /**
     * The auth belongs to role
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function roles()
    {
        return $this->belongsToMany('App\Models\Role', 'role_auth');
    }

    /**
     * Define relations with child
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function child()
    {
        return $this->hasMany(static::class, 'parent_id', 'id')
            ->select('id', 'name', 'parent_id')
            ->with(['child']);
    }

    /**
     * Get the model's relationships in array form.
     *
     * @return array
     */
    public function relationsToArray()
    {
        $attributes = parent::relationsToArray();

        if (isset($attributes['child']) && !$attributes['child']) {
            $attributes['child'] = null;
        }

        return $attributes;
    }
}
