<?php

namespace App\Providers;

use App\Model\Article;
use App\Observers\ArticleObserver;
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
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
        Article::observe(ArticleObserver::class);
    }
}
