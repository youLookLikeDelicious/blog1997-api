<?php
namespace App\Repository\Admin;

use App\Facades\Page;
use App\Model\Topic as ModelTopic;
use App\Contract\Repository\Topic as RepositoryTopic;
use Illuminate\Http\Request;

class Topic implements RepositoryTopic
{
    protected $model;

    public function __construct(ModelTopic $model)
    {
        $this->model = $model;
    }

    /**
     * get all topic by user id
     *
     * @param int $userId
     * @return array
     */
    public function all ()
    {
        $topics = $this->model
            ->select('id', 'name', 'user_id')
            ->where('user_id', auth()->id())
            ->get();

        return $topics;
    }

    /**
     * 分页获取专题
     * 
     * @param Request $request
     * @return array
     */
    public function paginate(Request $request)
    {
        $topicQuery = $this->model
            ->selectRaw('id ,name, article_sum, false as editAble ,created_at')
            ->where('user_id', auth()->id());
        
        if ($name = $request->input('name')) {
            $topicQuery->where('name', 'like', "%{$name}%");
        }
        
        $topicList = Page::paginate($topicQuery);
        
        return $topicList;
    }
}