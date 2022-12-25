<?php

namespace App\Model;

use Illuminate\Support\Facades\URL;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Manual extends Model
{
    use SoftDeletes;
    
    public $dateFormat = 'U';

    protected $guarded = [];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'updated_at' => 'datetime:Y-m-d H:i'
    ];

    /**
     * Convert manual cover to url asset
     *
     * @param string $value
     * @return string
     */
    public function getCoverAttribute($value)
    {
        return URL::asset($value);
    }

    /**
     * 定义和目录的一对多关系
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function catalogs()
    {
        return $this->hasMany(Catalog::class, 'manual_id')->where('parent_id', 0);
    }

    /**
     * Get the model's relationships in array form.
     *
     * @return array
     */
    public function relationsToArray()
    {
        $relationArray = parent::relationsToArray();
        
        if (!empty($relationArray['catalogs'])) {
            // 排序
            $sortedChildren = [];
            $children = collect($relationArray['catalogs']);
            $sortedChildren[] = $tempNode = $children->where('pre_node_id', 0)->first();
            while($tempNode = $children->firstWhere('id', $tempNode['next_node_id'])) {
                $sortedChildren[] = $tempNode;
            }

            $relationArray['catalogs'] = $sortedChildren;
        }

        return $relationArray;
    }
}
