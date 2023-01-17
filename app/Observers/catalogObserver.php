<?php

namespace App\Observers;

use App\Models\Catalog;

class catalogObserver
{
    /**
     * Handle the catalog "created" event.
     *
     * @param  \App\Models\Catalog  $catalog
     * @return void
     */
    public function created(Catalog $catalog)
    {
        $data = [];

        if ($lastNode = $this->getLastNode($catalog->parent_id, $catalog->id)) {
            Catalog::where('id', $lastNode->id)->update(['next_node_id' => $catalog->id]);
            $data['pre_node_id'] = $lastNode->id;
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
     * @param  \App\Models\Catalog  $catalog
     * @return void
     */
    public function updated(Catalog $catalog)
    {
        if ($catalog->isDirty('pre_node_id')) {            
            // 从链表中移除该节点
            if ($preNodeId = $catalog->getOriginal('pre_node_id')) {
                Catalog::where('id', $preNodeId)->update(['next_node_id' => $catalog->next_node_id]);
            }
            if ($catalog->next_node_id) {
                Catalog::where('id', $catalog->next_node_id)->update(['pre_node_id' => $catalog->getOriginal('pre_node_id')]);
            }

            // 重新插入该节点
            $currentPreNode = Catalog::find($catalog->pre_node_id);
            Catalog::where('id', $catalog->pre_node_id)->update(['next_node_id' => $catalog->id]);
            Catalog::where('id', $catalog->id)->update([
                'next_node_id' => $currentPreNode->next_node_id,
                'parent_id' => $currentPreNode->parent_id,
                'level' => $currentPreNode->level
            ]);
            if ($currentPreNode->next_node_id) {
                Catalog::where('id', $currentPreNode->next_node_id)->update(['pre_node_id' => $catalog->id]);
            }

            return;
        }        
 
        // 将当前节点的下一个节点 指向next节点
        if ($catalog->isDirty('next_node_id')) {
            // 从链表中删除该节点
            if ($catalog->pre_node_id) {
                Catalog::where('id', $catalog->pre_node_id)->update(['next_node_id' => $catalog->getOriginal('next_node_id')]);
            }
            if ($originNextId = $catalog->getOriginal('next_node_id')) {
                Catalog::where('id', $originNextId)->update(['pre_node_id' => $catalog->pre_node_id]);
            }

            // 重新插入该节点
            $nextNode = Catalog::find($catalog->next_node_id);
            Catalog::where('id', $catalog->next_node_id)->update(['pre_node_id' => $catalog->id]);
            Catalog::where('id', $catalog->id)->update([
                'pre_node_id' => $nextNode->pre_node_id,
                'parent_id' => $nextNode->parent_id,
                'level' => $nextNode->level
            ]);
            if ($nextNode->pre_node_id) {
                Catalog::where('id', $nextNode->pre_node_id)->update(['next_node_id' => $catalog->id]);
            }

            return;
        }

        if ($catalog->isDirty('parent_id')) {
            // 从之前的链表中删除该节点
            if ($catalog->pre_node_id) {
                Catalog::where('id', $catalog->pre_node_id)->update(['next_node_id' => $catalog->next_node_id]);
            }
            if ($catalog->next_node_id) {
                Catalog::where('id', $catalog->next_node_id)->update(['pre_node_id' => $catalog->pre_node_id]);
            }

            // 将节点放到父节点的最后一个节点
            $data = ['next_node_id' => 0, 'pre_node_id' => 0];
            
            if ($lastNode = $this->getLastNode($catalog->parent_id, $catalog->id)) {
                Catalog::where('id', $lastNode->id)->update(['next_node_id' => $catalog->id]);
                $data['pre_node_id'] = $lastNode->id;
            }

            $data['level'] = $catalog->parent ?  $catalog->parent->level + 1 : 0;

            Catalog::where('id', $catalog->id)->update($data);
        }
    }

    /**
     * Handle the catalog "deleted" event.
     *
     * @param  \App\Models\Catalog  $catalog
     * @return void
     */
    public function deleted(Catalog $catalog)
    {
        if ($catalog->pre_node_id) {
            Catalog::where('id', $catalog->pre_node_id)->update(['next_node_id' => $catalog->next_node_id]);
        }
        
        if ($catalog->next_node_id) {
            Catalog::where('id', $catalog->next_node_id)->update(['pre_node_id' => $catalog->pre_node_id]);
        }
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
            ['next_node_id', 0]
        ];

        if ($excludeId) {
            $condition[] =  ['id', '<>', $excludeId];
        }

        return Catalog::where($condition)->first();
    }
}
