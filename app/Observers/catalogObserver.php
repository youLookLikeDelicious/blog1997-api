<?php

namespace App\Observers;

use App\Model\Catalog;

class catalogObserver
{
    /**
     * Handle the catalog "created" event.
     *
     * @param  \App\Model\Catalog  $catalog
     * @return void
     */
    public function created(Catalog $catalog)
    {
        $data = [];

        if ($lastNode = $this->getLastNode($catalog->parent_id, $catalog->id)) {
            $lastNode->update(['next_node' => $catalog->id]);
            $data['pre_node'] = $lastNode->id;
        }
        
        if ($catalog->parent) {
            $data['level'] = $catalog->parent->level + 1;
        }
        
        if ($data) {
            Catalog::where('id', $catalog->id)->update($data);
        }
    }

    /**
     * Handle the catalog "updated" event.
     *
     * @param  \App\Model\Catalog  $catalog
     * @return void
     */
    public function updated(Catalog $catalog)
    {
        if ($catalog->isDirty('pre_node')) {
            // 将当前节点放到第一个位置
            if ($catalog->preNode === 0) {
                // 获取当前层级的第一个节点
                $firstCatalog = Catalog::where([
                    'parent_id', $catalog->parent_id,
                    'pre_node', 0
                ])->first();
                dd($firstCatalog);
                $catalog->next_node = $firstCatalog ? $firstCatalog->id : 0;
                $catalog->save();
            } else {
                Catalog::where('id', $catalog->getOriginal('pre_node'))->update(['next_node' => $catalog->getOriginal('next_node')]);
                Catalog::where('id', $catalog->getOriginal('next_node'))->update(['pre_node' => $catalog->getOriginal('pre_node')]);
                Catalog::where('id', $catalog->per_node)->update(['next_node' => $catalog->id]);
                Catalog::where('id', $catalog->id)->update(['next_node' => $catalog->preNode ? $catalog->preNode->next_node : 0]);
            }
        } else if ($catalog->isDirty('parent_node')) {
            // 将节点放到父节点的最后一个节点
            $data = [];
            if ($lastNode = $this->getLastNode($catalog->parent_id, $catalog->id)) {
                Catalog::where('id', $catalog->id)->update(['next_node' => $catalog->id]);
                $data['pre_node'] = $lastNode->id;
            } else {
                $data['pre_node'] = 0;
                $data['next_node'] = 0;
            }

            Catalog::where('id', $catalog->id)->update($data);
        }
    }

    /**
     * Handle the catalog "deleted" event.
     *
     * @param  \App\Model\Catalog  $catalog
     * @return void
     */
    public function deleted(Catalog $catalog)
    {
        $preNode = $catalog->preNode;
        $nextNode = $catalog->nextNode;

        if ($preNode) {
            $preNode->next_node = $nextNode
                ? $nextNode->id
                : 0;

            if ($nextNode) {
                $nextNode->pre_node = $preNode->id;
            }
        } else if($nextNode) {
            $nextNode->pre_node = $preNode ? $preNode->id : 0;

            if ($preNode) {
                $preNode->next_node = $nextNode->id;
            }
        }

        $preNode->save();
        $nextNode->save();
    }

    /**
     * 获取指定父节中的最后一个节点
     *
     * @param int $parentId
     * @param int $excludeId
     * @return Catalog
     */
    protected function getLastNode($parentId, $excludeId = 0)
    {
        $condition = [
            ['parent_id', $parentId],
            ['next_node', 0]
        ];

        if ($excludeId) {
            $condition[] =  ['id', '<>', $excludeId];
        }

        return Catalog::where($condition)->first();
    }
}
