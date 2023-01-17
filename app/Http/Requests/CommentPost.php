<?php

namespace App\Http\Requests;

use App\Models\Comment;
use App\Rules\ImageSource;
use App\Service\FilterStringService;
use App\Service\SensitiveWordService;
use App\Http\Requests\Traits\DecodeParam;
use Illuminate\Foundation\Http\FormRequest;
use App\Http\Requests\Traits\FailedValidation;
use Illuminate\Database\Eloquent\Relations\Relation;

class CommentPost extends FormRequest
{
    use FailedValidation, DecodeParam;

    public $key = 'able_id';

    /**
     * Real content length
     *
     * @var integer
     */
    protected $maxContentLength = 700;

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        // 过滤html标签
        $rule = [
            'able_id'   => 'required|numeric',
            'able_type' => 'in:article,comment,Blog1997',
            'content'   => ['required', 'max:2100', new ImageSource]
        ];

        if ($this->input('able_type') === 'Blog1997' && $this->input('level') == 1) {
            $rule = array_merge($rule, [
                'able_id' => 'required|in:0',
            ]);
        }

        return $rule;
    }

    /**
     * Auth parent id should not equal to auth id
     *
     * @param  \Illuminate\Validation\Validator  $validator
     * @return void
     */
    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            
            $content = $this->input('content', '');

            $filterContent = app()->make(FilterStringService::class)->removeHTMLTags($content, 'img');

            $pureLength = mb_strlen($filterContent);
            if ($pureLength > $this->maxContentLength) {
                $validator->errors()->add('content', "内容长度不能超过{$this->maxContentLength}!");
            }
            if (!$pureLength) {
                $validator->errors()->add('content', '内容无效!');
            }
        });
    }

    /**
     * 获取验证后的数据
     * 
     * @return array
     */
    public function validated()
    {
        $result = parent::validated();

        // 生成root_id和level
        if ($result['able_type'] !== 'comment') {
            $result['level'] = 1;
            $result['root_id'] = 0;
        } else {
            $rootComment = Comment::select(['id', 'root_id'])->findOrFail($result['able_id']);

            $result['root_id'] = $rootComment->root_id ?: $rootComment->id;
            $result['level'] = $rootComment->root_id ? 3 : 2;
        }

        $filterStringService = app()->make(FilterStringService::class);
        $sensitiveWordService = app()->make(SensitiveWordService::class);
        $content = $filterStringService->removeXss($result['content']);
        $content = $sensitiveWordService->make($content);
        $content = $filterStringService->coverImageSrc($content);
        
        $result['content'] = $content;

        // set reply to
        $result['reply_to'] = $this->getReplyPerson($result);

        // 设置user id
        $result['user_id'] = auth()->id();

        return $result;
    }

    /**
     * Get the person of reply to
     *
     * @param array $comment
     * @return int
     */
    protected function getReplyPerson($comment)
    {
        if ($comment['able_type'] === 'Blog1997') {
            return 1;
        }

        $class = Relation::getMorphedModel($comment['able_type']);
        $commentAbleModel = $class::select('user_id', 'id')->findOrFail($comment['able_id']);

        return $commentAbleModel->user_id;
    }
}
