<?php

namespace App\Http\Requests;

use App\Http\Requests\Traits\DecodeParam;
use Illuminate\Foundation\Http\FormRequest;

class ThumbUpRequest extends FormRequest
{
    use DecodeParam;
    
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
            'id'       => 'required|numeric',
            'category' => 'required|in:article,comment'
        ];
    }

    public function validated()
    {
        $result = parent::validated();

        $result['able_id'] = $result['id'];
        $result['able_type'] = $result['category'] === 'article' ? 'App\Model\Article' : 'App\Model\Comment';

        unset($result['id']);
        unset($result['category']);

        // 设置用户id
        $result['user_id'] = auth()->id();
        
        return $result;
    }
}
