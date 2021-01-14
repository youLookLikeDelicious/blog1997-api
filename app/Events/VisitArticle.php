<?php

namespace App\Events;

class VisitArticle {
    /**
     * Article id
     * 
     * @var number
     */
    public $id;
    
    public function __construct(int $id)
    {
        $this->id = $id;
    }
}