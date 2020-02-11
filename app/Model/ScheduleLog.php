<?php
namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class ScheduleLog extends Model
{
    //
    protected $table = 'schedule_log';
    protected $dateFormat = 'U';
    protected $guarded = [];
}
