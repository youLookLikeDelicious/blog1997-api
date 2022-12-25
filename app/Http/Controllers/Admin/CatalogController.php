<?php

namespace App\Http\Controllers\Admin;

use App\Model\Catalog;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\CatalogRequest;
use Illuminate\Support\Facades\DB;

class CatalogController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  CatalogRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CatalogRequest $request)
    {
        $data = $request->validated();

        DB::transaction(function () use ($data) {
            Catalog::create($data);
        });

        return response()->success('', '手册添加成功');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Model\Catalog  $catalog
     * @return \Illuminate\Http\Response
     */
    public function update(CatalogRequest $request, Catalog $catalog)
    {
        $data = $request->validated();

        DB::transaction(function () use ($data, $catalog) {
            $catalog->update($data);
        });

        return response()->success('', '手册更新成功');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Model\Catalog  $catalog
     * @return \Illuminate\Http\Response
     */
    public function destroy(Catalog $catalog)
    {
        if ($catalog->isCateNode && $catalog->children->isNotEmpty()) {
            return response()->error('该章节下还有内容,暂无法删除');
        }

        DB::transaction(function () use ($catalog) {
            $catalog->delete();
        });

        return response()->success('', '节点删除成功');
    }
}
