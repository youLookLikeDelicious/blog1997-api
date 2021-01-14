<?php

namespace Tests\Unit\Service;

use App\Service\FilterStringService;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;

class FilterStringServiceTest extends TestCase
{
    /**
     * A basic unit test filter string.
     * @group unit
     *
     * @return void
     */
    public function test_remove_html_tags()
    {
        $service = new FilterStringService();

        $str = $service->removeHTMLTags('<img src="sdfd"><script></script><img src="src">', 'img');

        $this->assertEquals($str, '<img src="sdfd"><img src="src">');
    }

    /**
     * A basic unit test filter string.
     * @group unit
     *
     * @return void
     */
    public function test_remove_all_html_tags()
    {
        $service = new FilterStringService();

        $str = $service->removeHTMLTags('<img src="sdfd"><script></script><img src="src">a Ha');

        $this->assertEquals($str, 'a Ha');
    }

    /**
     * A basic unit test filter string.
     * @group unit
     *
     * @return void
     */
    public function test_extract_img_url()
    {
        $service = new FilterStringService();

        $im = imagecreate(110, 20);
        imagecolorallocate($im, 0, 0, 0);
        $text_color = imagecolorallocate($im, 233, 14, 91);
        imagestring($im, 1, 5, 5,  "Blog1997 test", $text_color);

        $path = storage_path('image/test');
        if (!is_dir($path)) {
            mkdir($path, 0777);
        }

        $filename = $path . '/test.jpg';
        if (!is_file($filename)){
            touch($filename);
            imagepng($im, $filename);
        }


        imagedestroy($im);

        $urls = $service->extractImagUrl('<img data-src="https://timgsa.baidu.com/timg?image&quality=80&size=b9999_10000&sec=1600427584197&di=0d5e49eec20001824334183f4d960162&imgtype=0&src=http%3A%2F%2Fa0.att.hudong.com%2F56%2F12%2F01300000164151121576126282411.jpg"><script></script><img data-src="/image/test/test.jpg">a Ha');

        $this->assertEquals('/image/test/test.jpg', $urls[0]);
        $this->assertEquals(1, count($urls));
    }

    /**
     * 测试将图片的src属性转为 data-src属性
     * @group unit
     *
     * @return void
     */
    public function test_cover_image_src()
    {
        $service = new FilterStringService();

        $str = '<img src="src" /><img src="src-1" /><img src="src-2" /><img src="src-3" />';

        $result = $service->coverImageSrc($str);

        $this->assertEquals('<img class="lazy" data-src="src" /><img class="lazy" data-src="src-1" /><img class="lazy" data-src="src-2" /><img class="lazy" data-src="src-3" />', $result);

        $str = '<img data-src="12" /> <img data-src="12" />';
        $result = $service->coverImageSrc($str);
        $this->assertEquals('<img data-src="12" /> <img data-src="12" />', $result);
    }
}
