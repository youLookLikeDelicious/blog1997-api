<?php
namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class CommentFactory extends Factory
{
    public function definition()
    {
        return [
            'level' => 1,
            'root_id' => 1,
            'reply_to' => '1',
            'able_id' => 1,
            'able_type' => 'article',
            'content' => 'content'
        ];
    }

    public function suspended()
    {
        $type = func_get_args();
        return $this->state(function (array $attributes) use ($type) {
            $attr = [];
            // 留言
            if (in_array('Blog1997', $type)) {
                $attr += [
                    'able_type' => 'Blog1997',
                    'able_id' => 0,
                    'level' => 1,
                    'reply_to' => 0
                ];
            }

            // 普通评论
            if (in_array('comment', $type)) {
                $attr = array_merge($attr, [
                    'able_type' => 'comment'
                ]);
            }

            // 二级评论
            if (in_array('level-2', $type)) {
                $attr = array_merge($attr, [
                    'level' => 2
                ]);
            }

            // 三级评论
            if (in_array('level-3', $type)) {
                $attr = array_merge($attr, [
                    'level' => 3
                ]);
            }

            // 未验证的评论
            if (in_array('no-verified', $type)) {
                $attr = array_merge($attr, [
                    'verified' => 'no'
                ]);
            }

            return $attr;
        });
    }
}
