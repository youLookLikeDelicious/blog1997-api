<?php

namespace App\Providers;

use App\Contract\Repository\Gallery;
use App\Contract\Auth\Factory;
use App\Http\Controllers\Auth\LoginManager;
use App\Model\Article;
use App\Model\Comment;
use App\Model\SensitiveWord;
use App\Model\SensitiveWordCategory;
use App\Observers\ArticleObserver;
use App\Observers\CommentObserver;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\ServiceProvider;
use App\Model\ThumbUp;
use App\Model\Topic;
use App\Observers\SensitiveWordCategoryObserver;
use App\Observers\SensitiveWordObserver;
use App\Observers\ThumbUpObserver;
use App\Observers\TopicObserver;
use App\Contract\Repository\User as UserContract;
use App\Model\Auth;
use App\Model\Tag;
use App\Observers\AuthObserver;
use App\Observers\TagObserver;
use App\Observers\UserObserver;
use App\Repository\Admin\Gallery as AdminGallery;
use App\Model\User;
use App\Repository\User as RepositoryUser;

class AppServiceProvider extends ServiceProvider
{
    public $bindings = [
        UserContract::class => RepositoryUser::class,
        Gallery::class => AdminGallery::class,
    ];

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
        $this->app->bind('Page', function () {
            return new \App\Foundation\Page;
        });

        $this->app->bind('Upload', function () {
            return new \App\Foundation\Upload;
        });

        $this->app->bind(Factory::class, function ($app) {
            return new LoginManager($app);
        });

        $this->app->singleton('CacheModel', function () {
            return new \App\Foundation\CacheModel;
        });
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // 监听模型的CURD
        Article::observe(ArticleObserver::class);
        Comment::observe(CommentObserver::class);
        ThumbUp::observe(ThumbUpObserver::class);
        SensitiveWord::observe(SensitiveWordObserver::class);
        SensitiveWordCategory::observe(SensitiveWordCategoryObserver::class);
        Topic::observe(TopicObserver::class);
        Tag::observe(TagObserver::class);
        Auth::observe(AuthObserver::class);
        User::observe(UserObserver::class);

        /*记录sql /
        DB::listen(function ($query) {
             file_put_contents(storage_path('sql_log.txt'), $query->sql . "\r\n", FILE_APPEND);
             // $query->bindings
             // $query->time
             // var_dump($query->time);
         });//*/
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [
            Gallery::class
        ];
    }
}
