<?php

namespace App\Http\Controllers\Admin;

use App\Model\Tag;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\TagRequest;
use App\Contract\Repository\Tag as Repository;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TagController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, Repository $tag)
    {
        $data = $tag->all($request);

        return response()->success($data);
    }
    /**
     * Show the resource for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Repository $tag)
    {
        $data = $tag->flatted(true);

        return response()->success($data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(TagRequest $request)
    {
        $data = $request->validated();
        
        $this->authorize('create', [Tag::class, $data]);
        
        $tagModel = Tag::create($data);

        return response()->success($tagModel, '标签添加成功');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Model\Tag  $tag
     * @return \Illuminate\Http\Response
     */
    public function show(Repository $tag, int $id)
    {
        $result = $tag->find($id);

        return response()->success($result);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Model\Tag  $tag
     * @return \Illuminate\Http\Response
     */
    public function update(TagRequest $request, Tag $tag)
    {
        $data = $request->validated();

        $tag->update($data);
        
        return response()->success($tag, '标签修改成功');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Model\Tag  $tag
     * @return \Illuminate\Http\Response
     */
    public function destroy(Tag $tag)
    {
        $this->authorize('delete', $tag);

        DB::transaction(function () use ($tag) {
            $tag->delete();
            
        });

        return response()->success('', '标签删除成功');
    }
}
