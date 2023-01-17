<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SiteMap extends Model
{
    public $dateFormat = 'U';
    protected $table = 'sitemap_info';
    protected $guarded = [];
}
