<?php

namespace App\Contract\Repository;

use Illuminate\Http\Request;

interface MessageBox
{
    public function all(Request $request);

    public function find($id);
    
    /**
     * 统计文章和评论被举报的数量
     *
     * @return array
     */
    public function statisticByType();

    /**
     * Get comment notification
     *
     * @return void
     */
    public function getNotification(Request $request);
}