<?php
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/image/{type}/{dir}/{name}/{isWebp?}', 'ImageController@find')->middleware('cors');

Route::group(['middleware' => 'x-session'], function () {
    Route::get('/admin/manager/register', 'Auth\ManagerRegisterController@create')
        ->name('manager.register');

    Route::get('/admin/captcha', function () {
        return captcha();
    });
});

Route::get('/admin/verify', 'Auth\SignUpController@verify')
    ->name('user.verify');

Route::get('/admin/login', 'Admin\VueController@login')
    ->name('admin.login');

Route::get('/admin/login/{any}', 'Admin\VueController@login')->where('any', '.*');

Route::get('/admin/password/reset', 'Auth\ResetPasswordsController@showResetForm')
    ->name('password.reset');   

Route::get('/admin/{any?}', 'Admin\VueController@index')
    ->where('any', '.*')
    ->name('admin.index');

Route::get('/home', 'HomeController@index')->name('home');

Route::get('/{sitemap}', function ($sitemap) {
    return response()->file(storage_path("sitemap/{$sitemap}"));
})->where('sitemap', 'sitemap.*');