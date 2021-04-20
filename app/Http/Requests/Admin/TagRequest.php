<?php

namespace App\Http\Requests\Admin;

use App\Contract\Repository\Tag;
use App\Facades\Upload;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Storage;
class TagRequest extends FormRequest
{
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
            'cover'     => __('field.cover'),
            'description'     => __('field.description'),
        ];
    }

    /**
     * Get data to be validated from the request.
     *
     * @return array
     */
    protected function validationData()
    {
        $data = $this->all();

        if (empty($data['cover']) && $this->file('cover')) {
            $data['cover'] = $this->file('cover');
        }

        $data['description'] = $data['description'] ?? '';

        return $data;
    }

    /**
     * Get the validation rules that apply to the request.
     * Validate create or update primary tag, not user custom defined tag
     *
     * @return array
     */
    public function rules()
    {
        $rules = [
            'name'  => 'required|max:45',
            'cover' => 'sometimes|required|string|max:120',
            'parent_id'   => 'required|integer|min:-1',
            'description' => 'present|max:450',
        ];

        if (auth()->isMaster() && $this->coverIsFile()) {
            $rules['cover'] = 'required|image|max:10240';
        }

        $parameters = $this->route()->parameters();

        if (isset($parameters['tag'])) {
            $rules['name'] .= ",{$parameters['tag']->id},id";
        }

        return $rules;
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

            $routeParameter = $this->route()->parameters();

            // 验证更新的时候,父id是否等于该记录的id
            if (isset($routeParameter['tag']) && $routeParameter['tag']->id == $this->input('parent_id')) {
                $validator->errors()->add('parent_id', __('validation.invalid'));
            }

            // 标签名称不能重复
            $exists = app()->make(Tag::class)->exists([
                'name' => $this['name'],
                'user_id' => 0,
            ], $routeParameter['tag'] ?? null);
            
            if ($exists) {
                $validator->errors()->add('name', __('validation.unique', ['attribute' => 'name']));
            }

            if ($this['parent_id'] > 0 && !app()->make(Tag::class)->exists(['id' => $this['parent_id']])) {
                $validator->errors()->add('parent_id', __('validation.unique', ['attribute' => 'name']));
            }
        });
    }

    /**
     * Get the validated data from the request.
     *
     * @return array
     */
    public function validated()
    {
        $result = parent::validated();

        if (isset($result['cover']) && $this->coverIsFile()) {
            $result['cover'] = Upload::uploadImage($result['cover'], 'tag', 270, 0, false)
                ->getFileList()[0];
        }

        $result['parent_id'] += 0;

        return $result;
    }


    /**
     * Check cover is file
     *
     * @return boolean
     */
    public function coverIsFile()
    {
        return $this['cover'] instanceof \Illuminate\Http\Testing\File || is_uploaded_file($this->file('cover'));
    }
}
