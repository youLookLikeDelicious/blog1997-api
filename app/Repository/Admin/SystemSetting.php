<?php
namespace App\Repository\Admin;

use App\Model\SystemSetting as Model;
use Illuminate\Support\Facades\Cache;

class SystemSetting
{
    protected $model;

    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    /**
     * Get system setting
     *
     * @return array
     */
    public function get()
    {
        return Cache::remember('system-setting', 120, function () {
            return $this->model->select(['id', 'enable_comment', 'verify_comment'])->first();
        });
    }
}