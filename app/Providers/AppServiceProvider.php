<?php

namespace App\Providers;

use App\Models\Tag;
use App\Models\Auth;
use App\Models\User;
use App\Models\Topic;
use App\Models\Article;
use App\Models\Comment;
use App\Models\ThumbUp;
use Illuminate\Support\Str;
use App\Service\RSAService;
use App\Service\MapService;
use App\Service\CurlService;
use App\Models\SensitiveWord;
use App\Contract\Auth\Factory;
use App\Observers\TagObserver;
use App\Observers\UserObserver;
use App\Observers\AuthObserver;
use App\Foundation\ImageSampler;
use App\Observers\TopicObserver;
use App\Observers\ArticleObserver;
use App\Observers\CommentObserver;
use App\Contract\Repository\Gallery;
use App\Models\SensitiveWordCategory;
use Illuminate\Support\ServiceProvider;
use App\Observers\SensitiveWordObserver;
use App\Repository\User as RepositoryUser;
use App\Http\Controllers\Auth\LoginManager;
use App\Observers\SensitiveWordCategoryObserver;
use App\Contract\Repository\User as UserContract;
use App\Models\Catalog;
use App\Observers\catalogObserver;
use App\Repository\Admin\Gallery as AdminGallery;
use Illuminate\Database\Eloquent\Relations\Relation;

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
        $this->app->singleton('Page', function () {
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

        $this->app->singleton('CurlService', function () {
            return new CurlService;
        });

        $this->app->singleton('RSAService', function () {
            return new RSAService;
        });

        $this->app->singleton('ImageSampler', function () {
            return new ImageSampler;
        });

        // map 服务
        $this->app->singleton('MapService', function () {
            return new MapService;
        });

        $this->registerRequestRebindHandler();
    }

    /**
     * Handle the re-binding of the request binding.
     *
     * @return void
     */
    protected function registerRequestRebindHandler()
    {
        $this->app->rebinding('request', function ($app, $request) {
            $perPage = $request->input('perPage');
            $currentPage = $request->input('p');
            if ($currentPage && !$perPage || $perPage > 500) {
                $request->merge(['perPage' => 10]);
            }
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
        Tag::observe(TagObserver::class);
        Auth::observe(AuthObserver::class);
        User::observe(UserObserver::class);
        Topic::observe(TopicObserver::class);
        Article::observe(ArticleObserver::class);
        Comment::observe(CommentObserver::class);
        Catalog::observe(catalogObserver::class);
        SensitiveWord::observe(SensitiveWordObserver::class);
        SensitiveWordCategory::observe(SensitiveWordCategoryObserver::class);

        Relation::enforceMorphMap([
            'article' => Article::class,
            'comment' => Comment::class,
            'thumbup' => ThumbUp::class
        ]);

        /*记录sql */
        if (config('app.env') === 'local') {
            \DB::listen(function ($query) {
                 file_put_contents(
                    storage_path(date('Y-m-d') . '_sql_log.txt'),
                    '[' . date('Y-m-d H:i:s') . ']  ' . Str::replaceArray('?', $query->bindings, $query->sql) . '[  ' . implode(', ', $query->bindings) . PHP_EOL,
                    FILE_APPEND
                );
                //  dump($query->bindings);
                 // $query->time
                 // var_dump($query->time);
             });//*/
        }
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
