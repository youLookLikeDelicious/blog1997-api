<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class User extends JsonResource
{
    protected $token;

    /**
     * Create a new resource instance.
     *
     * @param  mixed  $resource
     * @return void
     */
    public function __construct($resource, $token = '')
    {
        $this->resource = $resource;
        $this->token    = $token;
    }

    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        if (auth()->user() && auth()->user()->id !== $this->id) {
            $email = substr($this->email, 0, 5) . '***' . strrchr($this->email, '.');
        } else {
            $email = $this->email;
        }
        
        $result = [
            'id' => $this->id,
            'name' => $this->name,
            'avatar' => $this->avatar,
            'email' => $email,
            'article_sum' => $this->article_sum,
            'created_at' => $this->created_at
        ];

        if ($this->token) {
            $result['token'] = $this->token;
        }

        $this->load('roles:role.id,name');
        
        if ($this->roles->isNotEmpty()) {
            $authorities = $this->roles->load('authorities:id,name,route_name')
                ->pluck('authorities')
                ->flatten();

            $result['roles'] = $this->roles;
            $result['authorities'] = $authorities;
        } else {
            $result['roles'] = [];
        }
        return $result;
    }
}
