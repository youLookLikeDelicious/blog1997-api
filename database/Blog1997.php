<?php

use Illuminate\Database\Eloquent\Model;

/**
 * 定义一个虚拟模型
 * 
 * 博客留言的多态关系对应到该模型
 */
class Blog1997 extends Model
{
    
    public $dateFormat = 'U';
    
    protected $table = 'blog1997';
}