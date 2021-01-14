<?php

namespace App\Http\Requests\Admin;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class SensitiveCategoryRequest extends FormRequest
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
        $name = $this['name'];
        $rank = $this['rank'];
        return [
            'rank' => 'required|integer|between:1,3',
            'name' => [
                'required',
                'max:12',
                Rule::unique('sensitive_word_category')
                    ->where(function ($query) use ($name, $rank) {
                        return $query->where('rank', $rank)
                            ->where('name', $name);
                    }) ],
        ];
    }
}
