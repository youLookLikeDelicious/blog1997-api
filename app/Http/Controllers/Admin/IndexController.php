<?php

namespace App\Http\Controllers\Admin;

use App\Contract\Repository\User;
use App\Contract\Repository\Article;
use App\Contract\Repository\ThumbUp;
use App\Http\Controllers\Controller;
use App\Contract\Repository\Comment;
use App\Contract\Repository\MessageBox;

/**
 * @group Admin dashboard
 * 
 * 后台首页
 */
class IndexController extends Controller
{
    /**
     * User repository
     *
     * @var \App\Contract\Repository\User
     */
    protected $userRepository;

    /**
     * Article repository
     *
     * @var \App\Contract\Repository\Article
     */
    protected $articleRepository;

    /**
     * MessageBox repository
     *
     * @var \App\Contract\Repository\MessageBox
     */
    protected $messageBoxRepository;

    /**
     * Thumb up repository
     *
     * @var ThumbUp
     */
    protected $thumbUpRepository;

    /**
     * comment repositoryf
     *
     * @var Comment
     */
    protected $commentRepository;

    /**
     * Create Instance
     *
     * @param User $userRepository
     * @param Article $articleRepository
     * @param MessageBox $messageBoxRepository
     */
    public function __construct(User $userRepository, Article $articleRepository, MessageBox $messageBoxRepository, ThumbUp $thumbUpRepository, Comment $commentRepository)
    {
        $this->userRepository = $userRepository;
        $this->articleRepository = $articleRepository;
        $this->thumbUpRepository = $thumbUpRepository;
        $this->commentRepository = $commentRepository;
        $this->messageBoxRepository = $messageBoxRepository;
    }
    /**
     * Statistic site data
     * 
     * 获取网站的统计信息
     * 
     * @responseFile response/admin/index/index.json
     * @return \Illuminate\Http\Response
     */
    public function index ()
    {
        $data = [];

        // 统计用户来源
        $data['userInfo'] = $this->userRepository->statisticBySource();

        // 统计文章分类
        $data['articleInfo'] = $this->articleRepository->statisticByCategory();

        // 统计被举报的内容
        $data['illegalInfo'] = $this->messageBoxRepository->statisticByType();

        // 统计总的点赞量
        $data['totalLiked'] = $this->thumbUpRepository->totalNum();

        // 统计总的评论量
        $data['totalCommented'] = $this->commentRepository->totalCommented();

        return response()->success($data);
    }
}
