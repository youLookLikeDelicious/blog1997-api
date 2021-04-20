<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repository\Admin\LogRepository;

/**
 * @group Log management
 * 
 * 查询系统|用户日志
 */
class LogController extends Controller
{
    /**
     * Get logs
     * 
     * 获取日志信息
     * Display a listing of the resource.
     *
     * @urlParam type 日志类型,例如:user,schedule
     * @queryParam email 用户邮箱
     * @queryParam startDate 开始日期
     * @queryParam endDate   结束日期
     * @queryParam p         请求的页数
     * @responseFile response/admin/log/index.json
     * @param Request $request
     * @param LogRepository $repository
     * @param String $type
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, LogRepository $repository, $type = 'user')
    {        
        $logs = $repository->all($request, $type);

        return response()->success($logs);
    }
}
