<?php
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// 登录登出接口
Route::group(['namespace' => 'Auth'], function () {
    Route::post('/oauth/authorize', 'LoginController@loginByProvider');  // 登录
    Route::post('/auth/login', 'LoginController@login');  // 登录
    Route::any('/oauth/currentUser', 'AuthorizeController@currentUser');
    Route::post('/oauth/logout', 'LoginController@logout');
    Route::put('/auth/manager/{manager}', 'ManagerRegisterController@update')
        ->name('manager.inti.password');
    Route::post('/oauth/sign-up', 'SignUpController@store');
});

/**
 * Send reset password link
 */
Route::post('/user/password/reset', 'UserController@sendResetLinkEmail')
    ->name('password.reset.send-email')
    ->middleware('throttle:2,1');

Route::group(['namespace' => 'Admin', 'prefix' => 'admin'], function () {
    // master权限的接口
    Route::group(['middleware' => 'auth'], function () {
        // 敏感词汇
        Route::post('/sensitive-word/import', 'SensitiveWordController@import');

        // 敏感词api
        Route::delete('/sensitive-word/batch-delete', 'SensitiveWordController@batchDestroy');
        Route::resource('sensitive-word', 'SensitiveWordController')
            ->only(['index', 'store', 'update', 'destroy'])
            ->parameters([
                'sensitive-word' => 'word'
            ]);

        // 添加敏感词分类
        Route::resource('sensitive-word-category', 'SensitiveWordCategoryController')
            ->only(['index', 'store', 'destroy', 'update'])
            ->parameters([
                'sensitive-word-category' => 'category'
            ]);

        // 获取举报的信息
        Route::get('/illegal-info', 'MessageBoxController@index')
            ->name('illegal-info.index');
        Route::post('/illegal-info/approve/{id}', 'MessageBoxController@approve')
            ->name('illegal-info.approve');
        Route::post('/illegal-info/ignore/{id}', 'MessageBoxController@ignore')
            ->name('illegal-info.ignore');

        // 相册的相关操作
        Route::resource('gallery', 'GalleryController')
            ->only(['index', 'store', 'destroy']);

        // 友链相关的操作
        Route::resource('friend-link', 'FriendLinkController')
            ->only(['index', 'destroy', 'update', 'store']);

        Route::resource('auth', 'AuthController')
            ->only(['store', 'update', 'destroy', 'index', 'create']);

        Route::resource('role', 'RoleController')
            ->only(['store', 'update', 'destroy', 'index']);

        Route::get('/manager/user/{email}', 'ManagerController@user')
            ->name('manager.get.user');
        Route::resource('manager', 'ManagerController')
            ->only(['update', 'create', 'index']);

        Route::post('/comment/approve', 'CommentController@approve')
            ->name('comment.approve');

        Route::delete('/comment/reject', 'CommentController@reject')
            ->name('comment.reject');

        Route::resource('comment', 'CommentController')
            ->only(['index']);

        Route::resource('system-setting', 'SystemSettingController')
            ->only(['index', 'update']);

        Route::resource('email-config', 'EmailConfigController')
            ->only(['index', 'update', 'store']);

        Route::resource('tag', 'TagController')
            ->only(['index', 'update', 'store', 'destroy', 'create']);
            
        Route::get('/log/{type?}', 'LogController@index')
            ->name('system.log');
    });

    // 后台需要管理员权限的接口
    Route::group(['middleware' => 'auth'], function () {
        // 首页
        Route::get('/dashboard', 'IndexController@index')
            ->name('admin.dashboard');

        // 上传图片
        Route::post('/upload/image/{category}', 'UploadController@uploadImage');

        Route::resource('topic', 'TopicController')
            ->only(['index', 'store', 'update', 'destroy']);

        // 创建微信图文素材
        Route::post('/article/create-wechat-material/{article}', 'ArticleController@createWeChatMaterial')
            ->name('wechat.material');

        Route::post('/article/restore/{article}', 'ArticleController@restore')
            ->name('article.restore');
        Route::resource('article', 'ArticleController')
            ->only(['index', 'store', 'update', 'destroy', 'show', 'create']);

        Route::get('/notification', 'MessageBoxController@getNotification')
            ->name('notification.index');
        Route::get('/notification/commentable-comments/{id}', 'MessageBoxController@getCommentAbleComments')
            ->name('notification.comments');        
    });
});

// 更新账号信息
Route::group(['middleware' => 'auth'], function () {
    Route::post('/user/update/{user}', 'UserController@update')
        ->name('user.update');
    Route::post('/user/bind', 'UserController@bind')
        ->name('user.bind');
    Route::post('/user/unbind/{account}', 'UserController@unbind')
        ->name('user.unbind');
    Route::post('/user/rebind', 'UserController@rebind')
        ->name('user.rebind');
    Route::get('/user/profile', 'UserController@profile')
        ->name('user.profile');
    Route::delete('/user/{user}', 'UserController@destroy')
        ->name('user.destroy');
    Route::post('/admin/password/update', 'Auth\ResetPasswordsController@reset')
        ->name('password.update'); 
});

Route::group(['namespace' => 'Home'], function () {
    // 需要用户权限的接口
    Route::group(['middleware' => 'auth'], function () {
        Route::resource('comment', 'CommentController')
            ->only(['destroy', 'store']);

        // 点赞
        Route::post('/thumb-up', 'ThumbUpController@store');

        // 举报
        Route::post('/report-illegal-info', 'ReportIllegalInfoController@store');
    });

    // 前台接口
    Route::group(['middleware' => ['home']], function () {
        // 首页
        Route::get('/', 'IndexController@index')
            ->middleware('sitemap:1,weekly,1');

        // 获取友链
        Route::get('/friend-link', 'IndexController@getFriendLink');
        //     ->middleware('sitemap:0.8,monthly,1'); 

        // 专题列表
        Route::get('/article/tags', 'ArticleController@tags')
            ->middleware('sitemap:0.9,weekly');

        // 获取评论
        // 评论相关操作
        Route::get('/comment/reply/{rootId}/{offset}', 'CommentController@getReply')
            ->where('offset', '[1-9]\d*');
        Route::post('/article/comments/{articleId}', 'ArticleController@comments');

        // 获取文章列表
        Route::match(['post', 'get'], '/article/search', 'ArticleController@all');

        // 文章详情
        Route::get('/article/{articleId}', 'ArticleController@find')
            ->middleware('sitemap:1,weekly');
        
        // 获取博客留言
        Route::get('/leave-message', 'LeaveMessageController@index')
            ->middleware('sitemap:0.8,weekly,1');
    });
});
