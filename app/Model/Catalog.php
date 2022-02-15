<?php
namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Catalog extends Model
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
        'updated_at' => 'datetime:Y-m-d H:i',
    ];

    /**
     * Define a one-to-one relationship with next node.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function nextNode()
    {
        return $this->hasOne(Catalog::class, 'pre_node');
    }

    /**
     * Define a one-to-one relationship with previous node.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function preNode()
    {
        return $this->hasOne(Catalog::class, 'next_node');
    }

    /**
     * Define a one-to-one relationship with parent node.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function parent()
    {
        return $this->hasOne(Catalog::class, 'id', 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(Catalog::class, 'parent_id', 'id')->with('children');
    }

    /**
     * Get the model's relationships in array form.
     *
     * @return array
     */
    public function relationsToArray()
    {
        $relationArray = parent::relationsToArray();
        
        if (empty($relationArray['children'])) {
            $relationArray['children'] = null;
        } else {
            // 排序
            $sortedChildren = [];
            $children = collect($relationArray['children']);
            $sortedChildren[] = $tempNode = $children->where('pre_node', 0)->first();
            while($tempNode = $children->where('id', $tempNode['next_node'])->first()) {
                $sortedChildren[] = $tempNode;
            }
        }

        return $relationArray;
    }

    public function manualArticle()
    {
        return $this->hasOne(ManualArticle::class, 'catalog_id')->withDefault([
            'content'     => '',
            'is_markdown' => 'yes',
            'catalog_id'  => $this->id,
            'title'       => $this->name,
            'manual_id'   => $this->manual_id
        ]);
    }
}