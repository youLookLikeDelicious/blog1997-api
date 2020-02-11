<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class FailedJobs extends Model
{
    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;
    
    protected $table = 'failed_jobs';
    protected $guarded = [];
}
