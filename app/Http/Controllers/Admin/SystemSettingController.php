<?php

namespace App\Http\Controllers\Admin;

use App\Facades\CacheModel;
use App\Models\SystemSetting;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\SystemSettingRequest;

/**
 * @group System config management
 * 
 * 系统配置管理
 */
class SystemSettingController extends Controller
{
    /**
     * Display system config
     *
     * 显示系统配置信息
     * 
     * @responseFile response/admin/system-setting/index.json
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $setting = CacheModel::getSystemSetting();

        return response()->success($setting);
    }

    /**
     * Update system config.
     *
     * 更新系统的配置
     * 
     * @bodyParam enable_comment string required 是否开启评论,例如:yes,no
     * @bodyParam verify_comment string required 是否审核评论,例如:yes,no
     * @param SystemSettingRequest $request
     * @param SystemSetting $systemSetting
     * @return \Illuminate\Http\Response
     */
    public function update(SystemSettingRequest $request, SystemSetting $systemSetting)
    {
        $data = $request->validated();

        $systemSetting->update($data);

        CacheModel::forgetSystemSetting();

        return response()->success($systemSetting, '配置修改成功');
    }
}
