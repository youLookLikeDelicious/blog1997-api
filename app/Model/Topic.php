<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Topic extends Model
{
    //
    public $dateFormat = 'U';
    protected $table = 'topic';
    protected $guarded = ['id'];
    protected $appends = ['edit_state'];

    /**
     * 定义额外的属性 editState
     */
    public function getEditStateAttribute () {
        return false;
    }
}
