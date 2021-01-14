<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Model\SensitiveWord;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Events\SensitiveWordBatchInsert;
use App\Service\ExtractSensitiveWordService;
use App\Http\Requests\Admin\BatchDeleteRequest;
use App\Http\Requests\Admin\SensitiveWordRequest;
use App\Http\Requests\Admin\SensitiveWordImportRequest;
use App\Contract\Repository\SensitiveWord as RepositorySensitiveWord;
use Illuminate\Support\Facades\Log;

class SensitiveWordController extends Controller
{
    /**
     * 获取敏感词列表
     * Method GET
     * 
     * @param $request
     * @param $id  category表中的id
     */
    public function index (Request $request, RepositorySensitiveWord $repository) 
    {
        $result = $repository->all($request);

        return response()->success($result);
    }


    /**
     * 添加敏感词汇
     *
     * @param SensitiveWordRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store (SensitiveWordRequest $request) 
    {
        // 获取验证后的数据
        $data = $request->validated();
        $newSensitiveWord = '';

        // 开始事务
        DB::transaction(function () use ($data, &$newSensitiveWord) {
            
            $newSensitiveWord = SensitiveWord::create($data);

            $newSensitiveWord->append('editAble');
        });

        return response()->success($newSensitiveWord, '敏感词添加成成功');
    }

    /**
     * 更新操作
     *
     * @param Request $request
     * @param int $id
     * @return void
     */
    public function update(SensitiveWordRequest $request, SensitiveWord $word)
    {
        $data = $request->validated();

        $word->update($data);

        $word->append('editAble');

        return response()->success($word, '敏感词汇更新成功');
    }

    /**
     * Destroy sensitive word
     *
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
     * Destroy specified records
     *
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
     * 批量导入敏感词汇
     *
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
