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
Route::middleware('auth')->group(function () {
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
});
