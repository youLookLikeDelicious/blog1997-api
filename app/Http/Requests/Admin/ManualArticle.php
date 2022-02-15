<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ManualArticle extends FormRequest
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
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'title'       => [
                'max:45',
                Rule::requiredIf(function () {
                    return !$this->input('parent_id');
                })
            ],
            'manual_id'   => 'required|int',
            'catalog_id'  => 'sometimes|required|int',
            'parent_id'   => 'sometimes|required',
            'content'     => 'sometimes|max:65535',
            'is_markdown' => 'required|in:yes,no'
        ];
    }
}
