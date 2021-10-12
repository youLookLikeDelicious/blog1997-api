<?php
namespace App\Foundation;

/**
 * 提取图片的颜色
 * 
 * @see https://www.the-art-of-web.com/php/extract-image-color/
 */
class ImageSampler
{
    private $img;
    private $callback = NULL;

    protected $percent = 5;
    protected $steps = 10;

    public $w, $h;
    public $sample_w = 0;
    public $sample_h = 0;

    /**
     * 读取文件信息
     *
     * @return void
     */
    public function make($imagePath)
    {
        if (!$this->img = imagecreatefromjpeg($imagePath)) {
            die("Error loading image: {$imagePath}");
        }
        $this->w = imagesx($this->img);
        $this->h = imagesy($this->img);

        $this->sample_w = $this->w / $this->steps;
        $this->sample_h = $this->h / $this->steps;
        $this->initialized = TRUE;

        return $this;
    }

    /**
     * 设置取值范围
     *
     * @param int $percent
     * @return this
     */
    public function setPercent($percent)
    {
        $percent = intval($percent);
        if (($percent < 1) || ($percent > 50)) {
            die("Your \$percent value needs to be between 1 and 50.");
        }
        $this->percent = $percent;

        return $this;
    }

    /**
     * 设置 step
     *
     * @param int $steps
     * @return this
     */
    public function setSteps($steps)
    {
        $steps = intval($steps);
        if (($steps < 1) || ($steps > 50)) {
            die("Your \$steps value needs to be between 1 and 50.");
        }
        $this->steps = $steps;

        return $this;
    }

    /**
     * 设置回调函数
     *
     * @param $callback
     * @return this
     */
    private function setCallback($callback)
    {
        try {
            $fn = new \ReflectionFunction($callback);
            if ($fn->getNumberOfParameters() != 4) {
                throw new \ReflectionException("Invalid parameter count in callback function.  Usage: fn(int, int, int, bool) { ... }");
            }
            $this->callback = $callback;
        } catch (\ReflectionException $e) {
            die($e->getMessage());
        }

        return $this;
    }

    private function getPixelColor($x, $y)
    {
        $rgb = imagecolorat($this->img, $x, $y);
        $r = ($rgb >> 16) & 0xFF;
        $g = ($rgb >> 8) & 0xFF;
        $b = $rgb & 0xFF;
        return [$r, $g, $b];
    }

    public function sample($callback = NULL)
    {
        if (($this->sample_w < 2) || ($this->sample_h < 2)) {
            die("Your sampling size is too small for this image - reduce the \$steps value.");
        }

        if ($callback) {
            $this->setCallback($callback);
        }

        $sample_size = round($this->sample_w * $this->sample_h * $this->percent / 100);

        $color_stack = [];
        for ($i = 0, $y = 0; $i < $this->steps; $i++, $y += $this->sample_h) {
            $flag = FALSE;
            $row_color_stack = [];
            for ($j = 0, $x = 0; $j < $this->steps; $j++, $x += $this->sample_w) {
                $total_r = $total_g = $total_b = 0;
                for ($k = 0; $k < $sample_size; $k++) {
                    $pixel_x = $x + rand(0, $this->sample_w - 1);
                    $pixel_y = $y + rand(0, $this->sample_h - 1);
                    list($r, $g, $b) = $this->getPixelColor($pixel_x, $pixel_y);
                    $total_r += $r;
                    $total_g += $g;
                    $total_b += $b;
                }
                $avg_r = round($total_r / $sample_size);
                $avg_g = round($total_g / $sample_size);
                $avg_b = round($total_b / $sample_size);
                if ($this->callback) {
                    call_user_func_array($this->callback, [$avg_r, $avg_g, $avg_b, !$flag]);
                }
                $row_color_stack[] = [$avg_r, $avg_g, $avg_b];
                $flag = TRUE;
            }
            $color_stack[] = $row_color_stack;
        }

        return collect($color_stack)->map(function ($item) {
            return array_unique(array_map(function ($val) {
                return implode(',', $val);
            }, $item));
        });
    }
}