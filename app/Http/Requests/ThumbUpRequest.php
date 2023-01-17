<?php

namespace App\Http\Requests;

use App\Http\Requests\Traits\DecodeParam;
use Illuminate\Foundation\Http\FormRequest;

class ThumbUpRequest extends FormRequest
{
    use DecodeParam;
    
    public $key = 'able_id';

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
            'able_id'   => 'required|numeric',
            'able_type' => 'required|in:article,comment'
        ];
    }

    public function validated()
    {
        $result = parent::validated();

        // 设置用户id
        $result['user_id'] = auth()->id();
        
        return $result;
    }
}
