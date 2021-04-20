<?php

namespace App\Policies;

use App\Model\User;
use App\Model\Article;
use App\Model\ArticleBase;
use Illuminate\Auth\Access\HandlesAuthorization;

class ArticlePolicy
{
    use HandlesAuthorization;
    
    /**
     * Determine whether the user can view any articles.
     *
     * @param  \App\Model\User  $user
     * @return mixed
     */
    public function viewAny(User $user, $article)
    {
        return true;
    }

    /**
     * Determine whether the user can view the article.
     *
     * @param  \App\Model\User  $user
     * @param  \App\Model\Article  $article
     * @return mixed
     */
    public function view(User $user, Article $article)
    {
        return $article->user_id == $user->id;
    }

    /**
     * Determine whether the user can create articles.
     *
     * @param  \App\Model\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return true;
    }

    /**
     * Determine whether the user can update the article.
     *
     * @param  \App\Model\User  $user
     * @param  \App\Model\Article  $article
     * @return mixed
     */
    public function update(User $user, $article = '')
    {
        return $article->user_id === $user->id;
    }

    /**
     * Determine whether the user can delete the article.
     *
     * @param  \App\Model\User  $user
     * @param  \App\Model\Article  $article
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
