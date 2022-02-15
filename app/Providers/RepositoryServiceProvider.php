<?php
namespace App\Providers;

use App\Contract\Repository\Tag;
use App\Contract\Repository\Auth;
use App\Contract\Repository\Role;
use App\Contract\Repository\Topic;
use App\Contract\Repository\ThumbUp;
use Illuminate\Support\ServiceProvider;
use App\Contract\Repository\MessageBox;
use App\Repository\Home\Tag as HomeTag;
use App\Contract\Repository\FriendLink;
use App\Repository\Admin\Tag as AdminTag;
use App\Repository\Role as RepositoryRole;
use App\Contract\Repository\SensitiveWord;
use App\Contract\Repository\ArticleBackUp;
use App\Repository\Admin\Auth as AdminAuth;
use App\Contract\Repository\IllegalComment;
use App\Repository\Admin\Topic as AdminTopic;
use App\Repository\Home\Article as HomeArticle;
use App\Repository\Comment as RepositoryComment;
use App\Repository\Admin\ThumbUp as AdminThumbUp;
use App\Repository\Admin\Article as AdminArticle;
use App\Contract\Repository\SensitiveWordCategory;
use Illuminate\Contracts\Support\DeferrableProvider;
use App\Repository\Home\FriendLink as HomeFriendLink;
use App\Repository\MessageBox as MessageBoxRepository;
use App\Repository\Admin\FriendLink as AdminFriendLink;
use App\Repository\SensitiveWord as RepositorySensitiveWord;
use App\Repository\Admin\ArticleBackUp as AdminArticleBackUp;
use App\Repository\IllegalComment as RepositoryIllegalComment;
use App\Contract\Repository\Article as ContractRepositoryArticle;
use App\Contract\Repository\Comment as ContractRepositoryComment;
use App\Contract\Repository\Manual;
use App\Repository\Admin\Manual as AdminManual;
use App\Repository\Admin\SensitiveWordCategory as AdminSensitiveWordCategory;

class RepositoryServiceProvider extends ServiceProvider implements DeferrableProvider
{    
    /**
     * All of the container singletons that should be registered.
     *
     * @var array
     */
    public $singletons = [
        MessageBox::class => MessageBoxRepository::class,
        ArticleBackUp::class => AdminArticleBackUp::class,
        IllegalComment::class => RepositoryIllegalComment::class,
        ContractRepositoryComment::class => RepositoryComment::class,
        SensitiveWordCategory::class => AdminSensitiveWordCategory::class,
        SensitiveWord::class => RepositorySensitiveWord::class,
        ThumbUp::class => AdminThumbUp::class,
        Auth::class => AdminAuth::class,
        Role::class => RepositoryRole::class,
        Topic::class => AdminTopic::class
    ];

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register ()
    {
        /*************************************************
         * 动态绑定 article repository
         *************************************************/
        $this->app->singleton(ContractRepositoryArticle::class, function ($app) {
            if (request()->is('api/admin*')) {
                return $app->make(AdminArticle::class);
            }

            return $app->make(HomeArticle::class);
        });


        /*************************************************
         * 动态绑定 friend link repository
         *************************************************/
        $this->app->singleton(FriendLink::class, function ($app) {
            if (request()->is('api/admin/*')) {
                return $app->make(AdminFriendLink::class);
            }
            
            return $app->make(HomeFriendLink::class);
        });

        /*************************************************
         * 动态绑定 tag repository
         *************************************************/
        $this->app->singleton(Tag::class, function ($app) {
            if (request()->is('api/admin/*')) {
                return $app->make(AdminTag::class);
            }
            
            return $app->make(HomeTag::class);
        });

        $this->app->singleton(Manual::class, function ($app) {
            if (request()->is('api/admin/*')) {
                return $app->make(AdminManual::class);
            }
            
            return $app->make(HomeTag::class);
        });
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
    }
    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [
            Tag::class,
            Role::class,
            Auth::class,
            Topic::class,
            ThumbUp::class,
            FriendLink::class,
            MessageBox::class,
            SensitiveWord::class,
            SensitiveWordCategory::class,
            ContractRepositoryComment::class,
            ContractRepositoryArticle::class,
        ];
    }
}