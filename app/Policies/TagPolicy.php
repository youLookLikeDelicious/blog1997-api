<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Tag;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Support\Facades\Storage;

class TagPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can create tags.
     *
     * @param User $user
     * @param array $tag // 被验证后的数据
     * @return mixed
     */
    public function create(User $user, array $tag)
    {
        return $tag['parent_id'] == -1;
    }

    /**
     * Determine whether the user can update the tag.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Tag  $tag
     * @return mixed
     */
    public function update()
    {
        return false;
    }

    /**
     * Determine whether the user can delete the tag.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Tag  $tag
     * @return mixed
     */
    public function delete()
    {
        return false;
    }

    public function before($user, $ability)
    {
        if ($user->isMaster()) {
            return true;
        }
    }
}
