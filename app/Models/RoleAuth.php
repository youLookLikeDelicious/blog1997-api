<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RoleAuth extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'role_auth';

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;
}
