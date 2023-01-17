<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Article;
use App\Models\ArticleBase;
use Illuminate\Auth\Access\HandlesAuthorization;

class ArticlePolicy
{
    use HandlesAuthorization;
    
    /**
     * Determine whether the user can view any articles.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function viewAny(User $user)
    {
        return true;
    }

    /**
     * Determine whether the user can view the article.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Article  $article
     * @return mixed
     */
    public function view(User $user, Article $article)
    {
        return $article->user_id == $user->id;
    }

    /**
     * Determine whether the user can create articles.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return true;
    }

    /**
     * Determine whether the user can update the article.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Article  $article
     * @return mixed
     */
    public function update(User $user, $article = '')
    {
        return $article->user_id === $user->id;
    }

    /**
     * Determine whether the user can delete the article.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Article  $article
     * @return boolean
     */
    public function delete(User $user, ArticleBase $article)
    {
        if ($user->isMaster()) {
            return true;
        }

        return $article->user_id === $user->id;
    }
}
