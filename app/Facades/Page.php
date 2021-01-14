<?php
namespace App\Facades;
use Illuminate\Support\Facades\Facade;
/**
 * 分页工具类
 * 对当前页 $cur_p 自动检测，最大值为$pages, 最小值为1
 * 对$show_page 自动检测，最大值为总页数
 * 
 * @method static array paginate ($query, int $limit = 20, int $showPages = 10)
 */
class Page extends Facade{
    protected static function getFacadeAccessor(){
        return 'Page';
    }
}
