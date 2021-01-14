<?php

namespace App\Http\Controllers\Home;

use App\Model\MessageBox;
use App\Repository\IllegalInfo;
use App\Http\Controllers\Controller;
use App\Http\Requests\ReportIllegalInfo as Request;

class ReportIllegalInfoController extends Controller
{
    /**
     * 举报违法信息
     * Method POST
     * 
     * @return Response
     */
    public function store (Request $request, IllegalInfo $illegalInfo)
    {
        // 验证提交的内容
        $data = $request->validated();
        
        // type 2表示举报的是评论， 1表示举报的是文章
        if ($illegalInfo->hasBeenProcessed($data)) {
            return response()->success('', '该记录已被处理,感谢您的配合');
        }

        // 向数据库中插入记录
        MessageBox::firstOrCreate($data);

        return response()->success('', '举报成功，我们会及时处理，感谢您的配合');
    }
}
