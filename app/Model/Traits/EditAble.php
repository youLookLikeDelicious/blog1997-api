<?php
namespace App\Model\Traits;

trait EditAble
{
    /**
     * Get custom editAble attribute
     *
     * @return Boolean
     */
    public function getEditAbleAttribute()
    {
        return false;
    }
}