<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class User extends JsonResource
{
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

        $roles = $this->load('roles:role.id,name')->roles;
        
        if ($roles->isNotEmpty()) {
            $authorities = $roles->load('authorities:id,name,route_name')
                ->pluck('authorities')
                ->flatten();

            $result['roles'] = $roles;
            $result['authorities'] = $authorities;
        }
        return $result;
    }
}
