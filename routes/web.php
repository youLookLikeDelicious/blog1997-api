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

Route::get('/image/{type}/{dir}/{name}/{isWebp?}', 'ImageController@find')
    ->middleware('cors')
    ->name('retrieve.image');

Route::get('/auth/password/reset', 'Auth\ResetPasswordsController@showResetForm')
    ->name('password.reset');   

// Route::get('/home', 'HomeController@index')->name('home');
Route::get('/{sitemap}', function ($sitemap) {
    $fullPath = storage_path("sitemap/{$sitemap}");
    if (!file_exists(storage_path("sitemap/{$sitemap}"))) {
        abort(404);
    }
    return response()->file($fullPath);
})->where('sitemap', 'sitemap.*');
