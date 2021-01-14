<?php

namespace App\Http\Controllers\Admin;

use App\Model\SystemSetting;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;
use App\Http\Requests\Admin\SystemSettingRequest;
use App\Repository\Admin\SystemSetting as Repository;

class SystemSettingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Repository $repository)
    {
        $setting = $repository->get();

        return response()->success($setting);
    }

    /**
     * Update a specified resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function update(SystemSettingRequest $request, SystemSetting $systemSetting)
    {
        $data = $request->validated();

        $systemSetting->update($data);

        Cache::forget('system-setting');

        return response()->success($systemSetting, '配置修改成功');
    }
}
