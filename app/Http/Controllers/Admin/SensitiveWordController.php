<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Models\SensitiveWord;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Events\SensitiveWordBatchInsert;
use App\Service\ExtractSensitiveWordService;
use App\Http\Requests\Admin\BatchDeleteRequest;
use App\Http\Requests\Admin\SensitiveWordRequest;
use App\Http\Requests\Admin\SensitiveWordImportRequest;
use App\Contract\Repository\SensitiveWord as RepositorySensitiveWord;
use Illuminate\Support\Facades\Log;

/**
 * @group Sensitive word management
 * 
 * 敏感词汇管理
 */
class SensitiveWordController extends Controller
{
    /**
     * Display sensitive work
     * 
     * 显示所有的敏感词汇
     * 
     * @queryParam category_id 敏感词汇分类ID
     * @queryParam word        词汇
     * @responseFile response/admin/sensitive-word/index.json
     * @param Request $request
     * @param RepositorySensitiveWord $repository
     * @return \Illuminate\Http\Response
     */
    public function index (Request $request, RepositorySensitiveWord $repository) 
    {
        $result = $repository->all($request);

        return $result->toResponse($request);
    }


    /**
     * Store newly create word
     * 
     * 添加新的敏感词汇
     *
     * @bodyParam category_id int    required 敏感词汇分类ID
     * @bodyParam word        string required 敏感词汇
     * @responseFile response/admin/sensitive-word/store.json
     * @param SensitiveWordRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store (SensitiveWordRequest $request) 
    {
        // 获取验证后的数据
        $data = $request->validated();

        // 开始事务
        DB::transaction(function () use ($data) {
            SensitiveWord::create($data);
        });

        return response()->success('', '敏感词添加成成功');
    }

    /**
     * Update the specific word
     * 
     * 更新敏感词汇
     * 
     * @urlParam word 敏感词汇ID
     * @bodyParam category_id int    required 敏感词汇分类ID
     * @bodyParam word        string required 敏感词汇
     * @responseFile response/admin/sensitive-word/update.json
     * @param SensitiveWordRequest $request
     * @param SensitiveWord $word
     * @return \Illuminate\Http\Response
     */
    public function update(SensitiveWordRequest $request, SensitiveWord $word)
    {
        $data = $request->validated();

        $word->update($data);

        return response()->success('', '敏感词汇更新成功');
    }

    /**
     * Destroy the specific sensitive word
     *
     * @urlParam word 敏感词汇ID
     * @responseFile response/admin/sensitive-word/destroy.json
     * @param SensitiveWord $word
     * @return \Illuminate\Http\Response
     */
    public function destroy(SensitiveWord $word)
    {
        DB::transaction(function () use ($word) {
            $word->delete();
        });

        Log::info('敏感词删除', ['operate' => 'create', 'result'=> 'success']);

        return response()->success('', '敏感词删除成功');
    }

    /**
     * Destroy specified sensitive words
     *
     * 批量删除敏感慈湖
     * 
     * @bodyParam ids   array required 敏感词汇ID列表
     * @bodyParam ids.* int   required 敏感词汇ID
     * @responseFiles response/admin/sensitive-word/batch-destroy.json
     * @param BatchDeleteRequest $request
     * @return \Illuminate\Http\Response
     */
    public function batchDestroy (BatchDeleteRequest $request) 
    {        
        $data = $request->validated();

        DB::transaction(function () use ($data) {
            foreach($data['ids'] as $id) {
                SensitiveWord::find($id)->delete();
            }
        });

        $message ='敏感词删除成功，共删除'. count($data['ids']) .'条记录';

        Log::info($message, ['operate' => 'create', 'result'=> 'success']);

        return response()->success('', $message);
    }

    /**
     * Import sensitive words from file
     * 
     * 批量导入敏感词汇
     * 文件格式
     *    word1
     *    word2
     * 
     * @responseFiles response/admin/sensitive-word/import.json
     * @param SensitiveWordImportRequest $request
     * @param ExtractSensitiveWordService $service
     * @return \Illuminate\Http\Response
     */
    public function import (SensitiveWordImportRequest $request, ExtractSensitiveWordService $service) 
    {               
        $data = $request->validated();

        // 插入数据库中的结果
        $result = $service->make($data);

        // 插入敏感词
        DB::transaction(function () use ($result, $data) {
            SensitiveWord::insert($result);
            
            event(new SensitiveWordBatchInsert(count($result), $data['category_id']));
        });

        return response()->success('', '批量导入成功,共录入' . count($result) . '条记录');
    }
}
