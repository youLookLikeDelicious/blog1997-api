<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Cookie;

class ImageController extends Controller
{
    /**
     * Get image
     *
     * @param Request $request
     * @param string $type
     * @param string $dir
     * @param string $name
     * @param boolean $isWebp
     * @return image
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     */
    public function find(Request $request, $type, $dir, $name)
    {
        $cookie = Cookie::get(config('app.name') . '_session');

        if (!$cookie && !App::runningUnitTests()) {
            abort(404);
        }

        $fileName = $this->getFileName($request, $type, $dir, $name);

        $storagePath = storage_path($fileName);

        if (is_file($storagePath)) {
            header('Access-Control-Allow-Credentials:true');
            setcookie('SameSite', 'Secure', 0, '/');
            return response()->file($storagePath);
        }

        abort(404);
    }

    /**
     * 获取请求的文件名称
     *
     * @return string
     */
    public function getFileName(Request $request, $type, $dir, $name)
    {
        // 获取文件的后缀
        $preExit = strrchr($name, '.');
        $ext = $preExit ? '.webp' : '';

        $name = str_replace($preExit, '', $name);

        // 如果客户端支持webp
        if ($request->header('Support-Webp') === 'no') {
            $ext = $preExit;
        }

        $t = $request->input('t');

        if ((!$t  && $type === 'article') || $t === 'min') {
            $name .= '.min';
        }

        // 生成文件路径
        $fileName = "image/{$type}/{$dir}/{$name}{$ext}";

        return $fileName;
    }
}
