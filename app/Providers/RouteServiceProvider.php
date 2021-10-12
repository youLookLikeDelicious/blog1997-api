<?php

namespace App\Providers;

use App\Model\Article as ModelArticle;
use App\Model\ArticleBackUp;
use App\Contract\Repository\Article;
use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * This namespace is applied to your controller routes.
     *
     * In addition, it is set as the URL generator's root namespace.
     *
     * @var string
     */
    protected $namespace = 'App\Http\Controllers';

    /**
     * Define your route model bindings, pattern filters, etc.
     *
     * @return void
     */
    public function boot()
    {
        Route::pattern('id', '[0-9]+');

        Route::pattern('p', '[0-9]+');

        Route::pattern('perPage', '[0-9]+');

        Route::pattern('limit', '[0-9]+');

        $this->bindArticle();

        parent::boot();
    }

    /**
     * Define the routes for the application.
     *
     * @return void
     */
    public function map()
    {
        $this->mapApiRoutes();

        $this->mapWebRoutes();

        //
    }

    /**
     * Define the "web" routes for the application.
     *
     * These routes all receive session state, CSRF protection, etc.
     *
     * @return void
     */
    protected function mapWebRoutes()
    {
        Route::middleware('web')
            ->namespace($this->namespace)
            ->group(base_path('routes/web.php'));
    }

    /**
     * Define the "api" routes for the application.
     *
     * These routes are typically stateless.
     *
     * @return void
     */
    protected function mapApiRoutes()
    {
        Route::prefix('api')
            ->middleware('api')
            ->namespace($this->namespace)
            ->group(base_path('routes/api.php'));
    }

    /**
     * Bind article model
     * This is for admin controller
     * @return void
     */
    protected function bindArticle()
    {
        Route::bind('article', function ($id) {
            if (is_int($id)) {
                abort(404);
            }

            $routeName = Route::currentRouteName();

            switch ($routeName) {
                case 'article.show':
                    return app()->make(Article::class)->find($id);
                case 'article.destroy':
                    return ModelArticle::find($id) ?? ArticleBackUp::findOrFail($id);
                case 'article.restore':
                    return ArticleBackUp::findOrFail($id)->makeHidden(['delete_role', 'delete_reason', 'deleted_at']);
                default:
                    return ModelArticle::findOrFail($id);
            }
        });
    }
}
