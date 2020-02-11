<?php

namespace App\Providers;

use App\Model\Article;
use App\Model\Comment;
use App\Observers\ArticleObserver;
use App\Observers\CommentObserver;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
        $this->app->bind('Page', function(){
            return new \App\Foundation\Page;
        });

        $this->app->bind('Upload', function(){
            return new \App\Foundation\Upload;
        });
        $this->app->bind('CustomAuth', function(){
            return new \App\Foundation\CustomAuth;
        });
        $this->app->bind('RedisCache', function(){
            return new \App\Foundation\RedisCache;
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

        /*记录sql /
        DB::listen(function ($query) {
             file_put_contents(storage_path('sql_log.txt'), $query->sql . "\r\n", FILE_APPEND);
             // $query->bindings
             // $query->time
             // var_dump($query->time);
         });//*/
    }
}
