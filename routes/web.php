<?php
use App\Schedule\RedisDataToMysql;
use App\Model\SiteMap;
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

Route::get('/', function (\Illuminate\Http\Request $request) {
    return view('welcome');
});

Route::get('/image/{type}/{dir}/{name}/{isWebp?}', function (\Illuminate\Http\Request $request, $type, $dir, $name, $isWebp = true) {
    $ext = strrchr($name, '.');
    $name = str_replace($ext, '', $name);

    if ($isWebp) {
        $ext = '.webp';
    }
    // 生成文件路径
    $filePath = "image/{$type}/{$dir}/{$name}{$ext}";

    $storagePath = storage_path($filePath);

    if (is_file($storagePath)) {
        header('Access-Control-Allow-Credentials:true');
        setcookie('SameSite', 'Secure', 0, '/');
        return response()->file($storagePath);
    } else {
        return view('404');
    }
});
