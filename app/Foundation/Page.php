<?php

namespace App\Foundation;

class Page{
    protected $url;						// 请求路径
    protected $limit;					// 每页显示的记录条数
    protected $showPages;				// 显示的页数
    protected $total;					// 总的记录数
    protected $uri;						// 请求的uri
    protected $currentPage;					// 当前页
    protected $pages;					// 总的页数
    protected $first;					// 起始位置
    protected $last;					// 结束位置
    protected $next;					// 下一页
    protected $previous;				// 上一页
    protected $query;                 // database query query
    /**
     * 获取分页数据
     * 
     * @param  Illuminate\Database\Query\Builder $query
     * @param int limit 一页显示的记录条数
     * @param int showPages 显示的页数
     * @return array
     */
    public function paginate ($query, int $limit = 20, int $showPages = 10) {

        $this->limit = request()->input('limit', $limit);

        // 获取带id的字段
        $model = $query->getModel();

        if ($query->getQuery()->unions) {
            // laravel compileUnionAggregate 的别名就是temp_table。无法自定义
            $keyForCount = "temp_table.{$model->getKeyName()}";
        } else {
            $keyForCount = "{$model->getTable()}.{$model->getKeyName()}"; // 表名.主键
        }
        
        // 计算总的记录数量
        $this->total = $query->count($keyForCount);

        $this->init( $showPages );

        $result = [
            "pagination" => $this->getPageInfo(),
            "records" => $this->getRecords($query)
        ];

        return $result;
    }

    protected function init ($showPages) {

        $this->getUri();

        $this->pages = (int)ceil($this->total / $this->limit);

        $this->setCurrentPage();

        // 显示的页数范围不能大于总的页数范围
        $this->showPages = $showPages > $this->pages? $this->pages : $showPages;

        $this->setPageRange();

        $this->setNext();

        $this->setPre();
    }

    /**
     * 重构uri
     * 
     * return string /index.html? | /index.html?a=admin&
     */
    protected function getUri(){
        // 获取请求的地址
        // URI 用来指定要访问的页面。例如 "/index.html" | /index.html?p=2。
        // 测试中没用REQUEST_URI参数
        $uri = empty($_SERVER['REQUEST_URI']) ?? '';

        if (!strpos($uri, '?')) {
            $uri .= '?';
        }

        // 判断uri中是否有request请求
        $parse = parse_url($uri);

        // 删除p参数，重构uri
        if(isset($parse['query'])){
            parse_str($parse['query'], $param);

            unset($param['p']);

            $uri = empty($param)
                ? $parse['path'] . '?' 
                : $parse['path'] . http_build_query($param) . '&';
        }

        $this->uri = $uri;
    }

    /**
     * 查询记录
     * 计算总的记录数
     */
    public function getRecords($query){
        return $query->limit($this->limit)
            ->offset( ($this->currentPage - 1) * $this->limit )
            ->get();
    }

    /** 
     * 对当前页的值进行调整
     * 如果当前页的值小于1，值为1
     * 如果当前页的值大于总的页数，值为总页数
     */
    public function setCurrentPage($p = 1) {

        $p = request()->input('p', 1);

        if (!is_numeric($p)) {
            $p = 1;
        }

        if ($p < 1) {
            $this->currentPage = 1;
        }
        elseif ($p > $this->pages) {
            $this->currentPage = $this->pages;
        }
        else {
            $this->currentPage = $p;
        }
    }

    /**
     * 设置首页和尾页的值
     */
    protected function setPageRange () {

        $mid = (int)ceil($this->showPages / 2);

        // 当前页 =< 显示页数的一半, 从第一页开始(前半部分)
        if($this->currentPage <= $mid){
            $this->first = 1;
        }
        // 当前页 >= 总页数 - 显示的页数，起始页为总页数 - 显示的页数（后半部分）
        else if($this->currentPage >=  ($this->pages - $mid)){
            $this->first = $this->pages - $this->showPages + 1;
        }
        // 当前页 > 显示页数的一半，起始页 = 总页数 - 显示的页数 + 1(中间部分)
        else{
            $this->first = $this->currentPage - $mid + 1;
        }

        $tmp = $this->first + $this->showPages - 1;

        $this->last = $tmp > $this->pages ? $this->pages: $tmp;
    }
    
    /**
     * 获取上一页
     */
    protected function setPre () {

        $tmp = $this->currentPage - 1;

        $this->previous = $tmp > 0? $tmp : NULL;
    }
    
    /**
     * 获取下一页
     */
    protected function setNext () {

        $tmp = $this->currentPage + 1;

        $this->next = $tmp <= $this->pages? $tmp : NULL;
    }
    
    /**
     * 获取页数列表
     * 
     * return array
     */
    protected function getPageInfo () {
        return [
            'total'   => $this->total,
            'uri'     => $this->uri,
            'currentPage' => $this->currentPage,
            'next'	  => $this->next,
            'previous'=> $this->previous,
            'first'   => $this->first,
            'last' 	  => $this->last,
            'pages'   => $this->pages,
        ];
    }
}
