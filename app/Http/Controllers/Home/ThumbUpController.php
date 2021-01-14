<?php

namespace App\Http\Controllers\Home;

use App\Model\ThumbUp;
use App\Http\Requests\ThumbUpRequest;
use App\Http\Controllers\Controller;

class ThumbUpController extends Controller
{
    protected $thumbUp;

    public function __construct(ThumbUp $thumbUp)
    {
        $this->thumbUp = $thumbUp;
    }
    /**
     * 点赞操作
     * Method POST
     * 
     * @param App\Http\Requests\ThumbUpRequest $request
     * @return mixed
     */
    public function store (ThumbUpRequest $request) {
        // 验证表单数据
        $data = $request->validated();

        $to = (new $data['able_type'])::select('user_id')->findOrFail($data['able_id']);
        
        $this->beginTransition();

        try {

            $data['to'] = $to->user_id;

            // 入库操作
            $thumbUp = $this->thumbUp->where($data)->first();

            if ($thumbUp) {
                $thumbUp->update([
                    'content' => $thumbUp->content + 1
                ]);
            } else {
                $this->thumbUp->create($data);
            }

            $this->commit();

            return response()->success('', '点赞成功');
        } catch (\Exception $e) {

            $this->rollBack();

            return response()->error('点赞失败');
        }
    }
}
