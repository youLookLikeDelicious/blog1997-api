<?php
namespace App\Repository\Home;

use App\Contract\Repository\Tag as RepositoryTag;
use App\Model\Tag as ModelTag;
use Illuminate\Http\Request;

class Tag implements RepositoryTag
{
    /**
     * Tag eloquent
     *
     * @var ModelTag
     */
    protected $tag;

    /**
     * Create instance
     *
     * @param ModelTag $tag
     */
    public function __construct(ModelTag $tag)
    {
        $this->tag = $tag;
    }

    /**
     * 获取所有的标签
     *
     * @return array
     */
    public function all(?Request $request) :array
    {
        $tags = $this->tag->select('name', 'id')
            ->where('user_id', 0)
            ->get();

        return $tags->toArray();
    }
}