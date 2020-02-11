<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class SiteInfo extends Model
{
    public $dateFormat = 'U';
    protected $table = 'site_info';
    protected $guarded = [];
}
