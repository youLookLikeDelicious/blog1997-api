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
 * 敏感词汇分类控制器
 */
class SensitiveWordCategoryController extends Controller
{
    /**
     * 获取列表
     * Method GET
     * 
     * @return Response
     */
    public function index (Request $request, RepositorySensitiveWordCategory $repository)
    {
        $data = $repository->all($request);

        return response()->success($data);
    }
    
    /**
     * 新建分类
     * Method Post
     * 
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
     * 更新操作
     * Method PUT
     *
     * @param Request $request
     * @return Response
     */
    public function update (SensitiveCategoryRequest $request, SensitiveWordCategory $category)
    {
        $data = $request->validated();
        
        $category->update($data);

        $category->append('editAble');
        
        return response()->success($category, '分类修改成功');
    }
    /**
     * 删除分类
     * Method DELETE
     * 
     * @return Response
     */
    public function destroy (SensitiveWordCategory $category)
    {
        DB::transaction(function () use ($category) {
            $category->delete();
        });

        return response()->success('', '敏感词分类删除成功');
    }
}
