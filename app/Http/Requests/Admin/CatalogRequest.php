<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class CatalogRequest extends FormRequest
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
            'name'         => 'required|string|max:45',
            'manual_id'    => 'required',
            'is_public'    => 'required|in:1,2',
            'pre_node_id'  => 'sometimes',
            'parent_id'    => 'sometimes',
            'next_node_id' => 'sometimes',
            'type'         => 'sometimes|in:1,2'
        ];
    }
}
