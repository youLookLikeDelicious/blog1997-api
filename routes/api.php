<?php

use Illuminate\Http\Request;

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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
Route::group([], function () {
    // 权限相关
    Route::get('/oauth/authorize', 'Auth\AuthorizeController@auth');
    Route::get('/oauth/authorize-wechat', 'Auth\AuthorizeController@auth');
    Route::get('/oauth/authorize-qq', 'Auth\AuthorizeController@auth');
    Route::get('/oauth/curUser', 'Auth\AuthorizeController@curUser');
    Route::post('/oauth/logout', 'Auth\AuthorizeController@logout');
});
// 需要用户权限的接口
Route::group(['middleware' => ['custom-auth']], function () {
    // 上传文件相关操作
    Route::post('/upload/upload-image', 'Upload\UploadController@uploadImage');
    // 专题相关操作
    Route::post('/admin/create-topic', 'Admin\TopicController@createTopic');
    Route::get('/admin/topic-list', 'Admin\TopicController@getTopicList');
    Route::post('/admin/topic-delete', 'Admin\TopicController@deleteTopic');
    // 文章相关操作
    Route::get('/admin/article-list/{topicId}', 'Admin\ArticleController@getArticleList');
    Route::post('/admin/article-create', 'Admin\ArticleController@createArticle');
    Route::post('/admin/article-get', 'Admin\ArticleController@getArticle');
    Route::post('/admin/article-delete', 'Admin\ArticleController@deleteArticle');
    Route::post('/admin/article-upload-image', 'Admin\ArticleController@uploadImage');
    // 友链相关的操作
    Route::get('/admin/friend-link-list', 'Admin\FriendLinkController@getList');
    Route::post('/admin/friend-link-create', 'Admin\FriendLinkController@createFriendLink');
    Route::post('/admin/friend-link-delete', 'Admin\FriendLinkController@deleteFriendLink');
    // 相册的相关操作
    Route::post('/admin/gallery-upload', 'Admin\GalleryController@upload');
    Route::get('/admin/gallery-get', 'Admin\GalleryController@getList');
    // 评论相关操作
    Route::post('/comment/create', 'Home\CommentController@create');
    Route::post('/comment/delete', 'Home\CommentController@delete');
    // 点赞或取消点赞
    Route::post('/thumb-up/{action}', 'Home\ThumbUpController@thumbUp');
});
// 前台接口
Route::group(['middleware' => ['home', 'session']], function () {
    Route::get('/', 'Home\IndexController@index')
            ->middleware('sitemap:1,weekly,1'); // 首页
    Route::get('/friend-link', 'Home\IndexController@getFriendLink');
        //     ->middleware('sitemap:0.8,monthly,1'); // 获取友链
    Route::get('/topic/{topicId?}', 'Home\TopicController@index')
            ->middleware('sitemap:0.9,weekly'); // 专题列表
    Route::get('/article/{id}', 'Home\ArticleController@index')
            ->middleware('sitemap:1,weekly'); // 文章详情
    Route::get('/article-get-list', 'Home\ArticleController@getList'); // 获取文章列表
    Route::get('/comment/get/{id}/{type}', 'Home\CommentController@get'); // 获取评论列表
    Route::get('/comment/get-reply/{rootId}/{offset}', 'Home\CommentController@getReply'); // 获取评论的回复
    Route::get('/leave-message/index', 'Home\LeaveMessageController@index')
            ->middleware('sitemap:0.8,weekly,1'); // 获取博客留言
    // 搜索的接口
    Route::get('/search/{content}/{orderBy?}', 'Home\SearchController@index');
});
