<?php

namespace App\Http\Controllers\Admin;

use App\Models\Manual;
use App\Models\Catalog;
use Illuminate\Http\Request;
use App\Models\ManualArticle;
use App\Http\Controllers\Controller;
use App\Http\Requests\ManualRequest;
use App\Contract\Repository\Manual as RepositoryManual;
use App\Http\Requests\Admin\ManualArticle as AdminManualArticle;
use Illuminate\Support\Facades\DB;

class ManualController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, RepositoryManual $repository)
    {
        $data = $repository->index($request);

        return $data->response();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param \App\Http\Requests\ManualRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(ManualRequest $request)
    {
        Manual::create($request->validated());

        return response()->success('', '手册创建成功');
    }

    /**
     * Display the specified resource.
     *
     * @queryParam with_catalogs  是否加载目录 1加载
     * @urlParam article required 文章的id
     * @param Request $request
     * @param  \App\Models\Manual  $manual
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, Manual $manual)
    {
        if ($request->input('with_catalogs')) {
            $manual->load('catalogs.children'); 
        }

        return response()->success($manual);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \\App\Http\Requests\ManualRequest $request
     * @param  \App\Models\Manual  $manual
     * @return \Illuminate\Http\Response
     */
    public function update(ManualRequest $request, Manual $manual)
    {
        $manual->update($request->validated());

        return response()->success('', '手册更新成功');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Manual  $manual
     * @return \Illuminate\Http\Response
     */
    public function destroy(Manual $manual)
    {
        $manual->delete();

        return response()->success('', '手册删除成功');
    }

    public function showArticle(Catalog $catalog)
    {
        return response()->success($catalog->manualArticle, 'success');
    }

    /**
     * Store new article resource
     * 
     * @param ManualArticle $manualArticle
     * @param AdminManualArticle $request
     * @return \Illuminate\Http\Response
     */
    public function storeArticle(AdminManualArticle $request)
    {
        DB::transaction(function () use ($request) {
            $data = $request->validated();

            // 创建目录
            if (isset($data['parent_id']) && empty($data['catalog_id'])) {
                $catalog = Catalog::create([
                    'type'      => 2,
                    'name'      => $data['title'],
                    'parent_id' => $data['parent_id'],
                    'manual_id' => $data['manual_id']
                ]);

                $data['catalog_id'] = $catalog->id;
            }

            if (isset($data['parent_id'])) {
                unset($data['parent_id']);
            }

            ManualArticle::create($data);
        });

        return response()->success('', '文章创建成功');
    }
    
    /**
     * Update article resource in storage
     *
     * @param AdminManualArticle $request
     * @param ManualArticle $article
     * @return \Illuminate\Http\Response
     */
    public function updateArticle(AdminManualArticle $request, ManualArticle $manualArticle)
    {
        DB::transaction(function () use ($request, $manualArticle) {
            $manualArticle->update($request->validated());
        });

        return response()->success('', '文章更新成功');
    }
}
