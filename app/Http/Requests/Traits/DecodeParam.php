<?php
namespace App\Http\Requests\Traits;

trait DecodeParam
{
    /**
     * Get data to be validated from the request.
     *
     * @return array
     */
    public function validationData()
    {
        $data = $this->all();

        $decodeKey = $this->key ?? 'id';
        if (isset($data[$decodeKey]) && !is_numeric($data[$decodeKey])) {
            $data[$decodeKey] = base64_decode($data[$decodeKey]);
        }

        return $data;
    }
}