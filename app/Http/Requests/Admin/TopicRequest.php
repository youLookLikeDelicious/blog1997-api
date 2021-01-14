<?php

namespace App\Http\Requests\Admin;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class TopicRequest extends FormRequest
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
        $name = $this->name;

        $rules = [
            'id' => 'nullable|numeric',
            'name' => [
                'required', 'max:15',
                Rule::unique('topic')
                    ->where(function ($query) use ($name) {
                        return $query->where('name', $name)
                            ->where('user_id', auth()->id());
                    })
            ]
        ];

        return $rules;
    }

    /**
     * Get the validated data from the request.
     *
     * @return array
     */
    public function validated()
    {
        $result =  parent::validated();
        $result['user_id'] = auth()->id();

        return $result;
    }
}
