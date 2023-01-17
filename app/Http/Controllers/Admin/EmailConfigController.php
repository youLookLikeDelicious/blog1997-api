<?php
namespace App\Http\Controllers\Admin;

use App\Models\EmailConfig;
use Illuminate\Support\Facades\Cache;
use App\Http\Requests\Admin\EmailConfigRequest;
use App\Repository\Admin\EmailConfig as AdminEmailConfig;

/**
 * @group Email config management
 * 
 * 系统邮箱的配置
 */
class EmailConfigController
{
    /**
     * Get email config record
     * 
     * 显示邮箱的配置,永远不会显示真实的授权码
     *
     * @responseFile response/admin/email/index.json
     * 
     * @param AdminEmailConfig $repository
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
     * Store newly created records
     * 
     * 添加邮箱配置
     *
     * @bodyParam email_server string required 邮箱服务器地址,例如:smtp.163.com
     * @bodyParam port         number required 邮箱服务器端口,例如:465
     * @bodyParam email_addr   string required 邮箱地址,例如:blog1997@163.com
     * @bodyParam encryption   string required 加密方式,例如:ssl
     * @bodyParam sender       string required 发件人名称,例如:blog1997
     * @bodyParam password     string required 授权码,例如:S*********N
     * @responseFile response/admin/email/store.json
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
     * 修改邮箱配置
     *
     * @bodyParam email_server string required 邮箱服务器地址,例如:smtp.163.com
     * @bodyParam port         number required 邮箱服务器端口,例如:465
     * @bodyParam email_addr   string required 邮箱地址,例如:blog1997@163.com
     * @bodyParam encryption   string required 加密方式,例如:ssl
     * @bodyParam sender       string required 发件人名称,例如:blog1997
     * @bodyParam password     string required 授权码,例如:S*********N
     * @responseFile response/admin/email/update.json
     * 
     * @param EmailConfigRequest $request
     * @param EmailConfig $emailConfig
     * @return \Illuminate\Http\Response
     */
    public function update(EmailConfigRequest $request, EmailConfig $emailConfig)
    {
        $data = $request->validated();

        $emailConfig->update($data);

        if (!empty($emailConfig['password'])) {
            $emailConfig['password'] = 'blog1997';
        }

        $this->forgotCache();

        return response()->success($emailConfig, '邮箱配置修改成功');
    }

    /**
     * 清除邮箱配置的缓存
     *
     * @return void
     */
    protected function forgotCache()
    {
        Cache::forget('email-config');
    }
}