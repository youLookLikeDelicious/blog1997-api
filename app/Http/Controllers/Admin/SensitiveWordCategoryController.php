<?php

namespace App\Http\Controllers\Admin;

use Exception;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Model\SensitiveWordCategory;
use App\Http\Requests\Admin\SensitiveCategoryRequest;
use App\Contract\Repository\SensitiveWordCategory as RepositorySensitiveWordCategory;
use Illuminate\Http\Request;

/**
 * @group Sensitive word management
 * 
 * 敏感词汇分管理
 */
class SensitiveWordCategoryController extends Controller
{
    /**
     * Display category of sensitive word
     * 
     * 获取敏感词汇分离列表
     * 
     * @queryParam name 分类名称
     * @queryParam rank 屏蔽等级, 1:替换,2:审批,3:拒绝
     * @responseFile response/admin/sensitive-word-category/index.json
     * @return Response
     */
    public function index (Request $request, RepositorySensitiveWordCategory $repository)
    {
        $data = $repository->all($request);

        return response()->success($data);
    }
    
    /**
     * Store newly created category of sensitive work
     * 
     * 新建敏感词汇分类
     * 
     * @bodyParam name string required 分类名称,名称是唯一的
     * @bodyParam rand number required 屏蔽的级别,1:替换,2:审批,3:拒绝
     * @responseFile response/admin/sensitive-word-category/store.json
     * @param SensitiveCategoryRequest $request
     * @return Response
     */
    public function store (SensitiveCategoryRequest $request)
    {
        // 获取表单数据
        $category = $request->validated();

        $newCategory = '';

        DB::transaction(function () use ($category, &$newCategory) {
            $newCategory = SensitiveWordCategory::create($category);
        });
    
        $newCategory->append('editAble');

        return response()->success($newCategory, '分类添加成功');
    }

    /**
     * Update the specific category of sensitive word
     *
     * 更新指定的分类
     * 
     * @bodyParam category 敏感词汇分类ID
     * @bodyParam name string required 分类名称,名称是唯一的
     * @bodyParam rand number required 屏蔽的级别,1:替换,2:审批,3:拒绝
     * @responseFile response/admin/sensitive-word-category/update.json
     * @param SensitiveCategoryRequest $request
     * @param SensitiveWordCategory $category
     * @return \Illuminate\Http\Response
     */
    public function update (SensitiveCategoryRequest $request, SensitiveWordCategory $category)
    {
        $data = $request->validated();
        
        $category->update($data);

        $category->append('editAble');
        
        return response()->success($category, '分类修改成功');
    }
    /**
     * Destroy the specific category of sensitive work
     * 
     * 删除敏感词汇分类,同时该分类下所有的词汇也会被删除
     * 
     * @urlParam category 敏感词汇分类ID
     * @responseFile response/admin/sensitive-word-category/update.json
     * @return \Illuminate\Http\Response
     */
    public function destroy (SensitiveWordCategory $category)
    {
        DB::transaction(function () use ($category) {
            $category->delete();
        });

        return response()->success('', '敏感词分类删除成功');
    }
}
