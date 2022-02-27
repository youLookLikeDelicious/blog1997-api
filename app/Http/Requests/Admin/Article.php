<?php

namespace App\Http\Requests\Admin;

use App\Model\Tag;
use App\Model\Topic;
use App\Model\Gallery;
use App\Facades\Upload;
use App\Service\SummaryService;
use App\Service\GalleryService;
use App\Service\FilterStringService;
use Illuminate\Foundation\Http\FormRequest;

class Article extends FormRequest
{
    /**
     * Topic instance
     *
     * @var Topic
     */
    protected $topic;

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
     * Get custom attributes for validator errors.
     *
     * @return array
     */
    public function attributes()
    {
        return [
            'tags'     => __('field.tags'),
            'title'    => __('field.title'),
            'content'  => __('field.content'),
            'topic_id' => __('field.topic_id'),
            'is_markdown' => __('field.is_markdown'),
        ];
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [
            'title'     => 'required|max:72',
            'topic_id'  => 'required',
            'is_origin' => 'sometimes|required|in:yes,no',
            'order_by'  => 'sometimes|sometimes|integer',
            'content'   => 'required|max:30000',
            'tags'      => 'required|array|min:1|max:5',
            'tags.*'    => 'required',
            'is_draft'  => 'sometimes|in:no,yes',
        ];

        // 作为草稿保存的规则
        if ($this->input('is_draft') === 'yes') {
            $rules['topic_id'] = 'sometimes|' . $rules['topic_id'];
            $rules['tags'] = 'sometimes|' . $rules['tags'];
            $rules['title'] = 'sometimes|' . $rules['title'];
            $rules['tags'] = 'sometimes|' . $rules['tags'];
            $rules['content'] = 'sometimes|' . $rules['content'];
        }

        if (is_uploaded_file($this->file('cover'))) {
            $rules['cover'] = 'required|image|max:10240';
        }

        if ($this->route()->getName() === 'article.store') {
            $rules['is_markdown'] = 'required|in:yes,no';
        }

        return $rules;
    }

    /**
     * Get validated data
     *
     * @return array
     */
    public function validated()
    {
        $result = $this->validator->validated();

        if (!empty($result['content'])) {
            $result['summary'] = app()->make(SummaryService::class)->make($result['content']);
            $result['content'] = app()->make(FilterStringService::class)->coverImageSrc($result['content']);
        }

        if (!empty($result['cover']) && is_uploaded_file($result['cover'])) {
            // 将封面上传到服务器上
            $result['gallery_id'] = $this->getGalleryId($result['cover']);
        } else if (!isset($this->route()->parameters()['article'])) {
            // 如果没有gallery id,自动从相册中生成一个封面
            $result['gallery_id'] = app()->make(GalleryService::class)
                ->calculateGalleryId();
        }

        if (isset($result['tags'])) {
            $this->dealWithTags($result);
        }

        if (empty($result['topic_id'])) {
            unset($result['topic_id']);
        }

        if($this->topic) {
            $result['topic_id'] = $this->topic->id;
        }

        $result['user_id'] = auth()->id();
        
        unset($result['cover']);

        return $result;
    }

    /**
     * Configure the validator instance.
     *
     * @param  \Illuminate\Validation\Validator  $validator
     * @return void
     */
    public function withValidator($validator)
    {
        $validator->after(function ($validator) {

            $topic = $this->input('topic_id');
    
            if (empty($topic)) {
                return;
            }
    
            // 如果专题id没有对应的记录,给出错误信息
            // 如果专题字段不是 一个字符串,创建之
            if (is_numeric($topic) && is_integer($topic + 0)) {
                if (!Topic::select('id')->find($topic + 0)) {
                    $validator->errors()->add('topic_id', __('validation.exists', ['attribute' => __('field.topic_id')]));
                }
            } else if (is_string($topic)) {
                $this->createTopic($topic);
            }
        });
    }

    /**
     * 如果是新建的Tag，将tag保存后，获取其id
     *
     * @param array $data
     * @return void
     */
    protected function dealWithTags(&$data)
    {
        // 过滤出系统预定义的标签
        $tags = array_filter($data['tags'], function ($item) {
            return is_numeric($item) && is_integer($item + 0);
        });

        // 获取新建的标签
        $newTags = array_diff($data['tags'], $tags);

        // 如果有自定义的标签，添加之
        if ($newTags) {
            $newTags = $this->createNewTag($newTags);
            $data['tags'] = array_merge($tags, $newTags);
        }
    }

    /**
     * 创建新的标签
     * 
     * @param array $newTags
     * @return array
     */
    protected function createNewTag($tags)
    {
        $result = array_map(function ($tag) {
            $model = [
                'parent_id' => -1,
                'name' => $tag,
                'created_at' => time(),
                'updated_at' => time(),
                'user_id' => auth()->id()
            ];
            return Tag::create($model)->id;
        }, $tags);

        return $result;
    }

    /**
     * 上传图片,获取gallery id
     *
     * @return int
     */
    protected function getGalleryId($file)
    {
        $imgUrl = Upload::uploadImage($file, 'cover')->getFileList()[0];

        $gallery = Gallery::create([
            'url' => $imgUrl,
            'is_cover' => 'yes'
        ]);

        return $gallery->id;
    }

    /**
     * 创建一个专题
     *
     * @param string $name
     * @return int
     */
    protected function createTopic($name)
    {
        $topic = Topic::firstOrCreate([
            'name' => $name,
            'user_id' => auth()->id()
        ]);

        return $this->topic = $topic;
    }
}
