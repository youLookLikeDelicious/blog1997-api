<?php

namespace App\Http\Requests;

use App\Http\Requests\Traits\DecodeParam;
use App\Rules\Exists;
use App\Service\FilterStringService;
use Illuminate\Foundation\Http\FormRequest;

class ReportIllegalInfo extends FormRequest
{
    protected $filterStringService;
    
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
        $reportedIdRule = ['required', 'numeric'];
        switch ($this->type) {
            case 'article':
                array_push($reportedIdRule, new Exists('article'));
            break;
            case 'comment':
                array_push($reportedIdRule, new Exists('comment'));
            break;
        }
        
        return [
            'sender'        => 'required|numeric',
            'content'       => 'nullable|max:2100',
            'type'          => 'required|in:article,comment',
            'reported_id'   => $reportedIdRule
        ];
    }

     /**
     * Get data to be validated from the request.
     *
     * @return array
     */
    public function validationData()
    {
        if (! is_numeric($this['reported_id'])) {
            $this->merge(['reported_id' => base64_decode($this['reported_id'])]);
        }
        
        // 获取提交的内容
        $this->merge(['content' => app()->make(FilterStringService::class)->removeXss($this['content'])]);
        
        return $this->all();
    }

    /**
     * Get the validated data from the request.
     *
     * @return array
     */
    public function validated()
    {
        $data = parent::validated();

        $data['receiver'] = -1;

        $data['content'] = trim($data['content'], ',');

        return $data;
    }
}
