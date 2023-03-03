<?php

namespace App\Http\Controllers\Home;

use App\Events\ThumbUpEvent;
use App\Models\ThumbUp;
use App\Http\Requests\ThumbUpRequest;
use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\Facades\DB;

/**
 * @group Home\ThumbUpController
 * 
 * 点赞 文章|评论
 */
class ThumbUpController extends Controller
{
    /**
     * Thumb up model
     *
     * @var ThumbUp
     */
    protected $thumbUp;

    public function __construct(ThumbUp $thumbUp)
    {
        $this->thumbUp = $thumbUp;
    }

    /**
     * store
     * 点赞操作
     * 
     * @bodyParam id int|string required 文章|评论的ID
     * @bodyParam category string required 点赞的类型
     * @responseFile response/home/thumb-up/store.json
     * @param App\Http\Requests\ThumbUpRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store (ThumbUpRequest $request)
    {
        $data = $request->validated();

        $to = Relation::getMorphedModel($data['able_type'])::select('user_id')->findOrFail($data['able_id']);
        
        $this->beginTransition();

        try {

            $data['to'] = $to->user_id;
            $thumbUp = ThumbUp::updateOrCreate($data, ['content' => DB::raw('content + 1')]);

            event(new ThumbUpEvent($thumbUp));
            $this->commit();

            return response()->success('', '点赞成功');
        } catch (\Exception $e) {
            $this->rollBack();
            throw $e;
            return response()->error('点赞失败');
        }
    }
}
