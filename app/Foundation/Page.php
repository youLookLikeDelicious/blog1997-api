<?php
namespace App\Foundation;
class Page{
    protected $url;						// 请求路径
    protected $limit;					// 每页显示的记录条数
    protected $show_pages;				// 显示的页数
    protected $total;					// 总的记录数
    protected $uri;						// 请求的uri
    protected $cur_p;					// 当前页
    protected $pages;					// 总的页数
    protected $first;					// 起始位置
    protected $last;					// 结束位置
    protected $next;					// 下一页
    protected $previous;				// 上一页
    protected $builder;                 // database query bulder
    /*
     * @builder laravle query builder
     * @param total 总的记录数
     * @param limite 一页显示的记录条数
     * @param pages 显示的页数
     * @return ['pagination' => **, 'records' => **]
     */
    public function paginate($builder, $limit = 10, $show_pages = 10, $p = null){
        $this->limit = $limit;
        $this->total = $builder->count();
        $this->init( $show_pages, $p );
        return [
            "pagination" => $this->getPageInfo(),
            "records" => $this->getRecords($builder)
        ];
    }
    protected function init($show_pages, $p){
        $this->getUri();
        $this->pages = (int)ceil($this->total / $this->limit);
        $this->setCurPage($p);
        // 显示的页数范围不能大于总的页数范围
        $this->show_pages = $show_pages > $this->pages? $this->pages : $show_pages;
        $this->setPageRange();
        $this->setNext();
        $this->setPre();
    }
    // 获取uri
    // return string /index.html? | /index.html?a=admin&
    protected function getUri(){
        // 获取请求的地址
        // URI 用来指定要访问的页面。例如 "/index.html" | /index.html?p=2。
        $uri = $_SERVER['REQUEST_URI'] . (strpos($_SERVER['REQUEST_URI'], '?')? '' : '?');
        // 判断uri中是否有request请求
        $parse = parse_url($uri);
        if(isset($parse['query'])){
            // 删除p参数，重构uri
            parse_str($parse['query'], $param);
            unset($param['p']);
            $uri = empty($param)? $parse['path'] . '?' : $parse['path'] . http_build_query($param) . '&';
        }
        $this->uri = $uri;
    }
    /*
     * 查询记录
     * 计算总的记录数
     */
    public function getRecords($builder){
        return $builder->limit($this->limit)
            ->offset( ($this->cur_p - 1) * $this->limit )
            ->get();
    }
    // 对当前页的值进行调整
    // 如果当前页的值小于1，值为1
    // 如果当前页的值大于总的页数，值为总页数
    public function setCurPage($p){
        if($p != NULL && is_numeric($p)) {
            $this->cur_p = $p;
            return;
        }

        $p = isset($_GET['p']) && is_numeric($_GET['p'])? $_GET['p'] + 0 : 1;
        if(empty($p) || $p < 1){
            $this->cur_p = 1;
        }
        elseif($p > $this->pages){
            $this->cur_p = $this->pages;
        }
        else {
            $this->cur_p = $p;
        }
    }
    // 设置首页和第尾页的值
    protected function setPageRange(){
        $mid = (int)ceil($this->show_pages / 2);
        // 当前页 =< 显示页数的一半, 从第一页开始(前半部分)
        if($this->cur_p <= $mid){
            $this->first = 1;
        }
        // 当前页 >= 总页数 - 显示的页数，起始页为总页数 - 显示的页数（后半部分）
        else if($this->cur_p >=  ($this->pages - $mid)){
            $this->first = $this->pages - $mid;
        }
        // 当前页 > 显示页数的一半，起始页 = 总页数 - 显示的页数 + 1(中间部分)
        else{
            $this->first = $this->cur_p - $mid + 1;
        }
        $tmp = $this->first + $this->show_pages - 1;
        $this->last = $tmp > $this->pages ? $this->pages: $tmp;
    }
    // 获取上一页
    protected function setPre(){
        $tmp = $this->cur_p - 1;
        $this->previous = $tmp > 0? $tmp : NULL;
    }
    // 获取下一页
    protected function setNext(){
        $tmp = $this->cur_p + 1;
        $this->next = $tmp <= $this->pages? $tmp : NULL;
    }
    // 获取页数列表
    // return array
    protected function getPageInfo(){
        return [
            'total'   => $this->total,
            'uri'     => $this->uri,
            'curPage' => $this->cur_p,
            'next'	  => $this->next,
            'previous'=> $this->previous,
            'first'   => $this->first,
            'last' 	  => $this->last,
            'pages'   => $this->pages,
        ];
    }
}
