<?php

namespace App\Http\Requests;

use App\Http\Requests\Traits\DecodeParam;
use App\Model\Comment;
use App\Rules\ImageSource;
use App\Service\FilterStringService;
use App\Service\SensitiveWordService;
use Illuminate\Foundation\Http\FormRequest;
use App\Http\Requests\Traits\FailedValidation;

class CommentPost extends FormRequest
{
    use FailedValidation, DecodeParam;

    protected $filterStringService;

    protected $sensitiveWordService;

    /**
     * Real content length
     *
     * @var integer
     */
    protected $maxContentLength = 700;

    /**
     * 映射评论的类型对应的关系
     * 
     * @var array
     */
    const commentType = [
        'comment' => 'App\Model\Comment',
        'article' => 'App\Model\Article',
        'Blog1997' => 'Blog1997'
    ];

    public function __construct(FilterStringService $filterStringService, SensitiveWordService $sensitiveWordService)
    {
        $this->filterStringService = $filterStringService;
        $this->sensitiveWordService = $sensitiveWordService;

        $this->key = 'able_id';
    }
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

            $filterContent = $this->filterStringService->removeHTMLTags($content, 'img');

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

        $result['able_type'] = self::commentType[$result['able_type']];

        // 生成root_id和level
        if ($result['able_type'] !== self::commentType['comment']) {
            $result['level'] = 1;
            $result['root_id'] = 0;
        } else {

            $rootComment = Comment::select(['id', 'root_id'])->find($result['able_id']);

            $result['root_id'] = $rootComment->root_id ?: $rootComment->id;
            $result['level'] = $rootComment->root_id ? 3 : 2;
        }

        $content = $this->filterStringService->removeXss($result['content']);
        $content = $this->sensitiveWordService->make($content);
        $content = $this->filterStringService->coverImageSrc($content);
        
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

        $commentAbleModel = $comment['able_type']::select('user_id', 'id')
            ->findOrFail($comment['able_id']);

        return $commentAbleModel->user_id;
    }
}
