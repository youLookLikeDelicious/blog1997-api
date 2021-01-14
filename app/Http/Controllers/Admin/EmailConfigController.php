<?php
namespace App\Http\Controllers\Admin;

use App\Model\EmailConfig;
use Illuminate\Support\Facades\Cache;
use App\Http\Requests\Admin\EmailConfigRequest;
use App\Repository\Admin\EmailConfig as AdminEmailConfig;

class EmailConfigController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index (AdminEmailConfig $repository)
    {
        $config = $repository->get();

        if ($config) {
            $config = $config->toArray();
        }

        if (!empty($config['password'])) {
            $config['password'] = 'blog1997';
        }

        return response()->success($config);
    }

    /**
     * Store email config to database
     *
     * @param EmailConfigRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(EmailConfigRequest $request)
    {
        $data = $request->validated();

        if (EmailConfig::where('id', '>', 0)->delete()) {
            $this->forgotCache();
        }

        $emailConfig = EmailConfig::create($data);

        return response()->success($emailConfig, '邮箱配置成功');
    }

    /**
     * Update a specified resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function update(EmailConfigRequest $request, EmailConfig $emailConfig)
    {
        $data = $request->validated();

        $emailConfig->update($data);

        $this->forgotCache();

        return response()->success($emailConfig, '邮箱配置修改成功');
    }

    /**
     * 删除
     *
     * @return void
     */
    protected function forgotCache()
    {
        Cache::forget('email-config');
    }
}